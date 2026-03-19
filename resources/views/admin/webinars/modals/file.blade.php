<!-- Modal -->
<div class="d-none" id="webinarFileModal">
    <h3 class="section-title after-line font-20 text-dark-blue mb-25">{{ trans('public.add_file') }}</h3>
    <form action="{{ getAdminPanelUrl() }}/files/store" method="post" enctype="multipart/form-data">
        <input type="hidden" name="webinar_id" value="{{  !empty($webinar) ? $webinar->id :''  }}">

        @if(!empty(getGeneralSettings('content_translate')))
            <div class="form-group">
                <label class="input-label">{{ trans('auth.language') }}</label>
                <select name="locale" class="form-control ">
                    @foreach($userLanguages as $lang => $language)
                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                    @endforeach
                </select>
                @error('locale')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        @else
            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
        @endif

        <div class="form-group">
            <label class="input-label">{{ trans('public.title') }}</label>
            <input type="text" name="title" class="form-control" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('public.chapter') }}</label>
            <select class="custom-select" name="chapter_id">
                <option value="">{{ trans('admin/main.no_chapter') }}</option>

                @if(!empty($chapters))
                    @foreach($chapters as $chapter)
                        <option value="{{ $chapter->id }}">{{ $chapter->title }}</option>
                    @endforeach
                @endif
            </select>
            <div class="invalid-feedback"></div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.source') }}</label>
                    <select name="storage"
                            class="js-file-storage form-control"
                    >
                        @foreach(\App\Models\File::$fileSources as $source)
                            <option value="{{ $source }}">{{ trans('update.file_source_'.$source) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label class="input-label">{{ trans('public.accessibility') }}</label>
                    <select class="custom-select" name="accessibility" required>
                        <option selected disabled>{{ trans('public.choose_accessibility') }}</option>
                        <option value="free">{{ trans('public.free') }}</option>
                        <option value="paid">{{ trans('public.paid') }}</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </div>

        <div class="form-group js-file-path-input">
            <div class="local-input input-group">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text admin-file-manager" data-input="file_path_record" data-preview="holder">
                        <i class="fa fa-upload"></i>
                    </button>
                </div>
                <input type="text" name="file_path" id="file_path_record" value="" class="js-ajax-file_path form-control" placeholder="{{ trans('webinars.file_upload_placeholder') }}"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group js-s3-file-path-input d-none">
            <label class="input-label" for="s3File_record">
                {{ trans('update.choose_file') }}
                <span class="sr-only">{{ trans('update.drag_drop_or_click_to_upload') }}</span>
            </label>

            {{-- Drag and Drop Zone --}}
            <div class="js-file-drag-drop-zone file-drag-drop-zone border-2 border-dashed rounded-12 p-20 text-center mb-12 position-relative" 
                 role="region" 
                 aria-label="{{ trans('update.file_upload_area') }}"
                 tabindex="0"
                 data-file-input-id="s3File_record">
                <div class="js-drag-drop-content">
                    <div class="mb-12">
                        <i class="fa fa-upload fa-3x text-gray-400" aria-hidden="true"></i>
                    </div>
                    <p class="font-14 text-gray-600 mb-4">
                        <span class="js-drag-drop-text">{{ trans('update.drag_drop_files_here') }}</span>
                        <span class="sr-only">{{ trans('update.or') }}</span>
                    </p>
                    <p class="font-12 text-gray-500 mb-0">
                        {{ trans('update.or_click_to_browse') }}
                    </p>
                    <p class="font-12 text-gray-400 mt-8 mb-0">
                        {{ trans('update.supported_formats') }}: MP4, AVI, MKV, MOV, PDF, DOC, DOCX
                    </p>
                </div>
                <div class="js-drag-drop-overlay d-none position-absolute top-0 start-0 w-100 h-100 bg-primary-10 border-2 border-primary rounded-12 d-flex align-items-center justify-content-center">
                    <div class="text-center">
                        <i class="fa fa-upload fa-3x text-primary mb-8" aria-hidden="true"></i>
                        <p class="font-14 font-weight-bold text-primary mb-0">{{ trans('update.drop_file_here') }}</p>
                    </div>
                </div>
            </div>

            {{-- Hidden File Input --}}
            <div class="input-group d-none">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text" aria-label="{{ trans('update.upload') }}">
                        <i class="fa fa-upload" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="custom-file">
                    <input type="file" 
                           name="s3_file" 
                           class="js-s3-file-input custom-file-input cursor-pointer" 
                           id="s3File_record"
                           aria-label="{{ trans('update.choose_file') }}"
                           aria-describedby="s3_file_help_record">
                    <label class="custom-file-label cursor-pointer" for="s3File_record">{{ trans('update.choose_file') }}</label>
                </div>
            </div>

            {{-- Selected File Display --}}
            <div class="js-selected-file-display d-none mt-12 p-12 bg-gray-50 rounded-8">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center flex-1">
                        <i class="fa fa-file text-primary mr-8" aria-hidden="true"></i>
                        <div class="flex-1">
                            <p class="font-14 font-weight-bold text-dark mb-2 js-selected-file-name"></p>
                            <p class="font-12 text-gray-500 mb-0 js-selected-file-size"></p>
                        </div>
                    </div>
                    <button type="button" 
                            class="js-remove-file btn btn-sm btn-transparent text-danger p-4" 
                            aria-label="{{ trans('update.remove_file') }}">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div id="s3_file_help_record" class="font-12 text-gray-500 mt-8">
                {{ trans('update.max_file_size') }}: 2GB
            </div>

            <div class="invalid-feedback" style="position: absolute;bottom: -20px"></div>
        </div>

        <div class="row form-group js-file-type-volume d-none">
            <div class="col-6">
                <label class="input-label">{{ trans('webinars.file_type') }}</label>
                <select name="file_type" class="js-ajax-file_type form-control">
                    <option value="">{{ trans('webinars.select_file_type') }}</option>

                    @foreach(\App\Models\File::$fileTypes as $fileType)
                        <option value="{{ $fileType }}">{{ trans('update.file_type_'.$fileType) }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-6">
                <label class="input-label">{{ trans('webinars.file_volume') }}</label>
                <input type="text" name="volume" value="" class="js-ajax-volume form-control" placeholder="{{ trans('webinars.online_file_volume') }}"/>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="input-label">{{ trans('public.description') }}</label>
            <textarea name="description" class="js-ajax-description form-control" rows="6"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="js-online_viewer-input form-group mt-20">
            <div class="d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="online_viewerSwitch_record">{{ trans('update.online_viewer') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="online_viewer" class="custom-control-input" id="online_viewerSwitch_record">
                    <label class="custom-control-label" for="online_viewerSwitch_record"></label>
                </div>
            </div>
        </div>


        <div class="form-group mt-20">
            <div class="d-flex align-items-center justify-content-between">
                <label class="cursor-pointer input-label" for="fileStatusSwitch_record">{{ trans('public.active') }}</label>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="status" class="custom-control-input" id="fileStatusSwitch_record">
                    <label class="custom-control-label" for="fileStatusSwitch_record"></label>
                </div>
            </div>
        </div>

        @if(getFeaturesSettings('sequence_content_status'))
            <div class="form-group mb-1">
                <div class="d-flex align-items-center justify-content-between">
                    <label class="cursor-pointer input-label" for="SequenceContentSwitch_record">{{ trans('update.sequence_content') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="sequence_content" class="js-sequence-content-switch custom-control-input" id="SequenceContentSwitch_record">
                        <label class="custom-control-label" for="SequenceContentSwitch_record"></label>
                    </div>
                </div>
            </div>

            <div class="js-sequence-content-inputs pl-2 d-none">
                <div class="form-group mb-1">
                    <div class="d-flex align-items-center justify-content-between">
                        <label class="cursor-pointer input-label" for="checkPreviousPartsSwitch_record">{{ trans('update.check_previous_parts') }}</label>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" checked name="check_previous_parts" class="custom-control-input" id="checkPreviousPartsSwitch_record">
                            <label class="custom-control-label" for="checkPreviousPartsSwitch_record"></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="input-label">{{ trans('update.access_after_day') }}</label>
                    <input type="number" name="access_after_day" value="" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        @endif

        <div class="mt-3 d-flex align-items-center justify-content-end">
            <button type="button" id="saveFile" class="btn btn-primary">{{ trans('public.save') }}</button>
            <button type="button" class="btn btn-danger ml-2 close-swl">{{ trans('public.close') }}</button>
        </div>
    </form>
</div>
