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

        // DCI DCP has separate video (j2c_*.mxf) and audio (pcm_*.mxf) files
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

        // Build FFmpeg command: video + optional audio
        $inputs = '-i ' . escapeshellarg($files['video']);
        $audioMap = '';
        if (!empty($files['audio'])) {
            $inputs   .= ' -i ' . escapeshellarg($files['audio']);
            $audioMap  = '-map 0:v:0 -map 1:a:0 -c:a aac -b:a 128k ';
        } else {
            $audioMap = '-an '; // no audio
        }

        $cmd = sprintf(
            '%s -y %s %s-c:v libx264 -preset fast -crf 28 -vf "scale=1280:-2" %s',
            $ffmpeg,
            $inputs,
            $audioMap,
            escapeshellarg($previewFile)
        );

        Log::info("GenerateDcpPreview: running FFmpeg", ['cmd' => $cmd, 'dcp_id' => $dcp->id]);

        exec($cmd . ' 2>&1', $output, $code);

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
                'output' => implode("\n", array_slice($output, -20)), // last 20 lines
            ]);
        }
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

            // DCI DCP naming: j2c_* or j2k_* = JPEG2000 video; pcm_* = PCM audio
            if (str_starts_with($name, 'j2c_') || str_starts_with($name, 'j2k_')) {
                $result['video'] = $file->getPathname();
            } elseif (str_starts_with($name, 'pcm_')) {
                $result['audio'] = $file->getPathname();
            } else {
                $result['other'][] = $file->getPathname();
            }
        }

        // Fallback: if no j2c/j2k found, take the first MXF as video
        if (!$result['video'] && !empty($result['other'])) {
            $result['video'] = array_shift($result['other']);
        }

        return $result;
    }

    private function ffmpegBinary(): ?string
    {
        $candidates = ['ffmpeg'];

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $candidates = [
                'ffmpeg',
                'C:\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\Program Files\\ffmpeg\\bin\\ffmpeg.exe',
                'C:\\laragon\\bin\\ffmpeg\\ffmpeg.exe',
            ];
        } else {
            $candidates = ['ffmpeg', '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'];
        }

        foreach ($candidates as $bin) {
            $out  = [];
            $code = -1;
            @exec(escapeshellarg($bin) . ' -version 2>&1', $out, $code);
            // Only trust exit code 0 — the error message itself contains "ffmpeg" on Windows
            if ($code === 0) {
                return $bin;
            }
        }

        return null;
    }
}
