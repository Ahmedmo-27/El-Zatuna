<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Exception;
use Aws\S3\S3Client;
use Aws\S3\MultipartUploader;
use Aws\Exception\AwsException;

class R2StorageService
{
    /**
     * Upload a file to Cloudflare R2
     * 
     * Path structure: Courses/{course_id}/{section_id}/{timestamp}_{filename}
     * Example: Courses/133/20/1770673760_problemsolvingsection7fileinout.mp4
     * 
     * @param UploadedFile $file
     * @param int $courseId The course/webinar ID
     * @param int|null $sectionId The section/chapter ID (chapter_id from database)
     * @param string|null $fileType (video or document)
     * @return array ['status' => bool, 'path' => string|null, 'url' => string|null]
     */
    public function uploadFile(UploadedFile $file, int $courseId, ?int $sectionId = null, ?string $fileType = 'video'): array
    {
        try {
            // Enable exception throwing to get actual error messages
            config(['filesystems.disks.r2.throw' => true]);
            $storage = Storage::disk('r2');
            
            // Build the path according to the structure: Courses/{course_id}/{section_id}/
            $path = $this->buildPath($courseId, $sectionId, $fileType);
            
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fullPath = $path . '/' . $fileName;
            
            \Log::info('R2 Upload Starting', [
                'full_path' => $fullPath,
                'file_size' => $file->getSize(),
                'course_id' => $courseId,
                'section_id' => $sectionId,
                'bucket' => config('filesystems.disks.r2.bucket'),
                'endpoint' => config('filesystems.disks.r2.endpoint'),
            ]);
            
            // Upload to R2 - try multiple methods for compatibility
            $uploaded = false;
            $uploadError = null;
            
            try {
                // Enable exception throwing to get actual error messages
                $originalThrow = config('filesystems.disks.r2.throw', false);
                config(['filesystems.disks.r2.throw' => true]);
                
                $fileSize = $file->getSize();
                $filePath = $file->getRealPath();
                
                // For large files (>100MB), use multipart upload
                // For smaller files, use regular put() for better performance
                if ($fileSize > 100 * 1024 * 1024) { // 100MB threshold
                    \Log::info('R2 Upload: Using multipart upload for large file', [
                        'file_size' => $fileSize,
                        'file_size_mb' => round($fileSize / 1024 / 1024, 2),
                    ]);
                    
                    // Create S3 client directly for multipart upload (same config as R2StorageServiceProvider)
                    $s3Client = new S3Client([
                        'credentials' => [
                            'key' => config('filesystems.disks.r2.key'),
                            'secret' => config('filesystems.disks.r2.secret'),
                        ],
                        'region' => config('filesystems.disks.r2.region', 'auto'),
                        'version' => 'latest',
                        'bucket_endpoint' => false,
                        'use_path_style_endpoint' => true,
                        'endpoint' => config('filesystems.disks.r2.endpoint'),
                        'http' => [
                            'verify' => 'C:\Users\Ahmed\AppData\Local\Programs\PHP\cacert.pem',
                            'timeout' => 0,
                            'connect_timeout' => 60,
                        ],
                    ]);
                    
                    // Use multipart uploader
                    $uploader = new MultipartUploader($s3Client, $filePath, [
                        'bucket' => config('filesystems.disks.r2.bucket'),
                        'key' => $fullPath,
                        'acl' => 'public-read',
                    ]);
                    
                    $result = $uploader->upload();
                    $uploaded = true;
                    
                    \Log::info('R2 Upload: Multipart upload successful', [
                        'etag' => $result['ETag'] ?? 'N/A',
                    ]);
                } else {
                    // For smaller files, use regular put()
                    \Log::info('R2 Upload: Reading file contents', [
                        'file_path' => $filePath,
                        'file_size' => $fileSize,
                    ]);
                    
                    $fileContents = file_get_contents($filePath);
                    if ($fileContents === false) {
                        throw new Exception('Failed to read file contents');
                    }
                    
                    \Log::info('R2 Upload: File contents read, size: ' . strlen($fileContents) . ' bytes');
                    
                    // Try put with visibility
                    \Log::info('R2 Upload: Attempting put() to R2', ['full_path' => $fullPath]);
                    $uploaded = $storage->put($fullPath, $fileContents, 'public');
                    
                    \Log::info('R2 Upload: put() result', ['uploaded' => $uploaded ? 'true' : 'false']);
                    
                    if (!$uploaded) {
                        \Log::warning('R2 Upload: put() failed, trying writeStream');
                        // Method 2: Try writeStream as fallback
                        $stream = fopen($filePath, 'r');
                        if ($stream) {
                            $uploaded = $storage->writeStream($fullPath, $stream);
                            \Log::info('R2 Upload: writeStream() result', ['uploaded' => $uploaded ? 'true' : 'false']);
                            if (is_resource($stream)) {
                                fclose($stream);
                            }
                        } else {
                            \Log::error('R2 Upload: Failed to open file stream');
                        }
                    }
                }
                
                // Restore original throw setting
                config(['filesystems.disks.r2.throw' => $originalThrow]);
            } catch (\Aws\Exception\AwsException $e) {
                $uploadError = $e->getAwsErrorMessage() ?: $e->getMessage();
                \Log::error('R2 Upload AWS Exception during upload', [
                    'error' => $uploadError,
                    'error_code' => $e->getAwsErrorCode(),
                    'full_path' => $fullPath,
                    'file_size' => $file->getSize(),
                    'http_status' => $e->getStatusCode(),
                ]);
            } catch (Exception $e) {
                $uploadError = $e->getMessage();
                \Log::error('R2 Upload Exception during upload', [
                    'error' => $uploadError,
                    'full_path' => $fullPath,
                    'file_size' => $file->getSize(),
                    'exception_class' => get_class($e),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
            
            if ($uploaded) {
                // Wait a moment for R2 to process
                sleep(1);
                
                // Verify the file was actually uploaded
                try {
                    $exists = $storage->exists($fullPath);
                    
                    if (!$exists) {
                        \Log::warning('R2 Upload returned true but file does not exist', [
                            'full_path' => $fullPath,
                        ]);
                        
                        return [
                            'status' => false,
                            'path' => null,
                            'url' => null,
                            'error' => 'Upload appeared successful but file not found on R2',
                        ];
                    }
                    
                    $fileSize = $storage->size($fullPath);
                    $url = $storage->url($fullPath);
                    
                    \Log::info('R2 Upload Successful', [
                        'full_path' => $fullPath,
                        'file_size' => $fileSize,
                        'url' => $url,
                    ]);
                    
                    return [
                        'status' => true,
                        'path' => $fullPath,
                        'url' => $url,
                    ];
                } catch (Exception $e) {
                    \Log::error('R2 Upload verification failed', [
                        'error' => $e->getMessage(),
                        'full_path' => $fullPath,
                    ]);
                    
                    return [
                        'status' => false,
                        'path' => null,
                        'url' => null,
                        'error' => 'Upload verification failed: ' . $e->getMessage(),
                    ];
                }
            }
            
            \Log::error('R2 Upload returned false', [
                'full_path' => $fullPath,
                'file_size' => $file->getSize(),
                'upload_error' => $uploadError,
            ]);
            
            return [
                'status' => false,
                'path' => null,
                'url' => null,
                'error' => $uploadError ?? 'Upload returned false without exception',
            ];
            
        } catch (Exception $e) {
            \Log::error('R2 Upload Error: ' . $e->getMessage(), [
                'course_id' => $courseId,
                'section_id' => $sectionId,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
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
     * Path structure: Courses/{course_id}/{section_id}/
     * Example: Courses/133/20/
     * 
     * @param int $courseId The course/webinar ID
     * @param int|null $sectionId The section/chapter ID (chapter_id from database)
     * @param string $fileType
     * @return string
     */
    protected function buildPath(int $courseId, ?int $sectionId = null, string $fileType = 'video'): string
    {
        $path = 'Courses/' . $courseId;
        
        if ($sectionId !== null) {
            $path .= '/' . $sectionId;
        }
        
        return $path;
    }
    
    /**
     * Get the worker/proxy URL for a file
     * Note: This should not be used for video streaming - videos use worker tokens via WebinarController
     * 
     * @param string $path
     * @return string|null
     */
    public function getUrl(string $path): ?string
    {
        try {
            // Use worker base URL if configured, otherwise fallback to public URL
            $workerBase = config('services.stream.worker_base');
            if (!empty($workerBase)) {
                // For worker URLs, we typically need to generate a token
                // But for non-video files, we might just return the path
                // This method is mainly for reference - actual video URLs are generated in WebinarController
                return rtrim($workerBase, '/') . '/' . ltrim($path, '/');
            }
            
            // Fallback to public URL if worker not configured
            $publicUrl = config('r2.public_url');
            if (!empty($publicUrl)) {
                return rtrim($publicUrl, '/') . '/' . ltrim($path, '/');
            }
            
            // If neither is configured, return null
            \Log::warning('R2 Get URL: Neither worker_base nor public_url is configured');
            return null;
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
     * @param int $courseId The course/webinar ID
     * @param int|null $sectionId The section/chapter ID
     * @return array
     */
    public function listFiles(int $courseId, ?int $sectionId = null): array
    {
        try {
            $path = $this->buildPath($courseId, $sectionId);
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
