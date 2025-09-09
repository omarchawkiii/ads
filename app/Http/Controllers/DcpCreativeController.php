<?php

namespace App\Http\Controllers;

use App\Models\DcpCreative;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ZipArchive;
use PharData;


class DcpCreativeController extends Controller
{

    private const CHUNK_SIZE = 10_485_760; // 10 * 1024 * 1024


    public function index()
    {
        return view('advertiser.dcp_creatives.index');
    }

    public function show(Request $request)
    {
        $dcp_creative = DcpCreative::findOrFail($request->id) ;
        return Response()->json(compact('dcp_creative'));
    }

    public function get()
    {
        $dcp_creatives = DcpCreative::orderBy('name', 'asc')->get();
        return Response()->json(compact('dcp_creatives'));
    }

    public function store(Request $request)
    {
        /*
            $request->validate([
                'xml_file' => [
                    'required',
                    'file',
                    // certains hébergeurs servent text/plain : on ne se fie pas qu’au mimetype
                    'max:5120', // 5MB
                ],
            ], [
                'xml_file.required' => 'Please select a file.',
                'xml_file.file'     => 'The file is invalid.',
                'xml_file.max'      => 'The file is too large (max 5MB).',
            ]);

            $file = $request->file('xml_file');
            $xmlString = @file_get_contents($file->getRealPath());
            if ($xmlString === false || trim($xmlString) === '') {
                return response()->json([
                    'ok'      => false,
                    'message' => 'The file is empty or unreadable.',
                ], 422);
            }
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
            if ($xml === false) {
                $errors = array_map(fn($e) => trim($e->message), libxml_get_errors());
                libxml_clear_errors();

                return response()->json([
                    'ok'      => false,
                    'message' => "The file is not valid XML.",
                    'detail'  => $errors, // utile pour debug
                ], 422);
            }


            if ($xml->getName() !== 'CompositionPlaylist') {
                return response()->json([
                    'ok'      => false,
                    'message' => "Root element must be 'CompositionPlaylist', found '{$xml->getName()}'.",
                ], 422);
            }



            $namespaces = $xml->getDocNamespaces(true);
            $defaultNs  = $namespaces[''] ?? null; // namespace par défaut
            $xml->registerXPathNamespace('cpl', $defaultNs ?: '');

            $idNode   = $xml->xpath('/cpl:CompositionPlaylist/cpl:Id');
            $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:ContentTitleText');
            if (empty($nameNode)) {
                $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:AnnotationText');
            }

            if (empty($idNode)) {
                return response()->json([
                    'ok'      => false,
                    'message' => "Missing <Id> in CompositionPlaylist.",
                ], 422);
            }
            $id = isset($idNode[0]) ? trim((string)$idNode[0]) : null;
            $name = isset($nameNode[0]) ? trim((string)$nameNode[0]) : null;

            // 4) Somme des durations (on prend la durée des MainPicture par Reel)
            $total_duration = 0;

            $durationNodesMainPicture = $xml->xpath('/cpl:CompositionPlaylist/cpl:ReelList/cpl:Reel/cpl:AssetList/cpl:MainPicture/cpl:Duration');
            $duration_main_picture = isset($durationNodesMainPicture[0]) ? trim((string)$durationNodesMainPicture[0]) : null;
            $total_duration +=  $duration_main_picture ;

            $durationNodesMainSound = $xml->xpath('/cpl:CompositionPlaylist/cpl:ReelList/cpl:Reel/cpl:AssetList/cpl:MainSound/cpl:Duration');
            $duration_main_sound = isset($durationNodesMainSound[0]) ? trim((string)$durationNodesMainSound[0]) : null;

            $total_duration +=   $duration_main_sound ;


            return response()->json([
                'ok'            => true,
                'message'       => "DCP Creative Created Successfully.",

            ]);
        */




    }

    public function destroy(Request $request)
    {
        try
        {
            $dcp_creative = DcpCreative::findOrFail($request->id) ;
            $dcp_creative->delete();
            return response()->json([
                'message' => 'DCP Creative deleted successfully.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Operation failed.',
            ], 500);
        }
    }



