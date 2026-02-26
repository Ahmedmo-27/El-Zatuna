<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mixins\BunnyCDN\BunnyVideoStream;
use App\Models\File;
use App\Models\Translation\FileTranslation;
use App\Models\Webinar;
use App\Models\WebinarChapterItem;
use App\Services\R2StorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Validator;

class FileController extends Controller
{
    public function store(Request $request)
    {
        // Increase PHP upload limits for large files
        @ini_set('upload_max_filesize', '2048M');
        @ini_set('post_max_size', '2048M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        @ini_set('memory_limit', '512M');
        
        $user = auth()->user();

        $data = $request->get('ajax')['new'] ?? [];
        $fileUpload = $request->file('ajax.new.file_upload');
        $r2UploadedPath = $data['r2_path'] ?? null;
        $r2UploadedFlag = !empty($data['r2_uploaded']);
        $r2SizeBytes = isset($data['r2_size_bytes']) ? (int)$data['r2_size_bytes'] : null;

        \Log::info('FileController::store request', [
            'has_file' => !empty($fileUpload),
            'file_valid' => $fileUpload ? $fileUpload->isValid() : false,
            'storage' => $data['storage'] ?? null,
            'webinar_id' => $data['webinar_id'] ?? null,
            'chapter_id' => $data['chapter_id'] ?? null,
        ]);

        if (!empty($fileUpload)) {
            $data['file_upload'] = $fileUpload;
        }

        if (empty($data['storage'])) {
            $data['storage'] = 'upload';
        }

        // Require file when storage is upload or r2 - fail early with clear message
        if (in_array($data['storage'], ['upload', 'r2'])) {
            if (!$r2UploadedFlag && (empty($fileUpload) || !$fileUpload->isValid())) {
                $message = empty($fileUpload)
                    ? 'Please choose a file to upload.'
                    : 'File upload failed. Please try again.';
                return response()->json([
                    'code' => 422,
                    'msg' => $message,
                    'errors' => ['file_upload' => [$message]],
                ], 422);
            }
            // Validate file size (2GB max) before proceeding (direct upload or server upload)
            $sizeToCheck = null;
            if ($r2UploadedFlag && $r2SizeBytes !== null) {
                $sizeToCheck = $r2SizeBytes;
            } elseif (!empty($fileUpload)) {
                $sizeToCheck = $fileUpload->getSize();
            }
            if ($sizeToCheck !== null && $sizeToCheck > 2 * 1024 * 1024 * 1024) {
                $message = 'File is too large. Maximum size is 2GB.';
                return response()->json([
                    'code' => 422,
                    'msg' => $message,
                    'errors' => ['file_upload' => [$message]],
                ], 422);
            }
        }

        $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
        $sourceRequiredFileVolume = ['external_link', 'google_drive'];
        $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe', 'secure_host'];

        if (in_array($data['storage'], $sourceDefaultFileTypeAndVolume)) {
            $data['file_type'] = 'video';
            $data['volume'] = !empty($data['volume']) ? $data['volume'] : 0;
        }

        $rules = [
            'webinar_id' => 'required',
            'chapter_id' => 'required',
            'title' => 'required|max:255',
            'accessibility' => 'required|' . Rule::in(File::$accessibility),
            'price' => ['nullable', 'numeric', 'min:0', Rule::requiredIf($data['accessibility'] == 'paid')],
            'file_url' => 'required',
            'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
            'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
            'description' => 'nullable',
        ];

        if ($data['storage'] == 'upload_archive') {
            $rules['file_url'] = 'nullable';
            $rules['file_upload'] = 'required|file|mimes:zip|max:2097152'; // 2GB max size
            $rules['interactive_type'] = 'required';
            $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
        }

        if (in_array($data['storage'], ['upload', 'r2'])) {
            $rules['file_url'] = 'nullable';
            // When file is uploaded directly to R2 from browser, skip file_upload validation here
            if (!$r2UploadedFlag) {
                $rules['file_upload'] = $this->handleUploadAndS3FileValidationByType($data['file_type'] ?? null);
            }
        }

        if ($data['storage'] == 'secure_host') {
            $rules['file_url'] = 'nullable';
            $rules['file_upload'] = 'required|file|mimes:mp4,avi,mkv,mov,wmv,flv,webm|max:2097152'; // 2GB max size

            if ($data['secure_host_upload_type'] == "manual") {
                $rules['file_upload'] = 'nullable';
                $rules['file_url'] = 'required';
                $rules['volume'] = 'required';
            }
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response([
                'code' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data['downloadable'] = !empty($data['downloadable']);
        if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
            $data['downloadable'] = false;
        } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
            $data['downloadable'] = true;
        }

        $data['price'] = (!empty($data['price']) and $data['accessibility'] == 'paid') ? $data['price'] : 0;

        if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
            $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
            $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
        } else {
            $data['check_previous_parts'] = false;
            $data['access_after_day'] = null;
        }

        $webinar = Webinar::find($data['webinar_id']);

        if (empty($webinar)) {
            return response([
                'code' => 404,
                'msg' => trans('update.course_not_found'),
            ], 404);
        }

        // Validate that chapter belongs to this webinar
        if (!empty($data['chapter_id']) && !$webinar->chapters()->where('id', $data['chapter_id'])->exists()) {
            return response([
                'code' => 422,
                'msg' => trans('update.invalid_chapter_for_course'),
            ], 422);
        }

        if ($webinar->canAccess($user)) {
            $volume = 0;
            $fileInfos = null;

            if ($data['storage'] == 'upload_archive') {
                $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
                $fileInfos = $this->fileInfo($data['file_url']);

                if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                    return response([
                        'code' => 422,
                        'errors' => [
                            'file_url' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                        ],
                    ], 422);
                }

                $volume = convertToMB($fileInfos['size'] ?? 0);
                $fileInfos['extension'] = 'archive';
                $data['interactive_file_path'] = $this->handleUnZipFile($webinar, $data);
            } elseif (in_array($data['storage'], ['secure_host', 'r2']) || $data['storage'] == 'upload') {
                // "Upload" in UI is stored as R2 (only cloud storage); no local upload for course files
                if ($data['storage'] == 'upload') {
                    $data['storage'] = 'r2';
                }
                if ($data['storage'] == 'r2') {
                    $sectionId = $data['chapter_id'] ?? null;
                    // Validate that chapter belongs to this webinar if provided
                    if ($sectionId && !$webinar->chapters()->where('id', $sectionId)->exists()) {
                        return response()->json([
                            'code' => 422,
                            'msg' => trans('update.invalid_chapter_for_course')
                        ], 422);
                    }

                    if ($r2UploadedFlag && $r2UploadedPath) {
                        // File was already uploaded directly to R2 from the browser
                        $data['volume'] = convertToMB($r2SizeBytes ?? 0);
                        $result = [
                            'status' => true,
                            'path' => $r2UploadedPath,
                        ];
                        \Log::info('FileController::store using existing R2 uploaded file', [
                            'webinar_id' => $webinar->id,
                            'section_id' => $sectionId,
                            'path' => $r2UploadedPath,
                            'file_size' => $r2SizeBytes,
                        ]);
                    } else {
                        // Classic flow: Laravel uploads the file to R2
                        $data['volume'] = convertToMB($fileUpload->getSize());
                        \Log::info('FileController::store calling uploadFileToR2', [
                            'webinar_id' => $webinar->id,
                            'section_id' => $sectionId,
                            'file_name' => $fileUpload->getClientOriginalName(),
                            'file_size' => $fileUpload->getSize(),
                        ]);
                        $result = $this->uploadFileToR2($fileUpload, $webinar->id, $sectionId);
                        \Log::info('FileController::store uploadFileToR2 result', [
                            'status' => $result['status'] ?? null,
                            'path' => $result['path'] ?? null,
                            'error' => $result['error'] ?? null,
                        ]);
                    }
                } else {
                    if ($data['secure_host_upload_type'] == "direct") {
                        $data['volume'] = convertToMB($fileUpload->getSize());
                        $result = $this->uploadFileToBunny($webinar, $fileUpload);
                    } else {
                        $result['status'] = true;
                        $result['path'] = $data['file_url'];
                    }
                }

                if (!$result['status']) {
                    // Log the error if available
                    if (isset($result['error'])) {
                        \Log::error('R2 Upload Failed in FileController', [
                            'error' => $result['error'],
                            'course_id' => $webinar->id,
                            'section_id' => $sectionId ?? null,
                        ]);
                    }
                    
                    // Return proper error response
                    return response()->json([
                        'code' => 500,
                        'msg' => $result['error'] ?? trans('update.file_upload_failed'),
                        'errors' => [
                            'file_upload' => [$result['error'] ?? trans('update.file_upload_failed')]
                        ]
                    ], 500);
                }

                $data['file_url'] = $result['path'] ?? null;
                
                // Do not create record if upload storage has no path
                if (in_array($data['storage'], ['upload', 'r2']) && empty($data['file_url'])) {
                    \Log::error('FileController::store - R2/upload succeeded but path empty', [
                        'course_id' => $data['webinar_id'] ?? null,
                    ]);
                    return response()->json([
                        'code' => 500,
                        'msg' => trans('update.file_upload_failed'),
                        'errors' => ['file_upload' => [trans('update.file_upload_failed')]],
                    ], 500);
                }
                
                // Determine file type from extension if not provided
                if (empty($data['file_type']) && !empty($fileUpload)) {
                    $extension = strtolower($fileUpload->getClientOriginalExtension());
                    // Map common extensions to file types
                    $extensionMap = [
                        'mp4' => 'mp4',
                        'avi' => 'avi',
                        'mkv' => 'mkv',
                        'mov' => 'mov',
                        'wmv' => 'wmv',
                        'flv' => 'flv',
                        'webm' => 'webm',
                        'pdf' => 'pdf',
                        'doc' => 'doc',
                        'docx' => 'docx',
                        'txt' => 'txt',
                    ];
                    $data['file_type'] = $extensionMap[$extension] ?? $extension;
                }
                
                $fileInfos['extension'] = $data['file_type'] ?? 'mp4'; // Default to mp4 if still empty
                $fileInfos['size'] = $data['volume'];

                if ($data['storage'] == 'secure_host' and $data['secure_host_upload_type'] == "manual") {
                    $volume = $data['volume'];
                } else {
                    $volume = convertToMB(($data['volume'] ?? 0));
                }

            } else {
                $volume = !empty($data['volume']) ? $data['volume'] : 0; // input is MB
            }

            $file = File::create([
                'creator_id' => $user->id,
                'webinar_id' => $data['webinar_id'],
                'chapter_id' => $data['chapter_id'],
                'file' => $data['file_url'],
                'volume' => $volume,
                'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                'accessibility' => $data['accessibility'],
                'price' => $data['price'],
                'storage' => $data['storage'],
                'secure_host_upload_type' => $data['secure_host_upload_type'] ?? null,
                'interactive_type' => $data['interactive_type'] ?? null,
                'interactive_file_name' => $data['interactive_file_name'] ?? null,
                'interactive_file_path' => $data['interactive_file_path'] ?? null,
                'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                'downloadable' => $data['downloadable'],
                'check_previous_parts' => $data['check_previous_parts'],
                'access_after_day' => $data['access_after_day'],
                'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                'created_at' => time()
            ]);

            if (!empty($file)) {
                $locale = $request->get('locale', getDefaultLocale());

                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                WebinarChapterItem::makeItem($file->creator_id, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
            }

            $webinar->update([
                'updated_at' => time()
            ]);

            return response()->json([
                'code' => 200,
                'file_id' => $file->id,
                'path' => $file->file,
            ], 200);
        }

        abort(403);
    }

    private function handleUnZipFile($webinar, $data)
    {
        $path = $data['file_url'];
        $interactiveType = $data['interactive_type'] ?? null;
        $interactiveFileName = $data['interactive_file_name'] ?? null;

        $storage = Storage::disk('public');

        $fileInfo = $this->fileInfo($path);

        $extractPath = "{$webinar->creator_id}/webinars/{$webinar->id}/files/{$fileInfo['name']}";
        $storageExtractPath = $storage->url($extractPath);

        if (!$storage->exists($extractPath)) {
            $storage->makeDirectory($extractPath);

            $filePath = public_path($path);

            $zip = new \ZipArchive();
            $res = $zip->open($filePath);

            if ($res) {
                $zip->extractTo(public_path($storageExtractPath));

                $zip->close();
            }
        }

        $fileName = 'index.html';

        if ($interactiveType == 'i_spring') {
            $fileName = 'story.html';
        } elseif ($interactiveType == 'custom') {
            $fileName = $interactiveFileName;
        }

        return $storageExtractPath . '/' . $fileName;
    }
    
    /**
     * Generate a pre-signed R2 upload URL for direct browser uploads.
     * Used to bypass App Platform request timeouts for large course files.
     */
    public function presignR2Upload(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'webinar_id' => 'required|integer|exists:webinars,id',
            'chapter_id' => 'nullable|integer',
            'file_name' => 'required|string|max:255',
            'file_size' => 'required|integer|min:1|max:2147483648', // 2GB
            'file_mime' => 'nullable|string|max:255',
        ]);
        
