<?php

namespace App\Http\Controllers;

use App\Models\CompaignCategory;
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
        $categories = CompaignCategory::orderBy('name')->get();
        return view('advertiser.dcp_creatives.index',compact('categories'));
    }

    public function show(Request $request)
    {
        $dcp_creative = DcpCreative::findOrFail($request->id);
        return Response()->json(compact('dcp_creative'));
    }

    public function get()
    {
        $dcp_creatives = DcpCreative::with('compaignCategory')->orderBy('name', 'asc')->get();
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
        try {
            $dcp_creative = DcpCreative::findOrFail($request->id);
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
            'file_size' => 'required|numeric', // not used on PHP side, informational only
        ]);

        // Security: accept only .zip, .tar, .7z
        $original = $request->string('file_name')->toString();
        if (!preg_match('/\.(zip|tar|7z)$/i', $original)) {
            return response()->json(['ok' => false, 'message' => 'Only .zip, .tar, or .7z files are accepted.'], 422);
        }

        $uploadId = (string) Str::uuid();
        $tmpDir   = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0775, true);
        }

        return response()->json([
            'ok'         => true,
            'upload_id'  => $uploadId,
            'chunk_size' => self::CHUNK_SIZE, // front-end can align with this
        ]);
    }

    public function chunk(Request $request)
    {
        // Each request contains one "chunk" + meta
        $request->validate([
            'upload_id' => 'required|string',
            'index'     => 'required|integer|min:0',
            'total'     => 'required|integer|min:1',
            'file_name' => 'required|string',
            'chunk'     => 'required|file|max:51200', // 50 MB safety max (we send 10 MB)
        ], [], [
            'chunk' => 'chunk',
        ]);

        $uploadId = $request->input('upload_id');
        $index    = (int) $request->input('index');
        $total    = (int) $request->input('total');
        $file     = $request->file('chunk');

        $tmpDir = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            return response()->json(['ok' => false, 'message' => 'Unknown upload (missing init).'], 422);
        }

        // Save fragment as .partN file
        $partPath = "{$tmpDir}/part_{$index}";
        $file->move($tmpDir, "part_{$index}");

        // Optional check: size > 0
        if (!is_file($partPath) || filesize($partPath) <= 0) {
            return response()->json(['ok' => false, 'message' => "Chunk {$index} is empty or invalid."], 422);
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
            'compaign_category_id' => ['required', 'exists:compaign_categories,id'],
        ]);

        $uploadId  = $request->input('upload_id');
        $total     = (int) $request->input('total');
        $original  = $request->string('file_name')->toString();

        if (!preg_match('/\.(zip|tar|7z)$/i', $original)) {
            return response()->json(['ok' => false, 'message' => 'Only .zip, .tar, or .7z files are accepted.'], 422);
        }

        $tmpDir = storage_path("app/uploads/tmp/{$uploadId}");
        if (!is_dir($tmpDir)) {
            return response()->json(['ok' => false, 'message' => 'Unknown upload.'], 422);
        }

        // Verify all fragments exist
        for ($i = 0; $i < $total; $i++) {
            if (!is_file("{$tmpDir}/part_{$i}")) {
                return response()->json(['ok' => false, 'message' => "Missing chunk: {$i}"], 422);
            }
        }

        // Final name and folder
        $safeName = substr(preg_replace('/[^A-Za-z0-9._-]/', '_', $original), 0, 180);
        $finalDir = storage_path('app/uploads/final');
        if (!is_dir($finalDir)) {
            @mkdir($finalDir, 0775, true);
        }

        $finalPath = "{$finalDir}/{$uploadId}_{$safeName}";

        // Streamed assembly (supports > 2 GB)
        $out = fopen($finalPath, 'ab');
        if (!$out) {
            return response()->json(['ok' => false, 'message' => 'Failed to create the final file.'], 500);
        }

        for ($i = 0; $i < $total; $i++) {
            $partPath = "{$tmpDir}/part_{$i}";
            $in = fopen($partPath, 'rb');
            if (!$in) {
                fclose($out);
                return response()->json(['ok' => false, 'message' => "Failed to read part_{$i}."], 500);
            }
            stream_copy_to_stream($in, $out);
            fclose($in);
        }
        fclose($out);

        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $warning = null;

        // (optional) basic signature checks
        $fh = @fopen($finalPath, 'rb');
        if ($fh) {
            if ($ext === 'zip') {
                // ZIP may start with PK\x03\x04 (file), PK\x05\x06 (empty archive), PK\x07\x08 (spanned)
                $magic = fread($fh, 4);
                if (!in_array($magic, ["PK\x03\x04", "PK\x05\x06", "PK\x07\x08"], true)) {
                    $warning = 'The assembled file does not appear to be a valid ZIP (signature).';
                }
            } elseif ($ext === '7z') {
                // 7z signature: 37 7A BC AF 27 1C
                $magic = fread($fh, 6);
                if ($magic !== "\x37\x7A\xBC\xAF\x27\x1C") {
                    $warning = 'The assembled file does not appear to be a valid 7z (signature).';
                }
            } elseif ($ext === 'tar') {
                // TAR: "ustar" at offset 257 (POSIX ustar). Not all TARs have it; warn if absent.
                fseek($fh, 257);
                $ustar = fread($fh, 5);
                if ($ustar !== 'ustar') {
                    $warning = 'The assembled file does not contain the POSIX TAR marker (ustar).';
                }
            }
            fclose($fh);
        }

        // safer extra ZIP check (only if ZIP & $magic set)
        if ($ext === 'zip' && isset($magic) && $magic !== "PK\x03\x04") {
            $warning = 'Warning: the assembled file does not appear to be a valid ZIP (magic bytes).';
        }

        // Cleanup .part files
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
                'ok'       => true, // upload ok, but extraction failed
                'message'  => 'Upload finished but extraction failed.',
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
                'message'  => 'Upload OK. No XML file with CompositionPlaylist as root was found.',
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
            'uuid' => $meta['uuid']
        ], [
            'uuid'                => $meta['uuid'],
            'name'                => $meta['name'],
            'duration'            => $meta['total_duration'],
            'path'                => $finalPath,
            'compaign_category_id' => $request->compaign_category_id,
        ]);

        return response()->json([
            'ok'       => true,
            'message'  => 'Upload completed, archive extracted, CPL found and parsed.',
            'final'    => [
                'path'         => $finalPath,
                'filename'     => basename($finalPath),
                'size'         => filesize($finalPath),
                'archive_type' => $ext,
            ],
            'warning'  => $warning ?? null,
            'creative' => ['id' => $creative->id, 'path' => $creative->path],
            'cpl_file' => [
                'path' => $first['path'], // path of the parsed CPL XML
            ],
            'cpl_meta' => $meta, // UUID, name, durations, edit rate…
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
                        return ['ok' => false, 'message' => 'ZIP extraction failed'];
                    }
                    $zip->close();
                    return ['ok' => true];
                }
                return ['ok' => false, 'message' => 'Unable to open ZIP'];
            }

            if ($ext === 'tar') {
                // TAR (for tar.gz/tgz, add a gunzip step before if needed)
                $phar = new PharData($archivePath);
                $phar->extractTo($destDir, null, true); // overwrite = true
                return ['ok' => true];
            }

            if ($ext === '7z') {
                // 7z requires "7z" CLI (p7zip). We call it via shell.
                $bin = trim(shell_exec('command -v 7z') ?? '');
                if ($bin === '') {
                    return ['ok' => false, 'message' => '7z binary not found on server (p7zip required).'];
                }
                $cmd = $bin . ' x ' . escapeshellarg($archivePath) . ' -o' . escapeshellarg($destDir) . ' -y';
                exec($cmd, $out, $code);
                if ($code !== 0) {
                    return ['ok' => false, 'message' => '7z extraction failed (code ' . $code . ').'];
                }
                return ['ok' => true];
            }

            return ['ok' => false, 'message' => 'Archive extension not supported for extraction.'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => 'Extraction error: ' . $e->getMessage()];
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
                // Stop at the first CPL found
                return ['ok' => true, 'path' => $path, 'xml' => $xml];
            }
        }

        return ['ok' => false, 'message' => 'No CPL XML found'];
    }

    private function parseCpl(\SimpleXMLElement $xml): array
    {
        // Default namespace (required for XPath with CPL)
        $namespaces = $xml->getDocNamespaces(true);
        $defaultNs  = $namespaces[''] ?? null; // default namespace
        $xml->registerXPathNamespace('cpl', $defaultNs ?: '');

        // Id and Name
        $idNode   = $xml->xpath('/cpl:CompositionPlaylist/cpl:Id');
        $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:ContentTitleText');
        if (empty($nameNode)) {
            $nameNode = $xml->xpath('/cpl:CompositionPlaylist/cpl:AnnotationText');
        }

        $idRaw = isset($idNode[0]) ? trim((string)$idNode[0]) : null;
        $name  = isset($nameNode[0]) ? trim((string)$nameNode[0]) : null;

        // Extract UUID from "urn:uuid:xxxxxxxx-xxxx-...." (or return raw if no match)
        $uuid = null;
        if ($idRaw && preg_match('~([0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})~', $idRaw, $m)) {
            $uuid = strtolower($m[1]);
        } else {
            $uuid = $idRaw;
        }

        // Sum of durations (first MainPicture + first MainSound)
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
