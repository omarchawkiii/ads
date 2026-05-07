<?php

namespace App\Jobs;

use App\Models\DcpCreative;
use FilesystemIterator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class GenerateDcpPreview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800;

    public function __construct(public DcpCreative $dcp) {}

    public function handle(): void
    {
        $dcp = $this->dcp->fresh();
        if (!$dcp || !$dcp->extract_path || !is_dir($dcp->extract_path)) {
            $dcp?->update(['preview_status' => 'failed']);
            Log::error("GenerateDcpPreview: extract_path missing or not a directory", ['dcp_id' => $dcp?->id]);
            return;
        }

        $dcp->update(['preview_status' => 'processing']);

        $ffmpeg = $this->ffmpegBinary();
        if (!$ffmpeg) {
            $dcp->update(['preview_status' => 'failed']);
            Log::error("GenerateDcpPreview: FFmpeg not found", ['dcp_id' => $dcp->id]);
            return;
        }

        $files = $this->scanMxfFiles($dcp->extract_path);

        if (empty($files['video'])) {
            $dcp->update(['preview_status' => 'failed']);
            Log::error("GenerateDcpPreview: no video MXF found", ['dcp_id' => $dcp->id, 'files' => $files]);
            return;
        }

        $previewDir = storage_path('app/public/dcp_previews');
        if (!is_dir($previewDir)) {
            @mkdir($previewDir, 0775, true);
        }

        $previewFile = "{$previewDir}/{$dcp->id}.mp4";

        $inputs   = '-i ' . escapeshellarg($files['video']);
        $audioMap = '';
        if (!empty($files['audio'])) {
            $inputs   .= ' -i ' . escapeshellarg($files['audio']);
            $audioMap  = '-map 0:v:0 -map 1:a:0 -c:a aac -b:a 128k ';
        } else {
            $audioMap = '-an ';
        }

        $cmd = sprintf(
            '%s -y %s %s-c:v libx264 -preset fast -crf 28 -vf "scale=1280:-2" %s',
            $ffmpeg,
            $inputs,
            $audioMap,
            escapeshellarg($previewFile)
        );

        Log::info("GenerateDcpPreview: running FFmpeg", ['cmd' => $cmd, 'dcp_id' => $dcp->id]);

        [$output, $code] = $this->runCommand($cmd);

        if ($code === 0 && file_exists($previewFile) && filesize($previewFile) > 0) {
            $dcp->update([
                'preview_path'   => 'dcp_previews/' . $dcp->id . '.mp4',
                'preview_status' => 'ready',
            ]);
            Log::info("GenerateDcpPreview: success", ['dcp_id' => $dcp->id]);
        } else {
            $dcp->update(['preview_status' => 'failed']);
            Log::error("GenerateDcpPreview: FFmpeg failed", [
                'dcp_id' => $dcp->id,
                'code'   => $code,
                'output' => implode("\n", array_slice($output, -20)),
            ]);
        }
    }

    /**
     * Run a shell command using proc_open (works even when exec/shell_exec are disabled).
     * Returns [output_lines[], exit_code].
     */
    private function runCommand(string $cmd): array
    {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = \proc_open($cmd, $descriptors, $pipes);

        if (!is_resource($process)) {
            return [['proc_open failed'], -1];
        }

        fclose($pipes[0]);

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $code   = \proc_close($process);
        $output = array_merge(
            $stdout ? explode("\n", rtrim($stdout)) : [],
            $stderr ? explode("\n", rtrim($stderr)) : []
        );

        return [$output, $code];
    }

    private function scanMxfFiles(string $dir): array
    {
        $result = ['video' => null, 'audio' => null, 'other' => []];

        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($it as $file) {
            if (!$file->isFile() || strtolower($file->getExtension()) !== 'mxf') {
                continue;
            }

            $name = strtolower($file->getFilename());

            if (str_starts_with($name, 'j2c_') || str_starts_with($name, 'j2k_')) {
                $result['video'] = $file->getPathname();
            } elseif (str_starts_with($name, 'pcm_')) {
                $result['audio'] = $file->getPathname();
            } else {
                $result['other'][] = $file->getPathname();
            }
        }

        if (!$result['video'] && !empty($result['other'])) {
            $result['video'] = array_shift($result['other']);
        }

        return $result;
    }

    private function ffmpegBinary(): ?string
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidates = [
                'ffmpeg',
                'C:\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\laragon\\bin\\ffmpeg\\ffmpeg.exe',
            ];
        } else {
            $home = $_SERVER['HOME'] ?? getenv('HOME') ?? '';
            $candidates = array_filter([
                'ffmpeg',
                '/usr/bin/ffmpeg',
                '/usr/local/bin/ffmpeg',
                $home !== '' ? $home . '/bin/ffmpeg' : null,
            ]);
        }

        foreach ($candidates as $bin) {
            if (!is_executable($bin) && $bin !== 'ffmpeg') {
                continue;
            }
            [, $code] = $this->runCommand(escapeshellarg($bin) . ' -version 2>&1');
            if ($code === 0) {
                return $bin;
            }
        }

        return null;
    }
}