    public function init(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'file_size' => 'required|numeric', // on ne l’utilise pas côté PHP pour la taille, juste indicatif
        ]);

        // sécurité: on n’accepte que .zip
        $original = $request->string('file_name')->toString();
        if (!preg_match('/\.(zip|tar|7z)$/i', $original)) {
            return response()->json(['ok' => false, 'message' => 'Seuls les fichiers .zip sont acceptés.'], 422);
        }

        $uploadId = (string) Str::uuid();
        $tmpDir   = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        return response()->json([
            'ok'         => true,
            'upload_id'  => $uploadId,
            'chunk_size' => self::CHUNK_SIZE, // le front peut s’aligner dessus
        ]);
    }

    public function chunk(Request $request)
    {
        // Chaque requête contient un "chunk" + méta
        $request->validate([
            'upload_id' => 'required|string',
            'index'     => 'required|integer|min:0',
            'total'     => 'required|integer|min:1',
            'file_name' => 'required|string',
            'chunk'     => 'required|file|max:51200', // 50 Mo max par sécurité (on envoie 10 Mo)
        ], [], [
            'chunk' => 'chunk',
        ]);

        $uploadId = $request->input('upload_id');
        $index    = (int) $request->input('index');
        $total    = (int) $request->input('total');
        $file     = $request->file('chunk');

        $tmpDir = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            return response()->json(['ok' => false, 'message' => 'Upload inconnu (init manquant).'], 422);
        }

        // Sauvegarde du fragment en fichier .partN
        $partPath = "{$tmpDir}/part_{$index}";
        $file->move($tmpDir, "part_{$index}");

        // Petit check optionnel: taille > 0
        if (!is_file($partPath) || filesize($partPath) <= 0) {
            return response()->json(['ok' => false, 'message' => "Chunk {$index} vide ou invalide."], 422);
        }

        return response()->json([
            'ok'    => true,
            'index' => $index,
            'left'  => max(0, $total - ($index + 1)),
        ]);
    }

    public function complete(Request $request)
    {
        $request->validate([
            'upload_id'  => 'required|string',
            'total'      => 'required|integer|min:1',
            'file_name'  => 'required|string',
        ]);

        $uploadId  = $request->input('upload_id');
        $total     = (int) $request->input('total');
        $original  = $request->string('file_name')->toString();

        if (!preg_match('/\.(zip|tar|7z)$/i', $original)) {
            return response()->json(['ok' => false, 'message' => 'Seuls les fichiers .zip sont acceptés.'], 422);
        }

        $tmpDir = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            return response()->json(['ok' => false, 'message' => 'Upload inconnu.'], 422);
        }

        // Vérifier que tous les fragments existent
        for ($i = 0; $i < $total; $i++) {
            if (!is_file("{$tmpDir}/part_{$i}")) {
                return response()->json(['ok' => false, 'message' => "Chunk manquant: {$i}"], 422);
            }
        }

        // Nom final et dossier
        $safeName = substr(preg_replace('/[^A-Za-z0-9._-]/', '_', $original), 0, 180);
        $finalDir = storage_path('app/uploads/final');
        if (!is_dir($finalDir)) {
            @mkdir($finalDir, 0775, true);
        }

        $finalPath = "{$finalDir}/{$uploadId}_{$safeName}";

        // Assemblage en flux (support > 2 Go)
        $out = fopen($finalPath, 'ab');
        if (!$out) {
            return response()->json(['ok' => false, 'message' => 'Impossible de créer le fichier final.'], 500);
        }

        for ($i = 0; $i < $total; $i++) {
            $partPath = "{$tmpDir}/part_{$i}";
            $in = fopen($partPath, 'rb');
            if (!$in) {
                fclose($out);
                return response()->json(['ok' => false, 'message' => "Impossible de lire part_{$i}."], 500);
            }
            stream_copy_to_stream($in, $out);
            fclose($in);
        }
        fclose($out);


        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $warning = null;
        // (optionnel) Vérifier magic bytes ZIP: "PK\x03\x04"
        $fh = @fopen($finalPath, 'rb');
        if ($fh) {
            if ($ext === 'zip') {
                // ZIP peut commencer par PK\x03\x04 (fichier), PK\x05\x06 (empty archive), PK\x07\x08 (spanned)
                $magic = fread($fh, 4);
                if (!in_array($magic, ["PK\x03\x04", "PK\x05\x06", "PK\x07\x08"], true)) {
                    $warning = 'Le fichier assemblé ne semble pas être un ZIP valide (signature).';
                }
            } elseif ($ext === '7z') {
                // Signature 7z: 37 7A BC AF 27 1C
                $magic = fread($fh, 6);
                if ($magic !== "\x37\x7A\xBC\xAF\x27\x1C") {
                    $warning = 'Le fichier assemblé ne semble pas être un 7z valide (signature).';
                }
            } elseif ($ext === 'tar') {
                // TAR: "ustar" à l’offset 257 (POSIX ustar). Tous les TAR n’ont pas forcément cette marque, on émet un warning si absent.
                fseek($fh, 257);
                $ustar = fread($fh, 5);
                if ($ustar !== 'ustar') {
                    $warning = 'Le fichier assemblé ne présente pas la marque TAR POSIX (ustar).';
                }
            }
            fclose($fh);
        }

        if ($magic !== "PK\x03\x04") {
            // On laisse le fichier mais on prévient
            $warning = 'Attention: le fichier assemblé ne semble pas être un ZIP valide (magic bytes).';
        }

        // Nettoyage des .part
        for ($i = 0; $i < $total; $i++) {
            @unlink("{$tmpDir}/part_{$i}");
        }
        @rmdir($tmpDir);




        $uploadRoot  = storage_path('app/uploads');
        $extractDir  = "{$uploadRoot}/extracted/{$uploadId}";
        @mkdir($extractDir, 0775, true);

        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $extract = $this->extractArchive($finalPath, $ext, $extractDir);
        if (!$extract['ok']) {
            return response()->json([
                'ok'       => true, // l’upload est ok, mais extraction KO
                'message'  => 'Upload terminé mais extraction impossible.',
                'final'    => [
                    'path'     => $finalPath,
                    'filename' => basename($finalPath),
                    'size'     => filesize($finalPath),
                ],
                'warning'  => ($warning ?? null) ?: $extract['message'],
                //'creative' => ['id' => $creative->id, 'path' => $creative->path],
            ]);
        }

        $first = $this->findFirstCplXml($extractDir);
        if (!$first['ok']) {
            return response()->json([
                'ok'       => true,
                'message'  => 'Upload ok. Aucun fichier XML avec racine CompositionPlaylist trouvé.',
                'final'    => [
                    'path'     => $finalPath,
                    'filename' => basename($finalPath),
                    'size'     => filesize($finalPath),
                ],
                'warning'  => $warning ?? null,
                //'creative' => ['id' => $creative->id, 'path' => $creative->path],
            ]);
        }
        $meta = $this->parseCpl($first['xml']);

        $creative = DcpCreative::updateOrCreate([
            'uuid' =>$meta['uuid']
        ],
        [
            'uuid' =>$meta['uuid'],
            'name' =>$meta['name'],
            'duration' =>$meta['total_duration'],
            'path' =>$finalPath,

        ]);

        return response()->json([
            'ok'       => true,
            'message'  => 'Upload terminé, archive extraite, CPL trouvé et lu.',
            'final'    => [
                'path'     => $finalPath,
                'filename' => basename($finalPath),
                'size'     => filesize($finalPath),
                'archive_type' => $ext,
            ],
            'warning'  => $warning ?? null,
            'creative' => ['id' => $creative->id, 'path' => $creative->path],
            'cpl_file' => [
                'path' => $first['path'], // chemin du XML CPL lu
            ],
            'cpl_meta' => $meta, // UUID, name, durations, editrate…
        ]);


    }

    private function extractArchive(string $archivePath, string $ext, string $destDir): array
    {
        try {
            if ($ext === 'zip') {
                $zip = new ZipArchive();
                if ($zip->open($archivePath) === true) {
                    if (!$zip->extractTo($destDir)) {
                        $zip->close();
                        return ['ok' => false, 'message' => 'Extraction ZIP échouée'];
                    }
                    $zip->close();
                    return ['ok' => true];
                }
                return ['ok' => false, 'message' => 'Ouverture ZIP impossible'];
            }

            if ($ext === 'tar') {
                // TAR (et tar.gz/tgz si besoin : ajoute un gunzip avant si nécessaire)
                $phar = new PharData($archivePath);
                $phar->extractTo($destDir, null, true); // overwrite = true
                return ['ok' => true];
            }

            if ($ext === '7z') {
                // 7z nécessite l’outil CLI "7z" (p7zip). On essaie via shell.
                $bin = trim(shell_exec('command -v 7z') ?? '');
                if ($bin === '') {
                    return ['ok' => false, 'message' => '7z introuvable sur le serveur (p7zip requis).'];
                }
                $cmd = $bin . ' x ' . escapeshellarg($archivePath) . ' -o' . escapeshellarg($destDir) . ' -y';
                exec($cmd, $out, $code);
                if ($code !== 0) {
                    return ['ok' => false, 'message' => 'Extraction 7z échouée (code '.$code.').'];
                }
                return ['ok' => true];
            }

            return ['ok' => false, 'message' => 'Extension d’archive non supportée pour extraction.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Erreur extraction: '.$e->getMessage()];
        }
    }

    private function findFirstCplXml(string $rootDir): array
    {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootDir, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            if (!$file->isFile()) continue;
            if (!preg_match('/\.xml$/i', $file->getFilename())) continue;

            $path = $file->getPathname();
            $xmlString = @file_get_contents($path);
            if ($xmlString === false || trim($xmlString) === '') continue;

            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
            if ($xml === false) {
                libxml_clear_errors();
                continue;
            }
            if ($xml->getName() === 'CompositionPlaylist') {
                // On s’arrête au premier CPL trouvé
                return ['ok' => true, 'path' => $path, 'xml' => $xml];
            }
        }

        return ['ok' => false, 'message' => 'Aucun XML CPL trouvé'];
    }

    private function parseCpl(\SimpleXMLElement $xml): array
    {
        // Namespace par défaut (obligatoire pour XPath avec CPL)
        $namespaces = $xml->getDocNamespaces(true);
        $defaultNs  = $namespaces[''] ?? null; // namespace par défaut
        $xml->registerXPathNamespace('cpl', $defaultNs ?: '');

        // Id et Name
        $idNode   = $xml->xpath('/cpl:CompositionPlaylist/cpl:Id');
        $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:ContentTitleText');
        if (empty($nameNode)) {
            $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:AnnotationText');
        }

        $idRaw = isset($idNode[0]) ? trim((string)$idNode[0]) : null;
        $name  = isset($nameNode[0]) ? trim((string)$nameNode[0]) : null;

        // Extraction UUID depuis "urn:uuid:xxxxxxxx-xxxx-...." (ou retourne brut si aucun match)
        $uuid = null;
        if ($idRaw && preg_match('~([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})~', $idRaw, $m)) {
            $uuid = strtolower($m[1]);
        } else {
            $uuid = $idRaw;
        }

        // 4) Somme des durations (premier MainPicture + premier MainSound)
        $total_duration = 0;

        $durationNodesMainPicture = $xml->xpath('/cpl:CompositionPlaylist/cpl:ReelList/cpl:Reel/cpl:AssetList/cpl:MainPicture/cpl:Duration');
        if (!empty($durationNodesMainPicture)) {
            $duration_main_picture = trim((string)$durationNodesMainPicture[0]);
            if ($duration_main_picture !== '' && is_numeric($duration_main_picture)) {
                $total_duration += (int) $duration_main_picture;
            }
        }

        $durationNodesMainSound = $xml->xpath('/cpl:CompositionPlaylist/cpl:ReelList/cpl:Reel/cpl:AssetList/cpl:MainSound/cpl:Duration');
        if (!empty($durationNodesMainSound)) {
            $duration_main_sound = trim((string)$durationNodesMainSound[0]);
            if ($duration_main_sound !== '' && is_numeric($duration_main_sound)) {
                $total_duration += (int) $duration_main_sound;
            }
        }

        return [
            'uuid'           => $uuid,
            'name'           => $name,
            'total_duration' => $total_duration,
        ];
    }

}
