<?php

namespace App\Helpers;

use App\Services\R2StorageService;
use Illuminate\Support\Facades\Storage;

class R2Helper
{
    /**
     * Get the full R2 URL for a file path.
     * For Course-Assets, when R2_PUBLIC_URL is empty we return the Laravel proxy URL.
     *
     * @param string $path
     * @return string|null
     */
    public static function getUrl(string $path): ?string
    {
        try {
            $path = ltrim($path, '/');
            $publicUrl = config('r2.public_url');
            if (!empty($publicUrl)) {
                return rtrim($publicUrl, '/') . '/' . self::encodePathSegments($path);
            }
            // Private bucket or no public URL: use Laravel proxy for Course-Assets and Profile-Assets
            if (str_starts_with($path, 'Course-Assets/') || str_starts_with($path, 'Profile-Assets/')) {
                return url('/r2-asset/' . self::encodePathSegments($path));
            }
            return Storage::disk('r2')->url($path);
        } catch (\Exception $e) {
            \Log::error('R2Helper getUrl error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Encode each path segment for use in URLs (handles filenames with spaces and special chars).
     *
     * @param string $path Path with forward slashes (e.g. Course-Assets/123/Lecture IB GUC - Endocrine I.mkv)
     * @return string Path with each segment rawurlencoded
     */
    public static function encodePathSegments(string $path): string
    {
        $segments = explode('/', $path);
        return implode('/', array_map('rawurlencode', $segments));
    }
    
    /**
     * Build the R2 path for a webinar file
     * 
     * @param int $webinarId
     * @param int|null $lessonId
     * @param string $fileName
     * @return string
     */
    public static function buildFilePath(int $webinarId, ?int $lessonId, string $fileName): string
    {
        $path = 'Courses/' . $webinarId;
        
        if ($lessonId !== null) {
            $path .= '/' . $lessonId;
        }
        
        return $path . '/' . $fileName;
    }
    
    /**
     * Generate a signed URL for private content (future implementation)
     * 
     * @param string $path
     * @param int $expirationMinutes
     * @return string|null
     */
    public static function getSignedUrl(string $path, int $expirationMinutes = 60): ?string
    {
        try {
            $expiration = now()->addMinutes($expirationMinutes);
            return Storage::disk('r2')->temporaryUrl($path, $expiration);
        } catch (\Exception $e) {
            \Log::error('R2Helper getSignedUrl error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Resolve a content asset path (thumbnail, cover, demo video) to full URL.
     * Use when you have a raw path from DB (e.g. from toArray()) and need the playable/display URL.
     *
     * @param string|null $path
     * @return string|null
     */
    public static function resolveContentAssetUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }
        if (str_starts_with($path, 'Course-Assets/') || str_starts_with($path, 'Profile-Assets/')) {
            return self::getUrl($path) ?: $path;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return url($path);
    }

    /**
     * Check if R2 is properly configured
     *
     * @return bool
     */
    public static function isConfigured(): bool
    {
        return !empty(config('r2.access_key_id')) 
            && !empty(config('r2.secret_access_key')) 
            && !empty(config('r2.bucket'));
    }
    
    /**
     * Get storage statistics for a webinar
     * 
     * @param int $webinarId
     * @return array
     */
    public static function getWebinarStorageStats(int $webinarId): array
    {
        try {
            $r2Service = new R2StorageService();
            $files = $r2Service->listFiles($webinarId);
            
            $totalSize = 0;
            $fileCount = count($files);
            
            foreach ($files as $file) {
                $size = $r2Service->getFileSize($file);
                if ($size !== null) {
                    $totalSize += $size;
                }
            }
            
            return [
                'file_count' => $fileCount,
                'total_size_bytes' => $totalSize,
                'total_size_mb' => round($totalSize / 1024 / 1024, 2),
                'total_size_gb' => round($totalSize / 1024 / 1024 / 1024, 2),
            ];
        } catch (\Exception $e) {
            \Log::error('R2Helper getWebinarStorageStats error: ' . $e->getMessage());
            return [
                'file_count' => 0,
                'total_size_bytes' => 0,
                'total_size_mb' => 0,
                'total_size_gb' => 0,
            ];
        }
    }
    
    /**
     * Delete all files for a webinar
     * 
     * @param int $webinarId
     * @return bool
     */
    public static function deleteWebinarFiles(int $webinarId): bool
    {
        try {
            $r2Service = new R2StorageService();
            $files = $r2Service->listFiles($webinarId);
            
            foreach ($files as $file) {
                $r2Service->deleteFile($file);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('R2Helper deleteWebinarFiles error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get the file extension from a path
     * 
     * @param string $path
     * @return string
     */
    public static function getFileExtension(string $path): string
    {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }
    
    /**
     * Determine file type from extension
     * 
     * @param string $extension
     * @return string
     */
    public static function getFileType(string $extension): string
    {
        $videoExtensions = ['mp4', 'mkv', 'avi', 'mov', 'wmv', 'webm', 'flv', 'm4v'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'aac'];
        $documentExtensions = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        $archiveExtensions = ['zip', 'rar', 'tar', 'gz', '7z'];
        
        if (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $audioExtensions)) {
            return 'sound';
        } elseif (in_array($extension, $documentExtensions)) {
            return 'document';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        } elseif (in_array($extension, $archiveExtensions)) {
            return 'archive';
        }
        
        return 'document'; // Default
    }
    
    /**
     * Format file size to human readable format
     * 
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public static function formatFileSize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
