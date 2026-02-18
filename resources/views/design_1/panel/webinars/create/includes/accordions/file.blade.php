@if(!empty($file) and $file->storage == 'upload_archive')
    @include('design_1.panel.webinars.create.includes.accordions.interactive_file',['file' => $file])
@else
    <li data-id="{{ !empty($chapterItem) ? $chapterItem->id :'' }}" class="accordion bg-white border-gray-200 p-12 rounded-16 mt-16">
        <div class="accordion__title d-flex align-items-center justify-content-between " role="tab" id="file_{{ !empty($file) ? $file->id :'record' }}">
            <div class="d-flex align-items-center cursor-pointer" href="#collapseFile{{ !empty($file) ? $file->id :'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id : 'record' }}" role="button" data-toggle="collapse" aria-expanded="true">
                <div class="d-flex mr-8">
                    @php
                        $fileIcon = !empty($file) ? $file->getIconXByType() : 'document';
                    @endphp

                    @svg("iconsax-lin-{$fileIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])
                </div>

                <div class="font-14 font-weight-bold d-block">{{ !empty($file) ? $file->title : trans('public.add_new_files') }}</div>
            </div>

            <div class="d-flex align-items-center">

                @if(!empty($file))

                    @if($file->accessibility == 'free')
                        <span class="px-8 py-4 bg-primary-20 text-primary font-12 mr-12 rounded-8">{{ trans('public.free') }}</span>
                    @endif

                    @if($file->status != \App\Models\WebinarChapter::$chapterActive)
                        <span class="px-8 py-4 bg-danger-20 text-danger font-12 mr-12 rounded-8">{{ trans('public.disabled') }}</span>
                    @endif

                    <div class="js-change-content-chapter cursor-pointer mr-12" data-item-id="{{ $file->id }}" data-item-type="{{ \App\Models\WebinarChapterItem::$chapterFile }}" data-chapter-id="{{ !empty($chapter) ? $chapter->id : '' }}" data-tippy-content="{{ trans('public.edit_chapter') }}">
                        <x-iconsax-lin-category-2 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>

                    <div class="move-icon mr-12 cursor-pointer d-flex" data-tippy-content="{{ trans('update.sort') }}">
                        <x-iconsax-lin-arrow-3 class="icons text-gray-500" width="20px" height="20px"/>
                    </div>


                    <a href="/panel/files/{{ $file->id }}/delete" class="delete-action d-flex text-gray-500 mr-12">
                        <x-iconsax-lin-trash class="icons text-gray-500" width="20px" height="20px"/>
                    </a>
                @endif

                <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseFile{{ !empty($file) ? $file->id :'record' }}" aria-controls="collapseFile{{ !empty($file) ? $file->id :'record' }}" data-parent="#chapterContentAccordion{{ !empty($chapter) ? $chapter->id : 'record' }}" role="button" data-toggle="collapse" aria-expanded="true">
                    <x-iconsax-lin-arrow-up-1 class="icons text-gray-500" width="20px" height="20px"/>
                </div>

            </div>
        </div>

        <div id="collapseFile{{ !empty($file) ? $file->id :'record' }}" class=" collapse @if(empty($file)) show @endif" role="tabpanel">
            <div class="js-content-form file-form" data-action="/panel/files/{{ !empty($file) ? $file->id . '/update' : 'store' }}">
                <input type="hidden" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][webinar_id]" value="{{ !empty($webinar) ? $webinar->id :'' }}">

                <div class="mt-20">
                    @include('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($file) ? $file : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'extraData' => "data-webinar-id='".(!empty($webinar) ? $webinar->id : '')."'  data-id='".(!empty($file) ? $file->id : '')."'  data-relation='files' data-fields='title,description'"
                    ])
                </div>

                @if(!empty($file))
                    <div class="form-group ">
                        <label class="form-group-label">{{ trans('public.chapter') }}</label>
                        <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][chapter_id]" class="js-ajax-chapter_id form-control select2">
                            @foreach($webinar->chapters as $ch)
                                <option value="{{ $ch->id }}" {{ ($file->chapter_id == $ch->id) ? 'selected' : '' }}>{{ $ch->title }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                @else
                    <input type="hidden" name="ajax[new][chapter_id]" value="" class="chapter-input">
                @endif

                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.title') }}</label>
                    <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][title]" class="js-ajax-title form-control" value="{{ !empty($file) ? $file->title : '' }}" placeholder="{{ trans('forms.maximum_255_characters') }}"/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.source') }}</label>
                    @php
                        $availableSources = getFeaturesSettings('available_sources');
                        if (is_string($availableSources)) {
                            $decodedSources = json_decode($availableSources, true);
                            if (is_array($decodedSources)) {
                                $availableSources = $decodedSources;
                            } else {
                                $availableSources = array_filter(array_map('trim', explode(',', $availableSources)));
                            }
                        }
                        if (!is_array($availableSources) || empty($availableSources)) {
                            $availableSources = ['upload', 'external_link', 'youtube', 'vimeo', 'iframe', 's3', 'r2', 'google_drive', 'secure_host'];
                        }
                        // Ensure r2 is in the list if not already
                        if (!in_array('r2', $availableSources)) {
                            $availableSources[] = 'r2';
                        }
                    @endphp
                    <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][storage]"
                            class="js-file-storage form-control"
                    >
                        @foreach($availableSources as $source)
                            <option value="{{ $source }}" @if((!empty($file) and $file->storage == $source) or (empty($file) and $source == 'r2')) selected @endif>{{ trans('update.file_source_'.$source) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-14 text-gray-500 bg-white">{{ trans('public.accessibility') }}</label>

                    <div class="d-flex align-items-center js-ajax-accessibility mt-12">

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]" id="accessibilityRadio1_{{ !empty($file) ? $file->id : 'record' }}" value="free" class="custom-control-input" @if(empty($file) or (!empty($file) and $file->accessibility == 'free')) checked="checked" @endif>
                            <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio1_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('public.free') }}</label>
                        </div>

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][accessibility]" id="accessibilityRadio2_{{ !empty($file) ? $file->id : 'record' }}" value="paid" class="custom-control-input" @if(empty($file) or (!empty($file) and $file->accessibility == 'paid')) checked="checked" @endif>
                            <label class="custom-control__label cursor-pointer pl-0" for="accessibilityRadio2_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('public.paid') }}</label>
                        </div>
                    </div>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.price') }}</label>
                    <input type="number" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][price]" class="js-ajax-price form-control" value="{{ !empty($file) ? $file->price : '' }}" placeholder="{{ trans('update.enter_amount') }}" min="0" step="0.01"/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-secure-host-upload-type-field form-group {{ (!empty($file) and $file->storage == "secure_host") ? '' : 'd-none' }}">
                    <label class="font-14 text-gray-500 bg-white">{{ trans('update.upload_type') }}</label>

                    <div class="d-flex align-items-center js-ajax-secure_host_upload_type mt-12">

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][secure_host_upload_type]" id="uploadTypeRadio1_{{ !empty($file) ? $file->id : 'record' }}" value="direct" class="custom-control-input" {{ (empty($file) or $file->secure_host_upload_type == 'direct') ? 'checked' : '' }}>
                            <label class="custom-control__label cursor-pointer pl-0" for="uploadTypeRadio1_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('update.direct') }}</label>
                        </div>

                        <div class="custom-control custom-radio mr-12">
                            <input type="radio" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][secure_host_upload_type]" id="uploadTypeRadio2_{{ !empty($file) ? $file->id : 'record' }}" value="manual" class="custom-control-input" {{ (!empty($file) and $file->secure_host_upload_type == 'manual') ? 'checked' : '' }}>
                            <label class="custom-control__label cursor-pointer pl-0" for="uploadTypeRadio2_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('public.manual') }}</label>
                        </div>
                    </div>

                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-file-url-input {{ (!empty($file) and (in_array($file->storage, \App\Models\File::$urlInputSources) or ($file->storage == 'secure_host' and $file->secure_host_upload_type == 'manual'))) ? '' : 'd-none' }}">
                    <label class="form-group-label">{{ trans('public.link') }}</label>
                    <span class="has-translation bg-transparent"><x-iconsax-lin-link class="text-gray-500" width="24px" height="24px"/></span>
                    <input type="text" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_url]" value="{{ (!empty($file)) ? $file->file : '' }}" class="js-ajax-file_url form-control" placeholder="{{ trans('update.enter_file_url') }}"/>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="form-group js-file-upload-input {{ (empty($file) or (in_array($file->storage, ['upload', 's3', 'r2']) or ($file->storage == 'secure_host' and $file->secure_host_upload_type == 'direct'))) ? '' : 'd-none' }}">
                    <label class="form-group-label" for="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">
                        {{ trans('update.choose_file') }}
                        <span class="sr-only">Drag and drop or click to upload</span>
                    </label>

                    {{-- Drag and Drop Zone --}}
                    <div class="js-file-drag-drop-zone file-drag-drop-zone text-center" 
                         role="region" 
                         aria-label="File upload area"
                         tabindex="0"
                         data-file-input-id="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">
                        <div class="js-drag-drop-content">
                            <div style="margin-bottom: 12px;">
                                <x-iconsax-lin-export class="icons text-gray-400" width="48px" height="48px" aria-hidden="true"/>
                            </div>
                            <p class="font-14 text-gray-600" style="margin-bottom: 4px;">
                                <span class="js-drag-drop-text">Drag and drop files here</span>
                                <span class="sr-only">or</span>
                            </p>
                            <p class="font-12 text-gray-500" style="margin-bottom: 0;">
                                or click to browse
                            </p>
                            <p class="font-12 text-gray-400" style="margin-top: 8px; margin-bottom: 0;">
                                Supported formats: MP4, AVI, MKV, MOV, PDF, DOC, DOCX
                            </p>
                        </div>
                        <div class="js-drag-drop-overlay d-none position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 123, 255, 0.1); border: 2px solid #007bff; border-radius: 12px;">
                            <div class="text-center">
                                <x-iconsax-lin-export class="icons text-primary" width="48px" height="48px" aria-hidden="true" style="margin-bottom: 8px;"/>
                                <p class="font-14 font-weight-bold text-primary" style="margin-bottom: 0;">Drop file here</p>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden File Input --}}
                    <div class="custom-file bg-white" style="position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; clip: rect(0, 0, 0, 0);">
                        <input type="file" 
                               name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" 
                               class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" 
                               data-upload-name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_upload]" 
                               id="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}"
                               aria-label="{{ trans('update.choose_file') }}"
                               aria-describedby="file_upload_help_{{ !empty($file) ? $file->id : 'record' }}">
                        <span class="custom-file-text">{{ (!empty($file) and !empty($file->file)) ? getFileNameByPath($file->file) : '' }}</span>
                        <label class="custom-file-label" for="file_upload_input_{{ !empty($file) ? $file->id : 'record' }}">{{ trans('update.browse') }}</label>
                    </div>

                    {{-- Selected File Display --}}
                    <div class="js-selected-file-display d-none mt-12 p-12 bg-gray-50 rounded-8">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center flex-1">
                                <x-iconsax-lin-document class="icons text-primary mr-8" width="20px" height="20px" aria-hidden="true"/>
                                <div class="flex-1">
                                    <p class="font-14 font-weight-bold text-dark mb-2 js-selected-file-name"></p>
                                    <p class="font-12 text-gray-500 mb-0 js-selected-file-size"></p>
                                </div>
                            </div>
                            <button type="button" 
                                    class="js-remove-file btn btn-sm btn-transparent text-danger p-4" 
                                    aria-label="Remove file">
                                <x-iconsax-lin-trash class="icons" width="18px" height="18px" aria-hidden="true"/>
                            </button>
                        </div>
                    </div>

                    <div id="file_upload_help_{{ !empty($file) ? $file->id : 'record' }}" class="font-12 text-gray-500 mt-8">
                        Max file size: 2GB
                    </div>

                    <div class="invalid-feedback d-block"></div>
                </div>

                <div class="row js-file-type-volume d-none">
                    <div class="col-6 js-file-type-field">
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('webinars.file_type') }}</label>

                            <select name="ajax[{{ !empty($file) ? $file->id : 'new' }}][file_type]" class="js-ajax-file_type form-control">
                                <option value="">{{ trans('webinars.select_file_type') }}</option>

                                @foreach(\App\Models\File::$fileTypes as $fileType)
                                    <option value="{{ $fileType }}" @if(!empty($file) and $file->file_type == $fileType) selected @endif>{{ trans('update.file_type_'.$fileType) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="col-6 js-file-volume-field">
                        <div class="form-group">
                            <label class="form-group-label">{{ trans('webinars.file_volume') }}</label>
                            <span class="has-translation bg-gray-100 font-14 text-gray-500 w-auto px-4">{{ trans('update.mb') }}</span>
                            <input type="number" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][volume]" value="{{ (!empty($file)) ? $file->volume : '' }}" class="js-ajax-volume form-control" placeholder="{{ trans('webinars.online_file_volume') }}"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-group-label">{{ trans('public.description') }}</label>
                    <textarea name="ajax[{{ !empty($file) ? $file->id : 'new' }}][description]" class="js-ajax-description form-control" rows="6">{{ !empty($file) ? $file->description : '' }}</textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="js-online_viewer-input">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][online_viewer]" class="custom-control-input" {{ (!empty($file) and $file->online_viewer) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="online_viewerSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.online_viewer') }}</label>
                        </div>
                    </div>
                </div>

                <div class="js-downloadable-input">
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="downloadableSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][downloadable]" class="custom-control-input" {{ (empty($file) or $file->downloadable) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="downloadableSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="downloadableSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('home.downloadable') }}</label>
                        </div>
                    </div>
                </div>

                <div class="form-group d-flex align-items-center">
                    <div class="custom-switch mr-8">
                        <input id="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][status]" class="custom-control-input" {{ (empty($file) or $file->status == \App\Models\File::$Active) ? 'checked' : ''  }}>
                        <label class="custom-control-label cursor-pointer" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                    </div>

                    <div class="">
                        <label class="cursor-pointer" for="fileStatusSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('public.active') }}</label>
                    </div>
                </div>


                @if(getFeaturesSettings('sequence_content_status'))
                    <div class="form-group d-flex align-items-center">
                        <div class="custom-switch mr-8">
                            <input id="fileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][sequence_content]" class="js-sequence-content-switch custom-control-input" {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? 'checked' : ''  }}>
                            <label class="custom-control-label cursor-pointer" for="fileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                        </div>

                        <div class="">
                            <label class="cursor-pointer" for="fileSequenceContentSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.sequence_content') }}</label>
                        </div>
                    </div>

                    <div class="js-sequence-content-inputs pl-4 {{ (!empty($file) and ($file->check_previous_parts or !empty($file->access_after_day))) ? '' : 'd-none' }}">
                        <div class="form-group d-flex align-items-center">
                            <div class="custom-switch mr-8">
                                <input id="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}" type="checkbox" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][check_previous_parts]" class="custom-control-input" {{ (empty($file) or $file->check_previous_parts) ? 'checked' : ''  }}>
                                <label class="custom-control-label cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}"></label>
                            </div>

                            <div class="">
                                <label class="cursor-pointer" for="checkPreviousPartsSwitch{{ !empty($file) ? $file->id : '_record' }}">{{ trans('update.check_previous_parts') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-group-label">{{ trans('update.access_after_day') }}</label>
                            <input type="number" name="ajax[{{ !empty($file) ? $file->id : 'new' }}][access_after_day]" value="{{ (!empty($file)) ? $file->access_after_day : '' }}" class="js-ajax-access_after_day form-control" placeholder="{{ trans('update.access_after_day_placeholder') }}"/>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                @endif

                <div class="progress d-none">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div>
                </div>

                <div class="mt-20 d-flex align-items-center justify-content-end">
                    <button type="button" class="js-save-course-content btn btn-lg btn-primary">{{ trans('public.save') }}</button>

                    @if(empty($file))
                        <button type="button" class="btn btn-lg btn-danger ml-12 cancel-accordion">{{ trans('public.close') }}</button>
                    @endif
                </div>
            </div>
        </div>
    </li>
@endif


@push('styles_top')
    <style>
        .file-drag-drop-zone {
            transition: all 0.3s ease;
            cursor: pointer;
            background-color: #f8f9fa;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 12px;
        }
        
        .file-drag-drop-zone:hover {
            background-color: #e9ecef;
            border-color: #007bff !important;
        }
        
        .file-drag-drop-zone:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }
        
        .file-drag-drop-zone.drag-over {
            background-color: #e7f3ff;
            border-color: #007bff !important;
        }
        
        .file-drag-drop-zone .js-drag-drop-overlay {
            transition: opacity 0.3s ease;
            pointer-events: none; /* Allow clicks to pass through overlay */
        }
        
        .js-selected-file-display {
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Accessibility: Ensure focus indicators are visible */
        .file-drag-drop-zone:focus-visible {
            outline: 3px solid #007bff;
            outline-offset: 2px;
        }
        
        /* Screen reader only text */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }
    </style>
@endpush

@push('scripts_bottom')
    <script>
        var filePathPlaceHolderBySource = {
            upload: '{{ trans('update.file_source_upload_placeholder') }}',
            youtube: '{{ trans('update.file_source_youtube_placeholder') }}',
            vimeo: '{{ trans('update.file_source_vimeo_placeholder') }}',
            external_link: '{{ trans('update.file_source_external_link_placeholder') }}',
            google_drive: '{{ trans('update.file_source_google_drive_placeholder') }}',
            dropbox: '{{ trans('update.file_source_dropbox_placeholder') }}',
            iframe: '{{ trans('update.file_source_iframe_placeholder') }}',
            s3: '{{ trans('update.file_source_s3_placeholder') }}',
            r2: '{{ trans('update.file_source_r2_placeholder') ?? trans('update.file_source_s3_placeholder') }}',
        }
    </script>
    <script src="/assets/design_1/js/panel/file-drag-drop.js"></script>
@endpush
