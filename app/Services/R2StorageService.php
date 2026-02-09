<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;

class R2StorageService
{
    /**
     * Upload a file to Cloudflare R2
     * 
     * @param UploadedFile $file
     * @param int $webinarId
     * @param int|null $lessonId
     * @param string|null $fileType (video or document)
     * @return array ['status' => bool, 'path' => string|null, 'url' => string|null]
     */
    public function uploadFile(UploadedFile $file, int $webinarId, ?int $lessonId = null, ?string $fileType = 'video'): array
    {
        try {
            $storage = Storage::disk('r2');
            
            // Build the path according to the structure: {webinar_id}/{lesson_id}/{file}
            $path = $this->buildPath($webinarId, $lessonId, $fileType);
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fullPath = $path . '/' . $fileName;
            
            // Upload to R2 using stream for large files
            $stream = fopen($file->getRealPath(), 'r');
            $uploaded = $storage->put($fullPath, $stream, 'public');
            
            if (is_resource($stream)) {
                fclose($stream);
            }
            
            if ($uploaded) {
                $url = $storage->url($fullPath);
                
                return [
                    'status' => true,
                    'path' => $fullPath,
                    'url' => $url,
                ];
            }
            
            return [
                'status' => false,
                'path' => null,
                'url' => null,
            ];
            
        } catch (Exception $e) {
            \Log::error('R2 Upload Error: ' . $e->getMessage(), [
                'webinar_id' => $webinarId,
                'lesson_id' => $lessonId,
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'status' => false,
                'path' => null,
                'url' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Build the storage path according to the directory structure
     * 
     * @param int $webinarId
     * @param int|null $lessonId
     * @param string $fileType
     * @return string
     */
    protected function buildPath(int $webinarId, ?int $lessonId = null, string $fileType = 'video'): string
    {
        $path = 'Courses/' . $webinarId;
        
        if ($lessonId !== null) {
            $path .= '/' . $lessonId;
        }
        
        // Optionally separate videos and documents
        // $path .= '/' . $fileType;
        
        return $path;
    }
    
    /**
     * Get the public URL for a file
     * 
     * @param string $path
     * @return string|null
     */
    public function getUrl(string $path): ?string
    {
        try {
            // Build URL using R2 public URL
            $publicUrl = config('r2.public_url');
            return rtrim($publicUrl, '/') . '/' . ltrim($path, '/');
        } catch (Exception $e) {
            \Log::error('R2 Get URL Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Delete a file from R2
     * 
     * @param string $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        try {
            return Storage::disk('r2')->delete($path);
        } catch (Exception $e) {
            \Log::error('R2 Delete Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if a file exists in R2
     * 
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        try {
            return Storage::disk('r2')->exists($path);
        } catch (Exception $e) {
            \Log::error('R2 File Exists Check Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get file size
     * 
     * @param string $path
     * @return int|null Size in bytes
     */
    public function getFileSize(string $path): ?int
    {
        try {
            return Storage::disk('r2')->size($path);
        } catch (Exception $e) {
            \Log::error('R2 Get File Size Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * List files in a directory
     * 
     * @param int $webinarId
     * @param int|null $lessonId
     * @return array
     */
    public function listFiles(int $webinarId, ?int $lessonId = null): array
    {
        try {
            $path = $this->buildPath($webinarId, $lessonId);
            return Storage::disk('r2')->files($path);
        } catch (Exception $e) {
            \Log::error('R2 List Files Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Upload file with custom path
     * 
     * @param UploadedFile $file
     * @param string $customPath
     * @return array
     */
    public function uploadFileWithCustomPath(UploadedFile $file, string $customPath): array
    {
        try {
            $storage = Storage::disk('r2');
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fullPath = $customPath . '/' . $fileName;
            
            $uploaded = $storage->put($fullPath, file_get_contents($file->getRealPath()), 'public');
            
            if ($uploaded) {
                $url = $storage->url($fullPath);
                
                return [
                    'status' => true,
                    'path' => $fullPath,
                    'url' => $url,
                ];
            }
            
            return [
                'status' => false,
                'path' => null,
                'url' => null,
            ];
            
        } catch (Exception $e) {
            \Log::error('R2 Upload Error (Custom Path): ' . $e->getMessage());
            
            return [
                'status' => false,
                'path' => null,
                'url' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
