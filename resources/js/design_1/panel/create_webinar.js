(function ($) {
    "use strict"

    // =========
    // Actions
    // ======

    $('body').on('click', '#sendForReview', function (e) {
        $(this).addClass('loadingbar').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(0);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#saveAsDraft', function (e) {
        $(this).addClass('loadingbar').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#getNextStep', function (e) {
        $(this).addClass('loadingbar').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#getNext').val(1);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '.js-get-next-step', function (e) {
        e.preventDefault();

        if (!$(this).hasClass('active')) {
            $(this).addClass('loadingbar').prop('disabled', true);
            const step = $(this).attr('data-step');

            $('#getStep').val(step);
            $('#forDraft').val(1);
            $('#getNext').val(1);
            $('#webinarForm').trigger('submit');
        }
    });

    // =========
    // Category Filters
    // ======

    function handleGetFiltersTitleFromTranslations(translations, defaultLocale) {
        let title = null;

        if (Object.keys(translations).length) {
            Object.keys(translations).forEach(key => {
                const translation = translations[key];

                if (translation.locale === defaultLocale) {
                    title = translation.title
                }
            })

            if (!title) {
                title = translations[0].title
            }
        }

        return title;
    }

    function handleFilterCardHtml(filterTitle, options) {
        let html = '';

        if (options.length) {
            html += `<div class="col-12 col-md-3 mt-16">
                        <div class="create-course-filter-card bg-white p-16 rounded-12 border-gray-200">
                            <h5 class="font-14 font-weight-bold mb-16">${filterTitle}</h5>`;

            Object.keys(options).forEach((index) => {
                let option = options[index];
                let optionTitle = option.title;

                if (!optionTitle && option.translations) {
                    optionTitle = handleGetFiltersTitleFromTranslations(option.translations, defaultLocale);
                }

                html += `<div class="custom-control custom-checkbox ${(index === 0) ? '' : 'mt-12'}">
                            <input type="checkbox" name="filters[]" value="${option.id}" id="filterOptions${option.id}" class="custom-control-input">
                            <label class="custom-control__label cursor-pointer" for="filterOptions${option.id}">${optionTitle}</label>
                        </div>`
            })

            html += `</div>
                    </div>`;
        }

        return html;
    }


    function handleCategoryFilters(path) {
        const $categoriesFiltersContainer = $('#categoriesFiltersContainer');
        const $categoriesFiltersCard = $('#categoriesFiltersCard');

        const loadingHtml = `<div class="js-loading-card d-flex align-items-center justify-content-center w-100 my-40">
                    <img src="/assets/design_1/img/loading.svg" width="56" height="56">
                </div>`;

        $categoriesFiltersContainer.removeClass('d-none');
        $categoriesFiltersCard.html(loadingHtml);

        $.get(path, function (result) {

            if (result && typeof result.filters !== "undefined" && result.filters.length) {
                const defaultLocale = result.defaultLocale;
                let html = '';

                Object.keys(result.filters).forEach(key => {
                    let filter = result.filters[key];
                    let options = [];

                    if (filter.options.length) {
                        options = filter.options;
                    }

                    let filterTitle = filter.title;

                    if (!filterTitle && filter.translations) {
                        filterTitle = handleGetFiltersTitleFromTranslations(filter.translations, defaultLocale);
                    }

                    html += handleFilterCardHtml(filterTitle, options);
                });

                $categoriesFiltersCard.html(html);
            } else {
                $categoriesFiltersContainer.addClass('d-none');
                $categoriesFiltersCard.html('');
            }
        })
    }

    $('body').on('change', '#categories', function (e) {
        e.preventDefault();
        let category_id = this.value;

        const path = `/panel/filters/get-by-category-id/${category_id}`

        handleCategoryFilters(path);
    });

    $('body').on('change', '#productCategories', function (e) {
        e.preventDefault();
        let category_id = this.value;

        const path = `/panel/store/products/filters/get-by-category-id/${category_id}`

        handleCategoryFilters(path);
    });


    // Options

    $('body').on('change', '#partnerInstructorSwitch', function (e) {
        let isChecked = this.checked;

        if (isChecked) {
            $('#partnerInstructorInput').removeClass('d-none');
        } else {
            $('#partnerInstructorInput').addClass('d-none');
        }
    });


    $('body').on('click', '.js-save-price_plan', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.js-price_plan-form');

        handleSendRequestItemForm(form, $this);
    });

    // =========
    // Chapters
    // ======

    $('body').on('click', '.js-add-chapter', function (e) {
        e.preventDefault();

        const $this = $(this);
        const chapterId = $this.attr("data-chapter");
        const webinarId = $this.attr('data-webinar-id');
        let path = `/panel/chapters/get-form`;
        let modalTitle = newChapterLang;

        if (typeof chapterId !== "undefined" && chapterId) {
            path = `/panel/chapters/${chapterId}/edit`;
            modalTitle = editChapterLang;
        }

        handleBasicModal(path, modalTitle, function (result, $body, $footer) {

            $body.find('.js-chapter-webinar-id').val(webinarId);

            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-chapter btn btn-sm btn-primary">${saveLang}</a>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '40rem')
    })

    $('body').on('click', '.js-save-chapter', function (e) {
        e.preventDefault();

        const $this = $(this);
        let $form = $this.closest('.js-custom-modal').find('.js-content-form');

        handleSendRequestItemForm($form, $this)
    })


    $('body').on('click', '.js-change-content-chapter', function (e) {
        e.preventDefault();

        const $this = $(this);
        const itemId = $this.attr('data-item-id');
        const itemType = $this.attr('data-item-type');
        const chapterId = $this.attr('data-chapter-id');

        const random = randomString();

        let html = $('#changeChapterModalHtml').html();
        html = html.replace(/record/g, random);

        const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                <button type="button" class="js-save-change-chapter btn btn-sm btn-primary">${saveLang}</a>
                <button type="button" class="close-swl btn btn-sm btn-danger ml-8">${closeLang}</button>
            </div>`;


        Swal.fire({
            html: makeModalHtml(changeChapterLang, closeIcon, html, footerHtml),
            showCancelButton: false,
            showConfirmButton: false,
            width: '40rem',
            didOpen: function () {
                const $body = $('.js-custom-modal');
                const $footer = $('.custom-modal-footer');

                $body.find(".js-item-id").val(itemId)
                $body.find(".js-item-type").val(itemType)
                $body.find('.js-ajax-chapter_id').val(chapterId).change();
            }
        });

    })

    $('body').on('click', '.js-save-change-chapter', function (e) {
        e.preventDefault();

        const $this = $(this);
        let $form = $this.closest('.js-custom-modal').find('.change-chapter-form');

        handleSendRequestItemForm($form, $this)
    })


    // Handle dropdown toggle for add content button
    $('body').on('click', '.js-add-content-dropdown-toggle', function (e) {
        e.stopPropagation();
        const $this = $(this);
        const $dropdown = $this.closest('.actions-dropdown').find('.actions-dropdown__dropdown-menu');
        const isExpanded = $this.attr('aria-expanded') === 'true';
        
        $this.attr('aria-expanded', !isExpanded);
        $dropdown.toggleClass('show');
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.actions-dropdown').length) {
            $('.js-add-content-dropdown-toggle').attr('aria-expanded', 'false');
            $('.actions-dropdown__dropdown-menu').removeClass('show');
        }
    });

    $('body').on('click', '.js-add-course-content-btn, .add-new-interactive-file-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Close the dropdown menu
        $(this).closest('.actions-dropdown').find('.js-add-content-dropdown-toggle').attr('aria-expanded', 'false');
        $(this).closest('.actions-dropdown__dropdown-menu').removeClass('show');
        
        const $this = $(this);
        const type = $this.attr('data-type');
        const chapterId = $this.attr('data-chapter');
        const appendTargetElId = $this.attr('data-target-el-id');

        let contentTagId = '#chapterContentAccordion' + chapterId;

        if (typeof appendTargetElId !== "undefined" && appendTargetElId) {
            contentTagId = `#${appendTargetElId}`;
        }

        const key = randomString();
        let $body = null;

        switch (type) {
            case 'file':
                $body = $('#newFileForm');

                break;

            case 'new_interactive_file':
                $body = $('#newInteractiveFileForm');

                break;

            case 'session':
                $body = $('#newSessionForm');

                break;

            case 'text_lesson':
                $body = $('#newTextLessonForm');

                break;

            case 'assignment':
                $body = $('#newAssignmentForm');

                break;

            case 'quiz':
                $body = $('#newQuizForm');

                break;

        }


        if ($body) {
            $body.find('.chapter-input').val(chapterId);
            let html = $body.html();

            html = html.replace(/record/g, key);
            
            // Fix data-parent attributes to use the correct chapter ID
            // Replace any data-parent that has 'record' or empty chapter ID
            html = html.replace(/data-parent="#chapterContentAccordionrecord"/g, 'data-parent="#chapterContentAccordion' + chapterId + '"');
            html = html.replace(/data-parent="#chapterContentAccordion"/g, 'data-parent="#chapterContentAccordion' + chapterId + '"');

            if (type === "text_lesson") {
                html = html.replaceAll('attachments-select2', 'attachments-select2-' + key);
                html = html.replaceAll('js-content-summernote-input', 'js-content-summernote-' + key);
                html = html.replaceAll('js-hidden-content-summernote', 'js-hidden-content-summernote-' + key);
            }

            const $contentTagId = $(contentTagId);
            
            // Remove empty state if exists
            $contentTagId.find('.d-flex-center.flex-column').remove();
            
            // Ensure we have a ul for draggable content
            let $contentList = $contentTagId.find('ul.draggable-content-lists');
            if (!$contentList.length) {
                $contentList = $('<ul class="draggable-content-lists draggable-lists-chapter-' + chapterId + '" data-path="/panel/webinar_chapters/items/orders" data-drag-class="draggable-lists-chapter-' + chapterId + '"></ul>');
                $contentTagId.append($contentList);
            }
            
            // Wrap html in li if it's not already
            if (!html.trim().startsWith('<li')) {
                html = '<li>' + html + '</li>';
            }
            
            $contentList.prepend(html);
            
            // Add fade-in animation
            const $newItem = $contentList.find('li').first();
            $newItem.addClass('fade-in');
            
            // Fix all data-parent attributes in the newly added content
            $newItem.find('[data-parent]').each(function() {
                const $this = $(this);
                let parentValue = $this.attr('data-parent');
                // Replace 'record' or empty with actual chapter ID
                if (parentValue && (parentValue.includes('record') || !parentValue.includes(chapterId))) {
                    parentValue = '#chapterContentAccordion' + chapterId;
                    $this.attr('data-parent', parentValue);
                }
            });
            
            // Prevent Bootstrap from auto-initializing collapse on new content
            // Remove data-toggle="collapse" temporarily, fix data-parent, then re-add
            $newItem.find('[data-toggle="collapse"]').each(function() {
                const $this = $(this);
                const target = $this.attr('href');
                const parentValue = '#chapterContentAccordion' + chapterId;
                
                // Ensure data-parent is correct
                $this.attr('data-parent', parentValue);
                
                // Verify parent exists before allowing collapse
                if ($(parentValue).length === 0) {
                    console.warn('Parent accordion not found:', parentValue);
                    $this.removeAttr('data-toggle');
                }
            });
            
            // Re-initialize accordion collapse handler for new content
            if (typeof window.handleAccordionCollapse === 'function') {
                setTimeout(function() {
                    window.handleAccordionCollapse();
                }, 100);
            }

            // Initialize drag and drop for newly added file upload zones
            if (type === "file" || type === "new_interactive_file") {
                // Wait a bit for DOM to be ready
                setTimeout(function() {
                    const $newForm = $contentList.find('.js-content-form').first();
                    const $dragDropZone = $newForm.find('.js-file-drag-drop-zone');
                    
                    if ($dragDropZone.length) {
                        // Check if FileDragDrop class exists
                        if (typeof window.FileDragDrop !== 'undefined') {
                            $dragDropZone.each(function() {
                                if (!$(this).data('drag-drop-initialized')) {
                                    try {
                                        new window.FileDragDrop($(this));
                                        $(this).data('drag-drop-initialized', true);
                                    } catch(e) {
                                        console.warn('Failed to initialize drag and drop:', e);
                                    }
                                }
                            });
                        } else {
                            // Fallback: initialize manually if FileDragDrop not available
                            $dragDropZone.each(function() {
                                const $zone = $(this);
                                const fileInputId = $zone.data('file-input-id');
                                const $fileInput = $('#' + fileInputId);
                                
                                if ($fileInput.length) {
                                    // Basic click handler
                                    $zone.on('click', function(e) {
                                        if (e.target === $zone[0] || $(e.target).closest('.js-drag-drop-content').length) {
                                            e.preventDefault();
                                            $fileInput.trigger('click');
                                        }
                                    });
                                    
                                    // Basic drag and drop
                                    $zone.on('dragover', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        $zone.addClass('drag-over');
                                        $zone.find('.js-drag-drop-overlay').removeClass('d-none');
                                    });
                                    
                                    $zone.on('dragleave', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        if (!$zone[0].contains(e.relatedTarget)) {
                                            $zone.removeClass('drag-over');
                                            $zone.find('.js-drag-drop-overlay').addClass('d-none');
                                        }
                                    });
                                    
                                    $zone.on('drop', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        $zone.removeClass('drag-over');
                                        $zone.find('.js-drag-drop-overlay').addClass('d-none');
                                        
                                        const files = e.originalEvent.dataTransfer?.files;
                                        if (files && files.length > 0) {
                                            const dataTransfer = new DataTransfer();
                                            dataTransfer.items.add(files[0]);
                                            $fileInput[0].files = dataTransfer.files;
                                            $fileInput.trigger('change');
                                        }
                                    });
                                    
                                    $(this).data('drag-drop-initialized', true);
                                }
                            });
                        }
                    }
                    
                    // Initialize file storage type handler for the new form
                    const $storageSelect = $newForm.find('.js-file-storage');
                    if ($storageSelect.length) {
                        // Get the selected storage value (default to 'r2' if not set)
                        let storageValue = $storageSelect.val();
                        if (!storageValue || storageValue === '') {
                            storageValue = 'r2';
                            $storageSelect.val('r2');
                        }
                        
                        const $fileTypeSelect = $newForm.find('.js-ajax-file_type');
                        const fileType = $fileTypeSelect.length ? $fileTypeSelect.val() : null;
                        
                        // Find the form container (could be .file-form or .js-content-form)
                        const $formContainer = $newForm.closest('.file-form, .js-content-form');
                        
                        // Ensure upload input is visible for new files (it should be by default from Blade)
                        const $uploadInput = $newForm.find('.js-file-upload-input');
                        if ($uploadInput.hasClass('d-none') && ['upload', 's3', 'r2'].includes(storageValue)) {
                            $uploadInput.removeClass('d-none');
                        }
                        
                        // Manually call handleShowFileInputsBySource to ensure correct visibility
                        if (typeof handleShowFileInputsBySource === 'function' && $formContainer.length) {
                            handleShowFileInputsBySource($formContainer, storageValue, fileType);
                        }
                        
                        // Trigger change event to ensure all handlers run and visibility is set correctly
                        $storageSelect.trigger('change');
                    }
                }, 100);
            }

            if (type === "text_lesson") {
                $('.attachments-select2-' + key).select2({
                    multiple: true,
                    width: '100%',
                });

                if (jQuery().summernote) {
                    makeSummernote($('.js-content-summernote-' + key), 400, function (contents, $editable) {
                        $('.js-hidden-content-summernote-' + key).val(contents);
                    })
                }
            }

            const $selectItems = $contentTagId.find('.js-make-select2-item');
            if ($selectItems.length) {
                for (const selectItem of $selectItems) {
                    handleSelect2($(selectItem))
                }
            }

            resetDatePickers();
            
            // Scroll to the newly added content
            const $newlyAdded = $contentList.find('li').first();
            if ($newlyAdded.length) {
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: $newlyAdded.offset().top - 100
                    }, 500);
                }, 100);
            }
        }

    });

    $('body').on('change', '.js-api-input', function (e) {
        e.preventDefault();

        const sessionForm = $(this).closest('.session-form');
        const value = this.value;

        sessionForm.find('.js-zoom-not-complete-alert').addClass('d-none');
        sessionForm.find('.js-agora-chat-and-rec').addClass('d-none');

        if (value === 'big_blue_button') {
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-api-secret').removeClass('d-none');
            sessionForm.find('.js-moderator-secret').removeClass('d-none');
        } else if (value === 'zoom') {
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-api-secret').addClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');

            if (hasZoomApiToken && hasZoomApiToken !== 'true') {
                sessionForm.find('.js-zoom-not-complete-alert').removeClass('d-none');
            }
        } else if (value === 'agora') {
            sessionForm.find('.js-agora-chat-and-rec').removeClass('d-none');
            sessionForm.find('.js-api-secret').addClass('d-none');
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');
        } else if (value === 'jitsi') {
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-api-secret').addClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');
        } else {
            sessionForm.find('.js-local-link').removeClass('d-none');
            sessionForm.find('.js-api-secret').removeClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');
        }
    });

    $('body').on('change', '.js-sequence-content-switch', function () {
        const parent = $(this).closest('.accordion');

        const sequenceContentInputs = parent.find('.js-sequence-content-inputs');
        sequenceContentInputs.addClass('d-none');

        if (this.checked) {
            sequenceContentInputs.removeClass('d-none');
        }
    });

    $('body').on('click', '.js-save-course-content', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-content-form');

        handleSendRequestItemForm($form, $this)
    });

    // =========
    // Files
    // ======

    function handleShowFileInputsBySource($form, source, fileType) {
        // Default to 'r2' if source is not provided or invalid
        if (!source || source === '') {
            source = 'r2';
        }

        const $fileTypeVolumeInputs = $form.find('.js-file-type-volume');
        const $volumeInputs = $form.find('.js-file-volume-field');
        const $typeInputs = $form.find('.js-file-type-field');
        const $downloadableInput = $form.find('.js-downloadable-input');
        const $onlineViewerInput = $form.find('.js-online_viewer-input');

        const $fileUrlInputCard = $form.find('.js-file-url-input');
        const $fileUploadInputCard = $form.find('.js-file-upload-input');
        const $fileUploadInputField = $fileUploadInputCard.find('input');

        const $secureHostUploadTypeField = $form.find('.js-secure-host-upload-type-field');

        // Don't hide by default - the switch statement will handle visibility
        // This ensures new files stay visible until source is determined

        $volumeInputs.addClass('d-none');
        $typeInputs.removeClass('d-none'); // parent is hidden or visible
        $secureHostUploadTypeField.addClass('d-none');

        $fileUploadInputField.find('input').removeAttr("accept")

        switch (source) {
            case 'youtube':
            case 'vimeo':
            case 'iframe':
                $fileTypeVolumeInputs.addClass('d-none');
                $fileTypeVolumeInputs.find('select').val('')

                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');

                $onlineViewerInput.find('input').prop('checked', false);
                $onlineViewerInput.addClass('d-none');

                break;

            case 'external_link':
            case 's3':
            case 'r2':
                $fileTypeVolumeInputs.removeClass('d-none');

                if (fileType && fileType === 'video') {
                    $downloadableInput.removeClass('d-none');
                } else {
                    $downloadableInput.find('input').prop('checked', false);
                    $downloadableInput.addClass('d-none');
                }

                if (source === 'external_link') {
                    $volumeInputs.removeClass('d-none');
                } else if (source === 's3' || source === 'r2') {
                    $fileUrlInputCard.addClass('d-none');
                    $fileUploadInputCard.removeClass('d-none');
                }

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }

                break;
            case 'secure_host':
                $fileTypeVolumeInputs.addClass('d-none');
                $fileTypeVolumeInputs.find('select').val('')

                $fileUrlInputCard.addClass('d-none');
                $fileUploadInputCard.removeClass('d-none');

                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');
                $onlineViewerInput.addClass('d-none');
                $secureHostUploadTypeField.removeClass('d-none');

                $fileUploadInputField.find('input').attr('accept', "video/mp4,video/x-m4v,video/*");
                break;
            case 'google_drive':
                $fileTypeVolumeInputs.removeClass('d-none');
                $volumeInputs.removeClass('d-none');
                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }

                break;

            case 'upload':
                $fileTypeVolumeInputs.removeClass('d-none');
                $downloadableInput.removeClass('d-none');

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }

                $fileUrlInputCard.addClass('d-none');
                $fileUploadInputCard.removeClass('d-none');
                break;
        }

        if (fileType && (fileType === 'image' || fileType === 'document' || fileType === 'powerpoint' || fileType === 'sound' || fileType === 'archive' || fileType === 'project')) {
            $downloadableInput.find('input').prop('checked', true);
            $downloadableInput.addClass('d-none');
        }

        if (filePathPlaceHolderBySource) {
            $fileUrlInputCard.find('input').attr('placeholder', filePathPlaceHolderBySource[source]);
        }

    }

    function handleSecureHostUploadType($form, value, isOnChangeByUser = false) {
        const $fileUrlInputCard = $form.find('.js-file-url-input');
        const $fileUploadInputCard = $form.find('.js-file-upload-input');
        const $fileTypeVolumeInputs = $form.find('.js-file-type-volume');
        const $volumeInputs = $form.find('.js-file-volume-field');
        const $typeInputs = $form.find('.js-file-type-field');

        if (isOnChangeByUser) {
            $typeInputs.addClass('d-none')
        }

        if (value === "manual") {
            if (isOnChangeByUser) {
                $fileTypeVolumeInputs.removeClass('d-none')
                $volumeInputs.removeClass('d-none')
            }

            $fileUrlInputCard.removeClass('d-none')
            $fileUploadInputCard.addClass('d-none')
        } else {
            if (isOnChangeByUser) {
                $fileTypeVolumeInputs.addClass('d-none')
                $volumeInputs.addClass('d-none')
            }

            $fileUrlInputCard.addClass('d-none')
            $fileUploadInputCard.removeClass('d-none')
        }
    }

    $('body').on('change', '.js-file-storage', function (e) {
        e.preventDefault();

        const value = $(this).val();
        // Find the form container (could be .file-form or .js-content-form)
        const formGroup = $(this).closest('.file-form, .js-content-form');
        const fileType = formGroup.find('.js-ajax-file_type').val();

        if (formGroup.length && typeof handleShowFileInputsBySource === 'function') {
            handleShowFileInputsBySource(formGroup, value, fileType);
        }
    });

    $('body').on('change', '.js-ajax-file_type', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const formGroup = $(this).closest('.file-form');
        const source = formGroup.find('.js-file-storage').val();

        handleShowFileInputsBySource(formGroup, source, value);
    });

    $('body').on('change', '.js-secure-host-upload-type-field input', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const $form = $(this).closest('.file-form');

        handleSecureHostUploadType($form, value, true)
    })

    $(document).ready(function () {
        const $fileForms = $('.file-form');

        if ($fileForms && $fileForms.length) {
            $fileForms.each(key => {
                if ($fileForms[key]) {
                    const $form = $($fileForms[key]);

                    const source = $form.find('.js-file-storage').val();
                    const fileType = $form.find('.js-ajax-file_type').val();

                    handleShowFileInputsBySource($form, source, fileType);

                    const secureHostType = $form.find('.js-secure-host-upload-type-field input:checked').val();

                    if (secureHostType) {
                        handleSecureHostUploadType($form, secureHostType)
                    }
                }
            });
        }


        let summernoteTarget = $('.accordion .js-content-summernote');
        if (summernoteTarget.length) {
            for (const summernoteTargetElement of summernoteTarget) {
                const $summernoteTargetEl = $(summernoteTargetElement);

                makeSummernote($summernoteTargetEl, 400, function (contents, $editable) {
                    $summernoteTargetEl.parent().find('.js-hidden-content-summernote').val(contents);
                })
            }
        }

    });


    $('body').on('change', '.js-interactive-type', function () {
        const fileForm = $(this).closest('.file-form');

        const $fileName = fileForm.find('.js-interactive-file-name-input');
        $fileName.addClass('d-none');

        if ($(this).val() === 'custom') {
            $fileName.removeClass('d-none');
        }

    });

    $('body').on('click', '.js-assignment-attachments-add-btn', function (e) {
        e.preventDefault();
        const $container = $(this).closest('.js-assignment-attachments').find(".js-assignment-attachments-items");
        const inputKey = $(this).attr("data-input-key");
        const randomKey = randomString();

        const html = `<div class="js-ajax-attachments position-relative mt-12">
                    <div class="p-16 border-gray-200 rounded-8">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group mb-0">
                                    <label class="form-group-label bg-white">${titleLang}</label>
                                    <input type="text" name="ajax[${inputKey}][attachments][${randomKey}][title]" class="form-control bg-white" placeholder=""/>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6 mt-20 mt-lg-0">
                                <div class="form-group mb-0">
                                    <label class="form-group-label bg-white">${chooseFileLang}</label>

                                    <div class="custom-file bg-white">
                                        <input type="file" name="ajax[${inputKey}][attachments][${randomKey}][attach]" class="js-ajax-upload-file-input js-ajax-file_upload custom-file-input" data-upload-name="ajax[${inputKey}][attachments][${randomKey}][attach]" id="attachments_assignment_${randomKey}">
                                        <span class="custom-file-text"></span>
                                        <label class="custom-file-label" for="attachments_assignment_${randomKey}">${browseLang}</label>
                                    </div>

                                    <div class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-16">
                            <div class="js-assignment-attachments-remove-btn btn btn-danger btn-lg">${deleteLang}</div>
                        </div>
                    </div>
                </div>`;

        $container.append(html)

    });

    $('body').on('click', '.js-assignment-attachments-remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.js-ajax-attachments').remove();
    });

})(jQuery)