        $webinar = Webinar::find($validated['webinar_id']);
        if (empty($webinar) || !$webinar->canAccess($user)) {
            return response()->json([
                'code' => 403,
                'msg' => trans('public.forbidden'),
            ], 403);
        }
        
        $sectionId = $validated['chapter_id'] ?? null;
        if ($sectionId && !$webinar->chapters()->where('id', $sectionId)->exists()) {
            return response()->json([
                'code' => 422,
                'msg' => trans('update.invalid_chapter_for_course'),
            ], 422);
        }
        
        $service = new R2StorageService();
        $result = $service->createPresignedUploadUrl(
            $webinar->id,
            $sectionId,
            $validated['file_name'],
            (int) $validated['file_size'],
            $validated['file_mime'] ?? null
        );
        
        if (!$result['status']) {
            return response()->json([
                'code' => 500,
                'msg' => $result['error'] ?? trans('update.file_upload_failed'),
            ], 500);
        }
        
        return response()->json([
            'code' => 200,
            'upload_url' => $result['upload_url'],
            'path' => $result['path'],
            'headers' => $result['headers'] ?? [],
            'max_size_bytes' => 2 * 1024 * 1024 * 1024,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Increase PHP upload limits for large files
        @ini_set('upload_max_filesize', '2048M');
        @ini_set('post_max_size', '2048M');
        @ini_set('max_execution_time', '300');
        @ini_set('max_input_time', '300');
        @ini_set('memory_limit', '512M');
        
        $user = auth()->user();
        $data = $request->get('ajax')[$id];

        $webinar = Webinar::query()->find($data['webinar_id']);

        if (empty($webinar)) {
            return response([
                'code' => 404,
                'msg' => trans('update.course_not_found'),
            ], 404);
        }

        // Validate that chapter belongs to this webinar
        if (!empty($data['chapter_id']) && !$webinar->chapters()->where('id', $data['chapter_id'])->exists()) {
            return response([
                'code' => 422,
                'msg' => trans('update.invalid_chapter_for_course'),
            ], 422);
        }

        if ($webinar->canAccess($user)) {
            $file = File::query()->where('id', $id)
                ->where(function ($query) use ($user, $webinar) {
                    $query->where('creator_id', $user->id);
                    $query->orWhere('webinar_id', $webinar->id);
                })
                ->first();

            if (empty($file)) {
                return response([
                    'code' => 404,
                    'msg' => trans('update.file_not_found'),
                ], 404);
            }

            if (!empty($file)) {

                $fileUpload = $request->file("ajax.{$id}.file_upload");

                if (!empty($fileUpload)) {
                    $data['file_upload'] = $fileUpload;
                }

                if (empty($data['storage'])) {
                    $data['storage'] = 'upload';
                }

                $sourceRequiredFileType = ['external_link', 's3', 'google_drive', 'upload'];
                $sourceRequiredFileVolume = ['external_link', 'google_drive'];
                $sourceDefaultFileTypeAndVolume = ['youtube', 'vimeo', 'iframe', 'secure_host'];

                if (in_array($data['storage'], $sourceDefaultFileTypeAndVolume)) {
                    $data['file_type'] = 'video';
                    $data['volume'] = !empty($data['volume']) ? $data['volume'] : 0;
                }

                $fileTypeIsChanged = !!(empty($data['file_type']) or $data['file_type'] != $file->file_type);

                $rules = [
                    'webinar_id' => 'required',
                    'chapter_id' => 'required',
                    'title' => 'required|max:255',
                    'accessibility' => 'required|' . Rule::in(File::$accessibility),
                    'price' => ['nullable', 'numeric', 'min:0', Rule::requiredIf($data['accessibility'] == 'paid')],
                    'file_url' => 'required',
                    'file_type' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileType)),
                    'volume' => Rule::requiredIf(in_array($data['storage'], $sourceRequiredFileVolume)),
                    'description' => 'nullable',
                ];

                if ($data['storage'] == 'upload_archive') {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = ($fileTypeIsChanged ? 'required' : 'nullable') . '|file|mimes:zip|max:2097152'; // 2GB max size
                    $rules['interactive_type'] = 'required';
                    $rules['interactive_file_name'] = Rule::requiredIf($data['interactive_type'] == 'custom');
                }

                if (in_array($data['storage'], ['upload', 'r2'])) {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = $this->handleUploadAndS3FileValidationByType($data['file_type'] ?? null, $fileTypeIsChanged);
                }

                if ($data['storage'] == 'secure_host') {
                    $rules['file_url'] = 'nullable';
                    $rules['file_upload'] = ($fileTypeIsChanged ? 'required' : 'nullable') . '|file|mimes:mp4,avi,mkv,mov,wmv,flv,webm|max:2097152'; // 2GB max size

                    if ($data['secure_host_upload_type'] == "manual") {
                        $rules['secure_host_file_path'] = 'required';
                        $rules['volume'] = 'required';
                    }
                }

                $validator = Validator::make($data, $rules);

                if ($validator->fails()) {
                    return response([
                        'code' => 422,
                        'errors' => $validator->errors(),
                    ], 422);
                }

                $data['downloadable'] = !empty($data['downloadable']);
                if (in_array($data['storage'], ['youtube', 'vimeo', 'iframe', 'google_drive', 'upload_archive'])) {
                    $data['downloadable'] = false;
                } elseif (in_array($data['storage'], ['external_link', 's3']) and $data['file_type'] != 'video') {
                    $data['downloadable'] = true;
                }

                $data['price'] = (!empty($data['price']) and $data['accessibility'] == 'paid') ? $data['price'] : 0;

                if (!empty($data['sequence_content']) and $data['sequence_content'] == 'on') {
                    $data['check_previous_parts'] = (!empty($data['check_previous_parts']) and $data['check_previous_parts'] == 'on');
                    $data['access_after_day'] = !empty($data['access_after_day']) ? $data['access_after_day'] : null;
                } else {
                    $data['check_previous_parts'] = false;
                    $data['access_after_day'] = null;
                }


                $volume = 0;
                $fileInfos = null;

                if ($data['storage'] == 'upload_archive') {

                    if (!empty($fileUpload)) {
                        $data['file_url'] = $this->uploadFile($fileUpload, "webinars/{$webinar->id}/files", null, $webinar->creator_id);
                    }

                    $fileInfos = $this->fileInfo($data['file_url']);

                    if (empty($fileInfos) or $fileInfos['extension'] != 'zip') {
                        return response([
                            'code' => 422,
                            'errors' => [
                                'file_url' => [trans('validation.mimes', ['attribute' => 'file', 'values' => 'zip'])]
                            ],
                        ], 422);
                    }

                    $volume = convertToMB($fileInfos['size'] ?? 0);
                    $fileInfos['extension'] = 'archive';
                    $data['interactive_file_path'] = $this->handleUnZipFile($webinar, $data);

                } elseif (in_array($data['storage'], ['secure_host', 'r2']) || $data['storage'] == 'upload') {
                    // "Upload" in UI is stored as R2 (only cloud storage)
                    if ($data['storage'] == 'upload') {
                        $data['storage'] = 'r2';
                    }
                    $result = ['status' => false, 'path' => null, 'error' => null];
                    if ($data['storage'] == 'r2') {
                        $sectionId = $data['chapter_id'] ?? null;
                        if (!empty($fileUpload)) {
                            $data['volume'] = convertToMB($fileUpload->getSize());
                            // Validate that chapter belongs to this webinar if provided
                            if ($sectionId && !$webinar->chapters()->where('id', $sectionId)->exists()) {
                                return response()->json([
                                    'code' => 422,
                                    'msg' => trans('update.invalid_chapter_for_course')
                                ], 422);
                            }
                            // Upload to Cloudflare R2 under Courses/{course_id}/{section_id}/
                            $result = $this->uploadFileToR2($fileUpload, $webinar->id, $sectionId);
                        } else {
                            // No new file: keep existing file path
                            $result = ['status' => true, 'path' => $file->file ?? $data['file_url']];
                        }
                    } else {
                        if ($data['secure_host_upload_type'] == "direct") {
                            if (!empty($fileUpload)) {
                                $data['volume'] = convertToMB($fileUpload->getSize());
                                $result = $this->uploadFileToBunny($webinar, $fileUpload);
                            }
                        } else {
                            $result['status'] = true;
                            $result['path'] = $data['file_url'];
                        }
                    }

                    if (!$result['status']) {
                        // Log the error if available
                        if (isset($result['error'])) {
                            \Log::error('R2 Upload Failed in FileController (Update)', [
                                'error' => $result['error'],
                                'course_id' => $webinar->id,
                                'section_id' => $sectionId ?? null,
                                'file_id' => $id,
                            ]);
                        }
                        
                        // Return proper error response
                        return response()->json([
                            'code' => 500,
                            'msg' => $result['error'] ?? trans('update.file_upload_failed'),
                            'errors' => [
                                'file_upload' => [$result['error'] ?? trans('update.file_upload_failed')]
                            ]
                        ], 500);
                    }

                    $data['file_url'] = $result['path'] ?? null;
                    
                    // Do not save when r2/upload has no path
                    if (in_array($data['storage'], ['upload', 'r2']) && empty($data['file_url'])) {
                        \Log::error('FileController::update - R2/upload path empty', ['file_id' => $id]);
                        return response()->json([
                            'code' => 500,
                            'msg' => trans('update.file_upload_failed'),
                            'errors' => ['file_upload' => [trans('update.file_upload_failed')]],
                        ], 500);
                    }
                    
                    // Determine file type from extension if not provided
                    if (empty($data['file_type']) && !empty($fileUpload)) {
                        $extension = strtolower($fileUpload->getClientOriginalExtension());
                        // Map common extensions to file types
                        $extensionMap = [
                            'mp4' => 'mp4',
                            'avi' => 'avi',
                            'mkv' => 'mkv',
                            'mov' => 'mov',
                            'wmv' => 'wmv',
                            'flv' => 'flv',
                            'webm' => 'webm',
                            'pdf' => 'pdf',
                            'doc' => 'doc',
                            'docx' => 'docx',
                            'txt' => 'txt',
                        ];
                        $data['file_type'] = $extensionMap[$extension] ?? $extension;
                    }
                    
                    $fileInfos['extension'] = $data['file_type'] ?? 'mp4'; // Default to mp4 if still empty
                    $fileInfos['size'] = $data['volume'];

                    $volume = $data['volume'];

                } else {
                    $volume = !empty($data['volume']) ? $data['volume'] : 0; // input is MB
                }


                $changeChapter = ($data['chapter_id'] != $file->chapter_id);
                $oldChapterId = $file->chapter_id;
                
                // If storage type changed and old file exists, delete it
                $oldStorage = $file->storage;
                $oldFilePath = $file->file;
                if ($oldStorage != $data['storage'] && !empty($oldFilePath)) {
                    // Delete old file from old storage
                    if ($oldStorage == 'upload' && file_exists(public_path($oldFilePath))) {
                        @unlink(public_path($oldFilePath));
                        \Log::info('FileController: Deleted old local file', [
                            'file_id' => $id,
                            'old_path' => $oldFilePath,
                        ]);
                    } elseif ($oldStorage == 'r2') {
                        // Delete old R2 file
                        try {
                            $r2Service = new R2StorageService();
                            $r2Service->deleteFile($oldFilePath);
                            \Log::info('FileController: Deleted old R2 file', [
                                'file_id' => $id,
                                'old_path' => $oldFilePath,
                            ]);
                        } catch (Exception $e) {
                            \Log::warning('FileController: Failed to delete old R2 file', [
                                'file_id' => $id,
                                'old_path' => $oldFilePath,
                                'error' => $e->getMessage(),
                            ]);
                        }
                    }
                }

                $file->update([
                    'chapter_id' => $data['chapter_id'],
                    'file' => $data['file_url'],
                    'volume' => $volume,
                    'file_type' => !empty($fileInfos) ? $fileInfos['extension'] : $data['file_type'],
                    'accessibility' => $data['accessibility'],
                    'price' => $data['price'],
                    'storage' => $data['storage'],
                    'secure_host_upload_type' => $data['secure_host_upload_type'] ?? null,
                    'interactive_type' => $data['interactive_type'] ?? null,
                    'interactive_file_name' => $data['interactive_file_name'] ?? null,
                    'interactive_file_path' => $data['interactive_file_path'] ?? null,
                    'online_viewer' => (!empty($data['online_viewer']) and $data['online_viewer'] == 'on'),
                    'downloadable' => $data['downloadable'],
                    'check_previous_parts' => $data['check_previous_parts'],
                    'access_after_day' => $data['access_after_day'],
                    'status' => (!empty($data['status']) and $data['status'] == 'on') ? File::$Active : File::$Inactive,
                    'updated_at' => time()
                ]);

                if ($changeChapter) {
                    WebinarChapterItem::changeChapter($file->creator_id, $oldChapterId, $file->chapter_id, $file->id, WebinarChapterItem::$chapterFile);
                }

                $locale = $request->get('locale', getDefaultLocale());

                FileTranslation::updateOrCreate([
                    'file_id' => $file->id,
                    'locale' => mb_strtolower($locale),
                ], [
                    'title' => $data['title'],
                    'description' => $data['description'],
                ]);

                $webinar->update([
                    'updated_at' => time()
                ]);

                return response()->json([
                    'code' => 200,
                    'file_id' => (int) $id,
                    'path' => $data['file_url'],
                ], 200);
            }

        }

