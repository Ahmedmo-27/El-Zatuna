<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;

/**
 * Serves Course-Assets and Profile-Assets from R2 (thumbnails, covers, profile images, demo/profile videos).
 * Used when R2_PUBLIC_URL is not set (private bucket) so the app proxies the file.
 * Supports Range requests for video seeking and streams large files to avoid memory issues.
 */
class R2AssetController extends Controller
{
    /** In-memory response threshold (bytes); above this we stream. */
    private const STREAM_THRESHOLD = 2 * 1024 * 1024; // 2MB

    /**
     * Serve an R2 asset by path (e.g. Course-Assets/123/thumbnail_1771781356.png or Profile-Assets/1/avatar_123.jpg).
     * Only paths under Course-Assets/ or Profile-Assets/ are allowed.
     */
    public function show(string $path)
    {
        $path = trim($path, '/');
        if (!str_starts_with($path, 'Course-Assets/') && !str_starts_with($path, 'Profile-Assets/')) {
            abort(404);
        }

        try {
            $disk = Storage::disk('r2');
            if (!$disk->exists($path)) {
                abort(404);
            }

            $size = $disk->size($path);
            $mime = $this->getMimeFromPath($path);
            $headers = [
                'Content-Type' => $mime,
                'Cache-Control' => 'public, max-age=31536000',
                'Accept-Ranges' => 'bytes',
                'X-Content-Type-Options' => 'nosniff',
            ];

            // Range request (video seeking, etc.)
            if (isset($_SERVER['HTTP_RANGE']) && preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $matches)) {
                $start = (int) $matches[1];
                $end = $matches[2] !== '' ? (int) $matches[2] : $size - 1;
                if ($start >= $size) {
                    $headers['Content-Range'] = "bytes */$size";
                    return response('', 416, $headers);
                }
                $end = min($end, $size - 1);
                if ($end < $start) {
                    $end = $start;
                }
                $length = $end - $start + 1;
                $headers['Content-Range'] = "bytes $start-$end/$size";
                $headers['Content-Length'] = $length;
                $stream = $this->streamR2Range($path, $start, $length);
                if ($stream !== null) {
                    return response()->stream($stream, 206, $headers);
                }
                \Log::warning('R2 asset proxy: range stream unavailable, falling back to full stream', ['path' => $path]);
            }

            // Small file: return in memory
            if ($size <= self::STREAM_THRESHOLD) {
                $contents = $disk->get($path);
                $headers['Content-Length'] = strlen($contents);
                return response($contents, 200, $headers);
            }

            // Large file: stream without loading into memory
            $headers['Content-Length'] = $size;
            $stream = function () use ($disk, $path) {
                $stream = $disk->readStream($path);
                if ($stream && is_resource($stream)) {
                    fpassthru($stream);
                    fclose($stream);
                }
            };
            return response()->stream($stream, 200, $headers);
        } catch (\Exception $e) {
            \Log::warning('R2 asset proxy error', ['path' => $path, 'error' => $e->getMessage()]);
            abort(404);
        }
    }

    /**
     * Stream a byte range from R2 using S3 GetObject Range (efficient for video seeking).
     */
    private function streamR2Range(string $path, int $start, int $length): ?callable
    {
        try {
            $adapter = Storage::disk('r2')->getAdapter();
            if (!method_exists($adapter, 'getClient')) {
                return null;
            }
            $client = $adapter->getClient();
            if (!$client instanceof S3Client) {
                return null;
            }
            $bucket = config('filesystems.disks.r2.bucket') ?: config('r2.bucket');
            if (empty($bucket)) {
                return null;
            }
            return function () use ($client, $bucket, $path, $start, $length) {
                $result = $client->getObject([
                    'Bucket' => $bucket,
                    'Key' => $path,
                    'Range' => "bytes=$start-" . ($start + $length - 1),
                ]);
                $body = $result['Body'];
                if (is_resource($body)) {
                    fpassthru($body);
                    fclose($body);
                } else {
                    echo $body;
                }
            };
        } catch (\Throwable $e) {
            \Log::warning('R2 asset range stream error', ['path' => $path, 'error' => $e->getMessage()]);
            return null;
        }
    }

    private function getMimeFromPath(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'mp4' => 'video/mp4',
            'm4v' => 'video/x-m4v',
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'avi' => 'video/x-msvideo',
            'wmv' => 'video/x-ms-wmv',
            'flv' => 'video/x-flv',
        ];
        return $map[$ext] ?? 'application/octet-stream';
    }
}
