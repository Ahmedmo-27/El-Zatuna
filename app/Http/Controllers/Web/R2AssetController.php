<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

/**
 * Serves Course-Assets (thumbnail, cover, icon, demo video) from R2.
 * Used when R2_PUBLIC_URL is not set (private bucket) so the app proxies the file.
 */
class R2AssetController extends Controller
{
    /**
     * Serve an R2 asset by path (e.g. Course-Assets/123/thumbnail_1771781356.png).
     * Only paths under Course-Assets/ are allowed.
     */
    public function show(string $path)
    {
        $path = trim($path, '/');
        if (!str_starts_with($path, 'Course-Assets/')) {
            abort(404);
        }

        try {
            $disk = Storage::disk('r2');
            if (!$disk->exists($path)) {
                abort(404);
            }

            $mime = $this->getMimeFromPath($path);
            $contents = $disk->get($path);

            return response($contents, 200, [
                'Content-Type' => $mime,
                'Content-Length' => strlen($contents),
                'Cache-Control' => 'public, max-age=31536000',
                'Accept-Ranges' => 'bytes',
            ]);
        } catch (\Exception $e) {
            \Log::warning('R2 asset proxy error', ['path' => $path, 'error' => $e->getMessage()]);
            abort(404);
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
            'webm' => 'video/webm',
            'mov' => 'video/quicktime',
        ];
        return $map[$ext] ?? 'application/octet-stream';
    }
}