        abort(403);
    }

    private function handleUploadAndS3FileValidationByType($fileType = null, $required = true)
    {
        $rule = ($required ? 'required' : 'nullable') . '|file|max:2097152'; // 2GB max size

        if (!empty($fileType)) {
            switch ($fileType) {
                case 'pdf':
                    $rule .= '|mimes:pdf';
                    break;
                case 'power_point':
                    $rule .= '|mimes:ppt,pptx';
                    break;
                case 'sound':
                    $rule .= '|mimes:mp3,wav,ogg,aac';
                    break;
                case 'video':
                    $rule .= '|mimes:mp4,avi,mkv,mov,wmv,flv,webm';
                    break;
                case 'image':
                    $rule .= '|mimes:jpg,jpeg,png,gif,bmp,webp,svg';
                    break;
                case 'archive':
                    $rule .= '|mimes:zip,rar,tar,gz,7z';
                    break;
                case 'document':
                    $rule .= '|mimes:doc,docx,xls,xlsx,csv,txt,rtf';
                    break;
                case 'project':
                    $rule .= '';
                    break;
            }
        }

        return $rule;
    }

    public function fileInfo($path)
    {
        $file = array();

        $file_path = public_path($path);

        $filePath = pathinfo($file_path);

        $file['name'] = $filePath['filename'];
        $file['extension'] = $filePath['extension'];
        $file['size'] = filesize($file_path);

        return $file;
    }

    private function uploadFileToS3($file)
    {
        $user = auth()->user();

        $path = '/store/' . $user->id;

        $result = [
            'path' => null,
            'status' => true
        ];

        try {
            $fileName = time() . $file->getClientOriginalName();

            $storage = Storage::disk('minio');

            if (!$storage->exists($path)) {
                $storage->makeDirectory($path);
            }

            $path = $storage->put($path, $file, $fileName);
            $result['path'] = $storage->url($path);
        } catch (\Exception $ex) {

            $result = [
                'path' => response([
                    'code' => 500,
                    'message' => $ex->getMessage(),
                    'traces' => $ex->getTrace(),
                ], 500),
                'status' => false
            ];
        }

        return $result;
    }

    /**
     * Upload file to R2 cloud storage
     * 
     * Path structure: Courses/{course_id}/{section_id}/{timestamp}_{filename}
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $courseId The course/webinar ID
     * @param int|null $sectionId The section/chapter ID (chapter_id from database)
     * @return array ['status' => bool, 'path' => string|null]
     */
    private function uploadFileToR2($file, $courseId, $sectionId = null)
    {
        $r2Service = new R2StorageService();
        
        // Determine file type from extension
        $fileType = 'video'; // Default to video
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
            $fileType = 'document';
        }
        
        \Log::info('FileController: Starting R2 upload', [
            'course_id' => $courseId,
            'section_id' => $sectionId,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_type' => $fileType,
        ]);
        
        $result = $r2Service->uploadFile($file, $courseId, $sectionId, $fileType);
        
        \Log::info('FileController: R2 upload result', [
            'status' => $result['status'],
            'path' => $result['path'] ?? null,
            'error' => $result['error'] ?? null,
        ]);
        
        // Store path only, not URL (for private bucket compatibility)
        // Path will be used to stream through Laravel proxy
        return [
            'path' => $result['path'], // Always use path, never URL
            'status' => $result['status'],
            'error' => $result['error'] ?? null, // Include error for better debugging
        ];
    }

    private function uploadFileToBunny($webinar, $file)
    {
        $result = [
            'path' => null,
            'status' => true
        ];

        try {
            $bunnyVideoStream = new BunnyVideoStream();

            $collectionId = $bunnyVideoStream->createCollection("course {$webinar->id}", true);

            if ($collectionId) {

                $videoUrl = $bunnyVideoStream->uploadVideo($file->getClientOriginalName(), $collectionId, $file);

                $result['path'] = $videoUrl;
            }
        } catch (\Exception $ex) {

            $result = [
                'path' => response([
                    'code' => 500,
                    'message' => $ex->getMessage(),
                    'traces' => $ex->getTrace(),
                ], 500),
                'status' => false
            ];
        }

        return $result;
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $file = File::where('id', $id)->first();


        if (!empty($file)) {
            $webinar = Webinar::query()->find($file->webinar_id);

            if ($file->creator_id == $user->id or (!empty($webinar) and $webinar->canAccess($user))) {

                if ($file->storage == "secure_host") {
                    $bunnyVideoStream = new BunnyVideoStream();
                    $bunnyVideoStream->deleteVideo($file->file);
                }


                WebinarChapterItem::where('user_id', $file->creator_id)
                    ->where('item_id', $file->id)
                    ->where('type', WebinarChapterItem::$chapterFile)
                    ->delete();

                $file->delete();
            }
        }

        return response()->json([
            'code' => 200
        ], 200);
    }
}
