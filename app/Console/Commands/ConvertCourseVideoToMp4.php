<?php

namespace App\Console\Commands;

use App\Models\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

/**
 * Convert an R2 course video (MKV/AVI/WMV) to MP4 for reliable browser playback.
 * Downloads the file from R2, runs FFmpeg, uploads the MP4 to the same path with .mp4 extension.
 * Playback will then use the MP4 automatically (fixes black screen with MKV).
 */
class ConvertCourseVideoToMp4 extends Command
{
    protected $signature = 'course:convert-video-to-mp4 {file_id : The file ID from the files table}';

    protected $description = 'Convert an R2 course video (MKV/AVI/WMV) to MP4 for browser playback (requires FFmpeg)';

    public function handle(): int
    {
        $fileId = (int) $this->argument('file_id');

        $file = File::find($fileId);
        if (!$file) {
            $this->error("File with ID {$fileId} not found.");
            return 1;
        }

        if ($file->storage !== 'r2' || !$file->isVideo()) {
            $this->error('File is not an R2 video. Only R2 video files can be converted.');
            return 1;
        }

        $r2Path = $this->extractR2Path($file->file);
        if (empty($r2Path)) {
            $this->error('Could not extract R2 path from file.');
            return 1;
        }

        $ext = strtolower(pathinfo($r2Path, PATHINFO_EXTENSION));
        $convertible = ['mkv', 'avi', 'wmv', 'flv'];
        if (!in_array($ext, $convertible, true)) {
            $this->warn("File is already {$ext}. For best browser support, convert to MP4. Converting anyway if you want a separate MP4 copy.");
        }

        $dir = pathinfo($r2Path, PATHINFO_DIRNAME);
        $base = pathinfo($r2Path, PATHINFO_FILENAME);
        $mp4Path = $dir . '/' . $base . '.mp4';

        if (Storage::disk('r2')->exists($mp4Path)) {
            if (!$this->confirm("MP4 already exists at R2. Overwrite?")) {
                $this->info('Skipped.');
                return 0;
            }
        }

        $this->info("Downloading from R2: {$r2Path}");
        $tempDir = storage_path('app/temp_video_convert_' . $fileId);
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempInput = $tempDir . '/input.' . $ext;
        $tempOutput = $tempDir . '/output.mp4';

        try {
            $contents = Storage::disk('r2')->get($r2Path);
            if ($contents === null) {
                $this->error('Failed to download file from R2.');
                $this->cleanup($tempDir);
                return 1;
            }
            file_put_contents($tempInput, $contents);
            $this->info('Downloaded. Converting with FFmpeg...');

            // FFmpeg: H.264 + AAC, faststart for web, yuv420p for compatibility
            $result = Process::timeout(3600)->run([
                'ffmpeg',
                '-i', $tempInput,
                '-c:v', 'libx264',
                '-profile:v', 'high',
                '-level', '4.0',
                '-pix_fmt', 'yuv420p',
                '-c:a', 'aac',
                '-movflags', '+faststart',
                '-y',
                $tempOutput,
            ]);

            if (!$result->successful()) {
                $this->error('FFmpeg failed.');
                $this->line($result->errorOutput());
                $this->cleanup($tempDir);
                return 1;
            }

            if (!is_file($tempOutput) || filesize($tempOutput) === 0) {
                $this->error('FFmpeg did not produce output.');
                $this->cleanup($tempDir);
                return 1;
            }

            $this->info('Uploading MP4 to R2: ' . $mp4Path);
            $stream = fopen($tempOutput, 'r');
            if ($stream === false) {
                throw new \RuntimeException('Failed to open converted MP4 for upload');
            }
            try {
                Storage::disk('r2')->writeStream($mp4Path, $stream);
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }

            $this->info('Done. Playback will now use the MP4 (no more black screen).');
        } catch (\Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->cleanup($tempDir);
            return 1;
        }

        $this->cleanup($tempDir);
        return 0;
    }

    private function extractR2Path($fileField): ?string
    {
        if (empty($fileField)) {
            return null;
        }
        if (!filter_var($fileField, FILTER_VALIDATE_URL)) {
            return ltrim($fileField, '/');
        }
        $parsedUrl = parse_url($fileField);
        if (!isset($parsedUrl['path'])) {
            return null;
        }
        $path = ltrim($parsedUrl['path'], '/');
        $bucket = config('r2.bucket');
        if (!empty($bucket) && strpos($path, $bucket . '/') === 0) {
            $path = substr($path, strlen($bucket) + 1);
        }
        return $path;
    }

    private function cleanup(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }
        foreach (glob($dir . '/*') ?: [] as $f) {
            @unlink($f);
        }
        @rmdir($dir);
    }
}
