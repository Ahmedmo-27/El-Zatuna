(function ($) {
    "use strict"

    function isMp4File(file) {
        if (!file) return true;
        const name = (file.name || '').toLowerCase();
        const type = (file.type || '').toLowerCase();
        return name.endsWith('.mp4') && type === 'video/mp4';
    }

    function showMp4OnlyMessage() {
        const msg = 'Please convert the file to mp4 before uploading';

        if (typeof showToast === 'function') {
            showToast('error', msg);
            return;
        }

        alert(msg);
    }

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
        if ($(this).hasClass('create-course-nav-btn--disabled') || !$(this).hasClass('cursor-pointer')) {
            e.preventDefault();
            return;
        }

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

    // Panel category input (same UX as occupations: search + add) – single selection, triggers #categories change for filters
    function initPanelCategoryInput() {
        const $wrapper = $('.js-panel-category-wrapper');
        if (!$wrapper.length) return;

        const $input = $wrapper.find('.js-panel-category-input');
        const $dropdown = $wrapper.find('.js-panel-category-dropdown');
        const $results = $wrapper.find('.js-panel-category-results');
        const $addNew = $wrapper.find('.js-panel-category-add-new');
        const $addNewTerm = $wrapper.find('.js-panel-category-add-new-term');
        const $tagContainer = $wrapper.find('.js-panel-category-tag');
        const $hidden = $wrapper.find('.js-panel-category-hidden');
        const $loading = $wrapper.find('.js-panel-category-loading');
        const $error = $wrapper.find('.js-panel-category-error');

        let selected = null;
        let searchTimeout = null;
        let hideDropdownTimeout = null;

        function setLoading(isLoading) {
            if (isLoading) $loading.removeClass('d-none');
            else $loading.addClass('d-none');
        }
        function showError(msg) {
            if (!msg) { $error.addClass('d-none').text(''); return; }
            $error.removeClass('d-none').text(msg);
        }
        function syncHidden() {
            const id = selected ? selected.id : '';
            $hidden.val(id);
            if ($hidden.attr('id') === 'categories') $hidden.trigger('change');
        }
        function renderTag() {
            $tagContainer.empty();
            if (selected) {
                const textEsc = $('<div>').text(selected.text).html();
                const $tag = $('<span class="badge bg-[#F5F9E8] text-[#072923] px-3 py-1 rounded-8 d-inline-flex align-items-center gap-1">' +
                    '<span>' + textEsc + '</span>' +
                    '<button type="button" class="js-panel-category-remove btn btn-link p-0 text-[#072923]/60 hover:text-danger" style="font-size: 14px; line-height: 1;" data-id="' + selected.id + '">&times;</button></span>');
                $tagContainer.append($tag);
            }
        }
        function setSelected(item) {
            selected = item;
            renderTag();
            syncHidden();
        }

        function doSearch(q) {
            showError('');
            if (q && q.length) setLoading(true);
            $addNewTerm.text(q || 'type above first');
            $addNew.removeClass('d-none');
            $.get('/become-instructor/search-subjects', { q: q || '' })
                .done(function (data) {
                    const results = data.results || [];
                    $results.empty();
                    if (!results.length) {
                        if (q) $results.append($('<div class="p-2 text-[#072923]/50">No matching categories. Use "Add new category" below to add "' + $('<div>').text(q).html() + '".</div>'));
                        else $results.append($('<div class="p-2 text-[#072923]/50">No categories found.</div>'));
                    } else {
                        results.forEach(function (item) {
                            const textEsc = $('<div>').text(item.text).html();
                            $results.append($('<div class="js-panel-category-row p-2 rounded-8 cursor-pointer hover:bg-[#F5F9E8]/50" style="min-height: 44px; display: flex; align-items: center;" data-id="' + item.id + '">' + textEsc + '</div>'));
                        });
                    }
                })
                .fail(function () { showError('Could not load categories. Please try again.'); })
                .always(function () { if (q && q.length) setLoading(false); });
        }
        function showDropdown() { $dropdown.removeClass('d-none'); }
        function hideDropdown() {
            if (hideDropdownTimeout) clearTimeout(hideDropdownTimeout);
            hideDropdownTimeout = setTimeout(function () { hideDropdownTimeout = null; $dropdown.addClass('d-none'); }, 280);
        }
        function cancelHide() { if (hideDropdownTimeout) { clearTimeout(hideDropdownTimeout); hideDropdownTimeout = null; } }

        $dropdown.on('mousedown touchstart', function (e) { e.preventDefault(); cancelHide(); });
        $input.on('focus', function () {
            cancelHide();
            if ($dropdown.hasClass('d-none')) { showDropdown(); doSearch($.trim($input.val())); }
        });
        $input.on('input', function () {
            const q = $.trim($input.val());
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () { doSearch(q); showDropdown(); }, 200);
        });
        $input.on('blur', function () { hideDropdown(); });

        $results.on('click', '.js-panel-category-row', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            const text = $(this).text().trim();
            setSelected({ id: id, text: text });
            $input.val('');
            cancelHide();
            hideDropdown();
        });
        $addNew.on('click', function (e) {
            e.preventDefault();
            const term = $.trim($input.val()) || $.trim($addNewTerm.text());
            if (!term || term === 'type above first') return;
            showError('');
            setLoading(true);
            const token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            $.post('/become-instructor/create-subject', { title: term, _token: token })
                .done(function (data) { setSelected({ id: data.id, text: data.text || term }); $input.val(''); doSearch(''); showDropdown(); })
                .fail(function () { showError('Could not create category. Please try again.'); })
                .always(function () { setLoading(false); });
        });
        $tagContainer.on('click', '.js-panel-category-remove', function (e) {
            e.preventDefault();
            setSelected(null);
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.js-panel-category-wrapper').length) hideDropdown();
        });

        try {
            const initial = $wrapper.data('initial') || [];
            if (Array.isArray(initial) && initial.length) setSelected(initial[0]);
        } catch (err) {}
        syncHidden();
    }

    // Enforce mp4-only uploads for course files (UI)
    $('body').on('change', '.js-ajax-file_upload', function () {
        const input = this;
        const file = input.files && input.files.length ? input.files[0] : null;
        if (!file) return;

        if (!isMp4File(file)) {
            input.value = '';
            showMp4OnlyMessage();
        }
    });
    $(document).ready(function () { initPanelCategoryInput(); });


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
                                        if ($zone.closest('.js-file-upload-input').hasClass('js-file-upload-locked')) {
                                            e.preventDefault();
                                            return;
                                        }
                                        if (e.target === $zone[0] || $(e.target).closest('.js-drag-drop-content').length) {
                                            e.preventDefault();
                                            $fileInput.trigger('click');
                                        }
                                    });
                                    
                                    // Basic drag and drop
                                    $zone.on('dragover', function(e) {
                                        e.preventDefault();
                                        e.stopPropagation();
                                        if ($zone.closest('.js-file-upload-input').hasClass('js-file-upload-locked')) {
                                            return;
                                        }
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

                                        if ($zone.closest('.js-file-upload-input').hasClass('js-file-upload-locked')) {
                                            return;
                                        }
                                        
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
                        if ($uploadInput.hasClass('d-none') && ['upload', 'r2'].includes(storageValue)) {
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

    function extractSummernotePlainText(html) {
        if (!html) {
            return '';
        }

        return $('<div>').html(html).text().replace(/\u00a0/g, ' ').trim();
    }

    function syncAndValidateTextLessonContent($form) {
        if (!$form.hasClass('text_lesson-form')) {
            return true;
        }

        const $editor = $form.find('textarea.js-content-summernote, textarea[class*="js-content-summernote-"]').first();
        const $hiddenContent = $form.find('.js-hidden-content-summernote, textarea[class*="js-hidden-content-summernote-"], textarea[name*="[content]"]').first();

        if (!$hiddenContent.length) {
            return true;
        }

        let html = '';

        if ($editor.length && typeof $editor.summernote === 'function' && $editor.next('.note-editor').length) {
            html = $editor.summernote('code') || '';
        } else if ($editor.length) {
            html = $editor.val() || '';
        } else {
            html = $hiddenContent.val() || '';
        }

        $hiddenContent.val(html);

        const hasMedia = /<(img|iframe|video|audio|object|embed)\b/i.test(html);
        const isEmpty = !hasMedia && extractSummernotePlainText(html).length === 0;
        const $contentFormGroup = $hiddenContent.closest('.form-group');
        const $feedback = $contentFormGroup.find('.invalid-feedback').first();

        if (isEmpty) {
            $editor.addClass('is-invalid');
            $hiddenContent.addClass('is-invalid');

            if ($feedback.length) {
                const message = (typeof contentRequiredLang !== 'undefined' && contentRequiredLang) ? contentRequiredLang : 'The content field is required.';
                $feedback.text(message);
            }

            return false;
        }

        $editor.removeClass('is-invalid');
        $hiddenContent.removeClass('is-invalid');

        if ($feedback.length) {
            $feedback.text('');
        }

        return true;
    }

    function courseFileFormShowsFileTypeField($form) {
        const $row = $form.find('.js-file-type-volume');
        const $field = $form.find('.js-file-type-field');
        if (!$row.length || $row.hasClass('d-none')) {
            return false;
        }
        if ($field.length && $field.hasClass('d-none')) {
            return false;
        }
        return true;
    }

    function syncFileUploadInteractionLock($form) {
        const $card = $form.find('.js-file-upload-input').first();
        if (!$card.length || $card.hasClass('d-none')) {
            return;
        }
        const needsFileTypeFirst = courseFileFormShowsFileTypeField($form);
        const hasFileType = (($form.find('.js-ajax-file_type').val() || '').trim().length > 0);
        const locked = needsFileTypeFirst && !hasFileType;
        const $fileInput = $card.find('.js-ajax-upload-file-input');
        const $zone = $card.find('.js-file-drag-drop-zone');
        const lockHint = (typeof webinarFileTypeRequiredLang !== 'undefined' && webinarFileTypeRequiredLang)
            ? webinarFileTypeRequiredLang
            : 'Please select a file type first.';

        if (locked) {
            $card.addClass('js-file-upload-locked');
            if ($fileInput.length) {
                if ($fileInput[0].files && $fileInput[0].files.length > 0) {
                    $fileInput.val('');
                    $card.find('.js-selected-file-display').addClass('d-none');
                    $card.find('.js-selected-file-name').text('');
                    $card.find('.js-selected-file-size').text('');
                    const $customLabel = $fileInput.closest('.custom-file').find('.custom-file-label');
                    if ($customLabel.length) {
                        const def = $customLabel.data('default-label');
                        $customLabel.text(def && String(def).length ? def : 'Browse');
                    }
                }
                $fileInput.prop('disabled', true);
            }
            if ($zone.length) {
                $zone.attr({ tabindex: '-1', 'aria-disabled': 'true', title: lockHint });
            }
        } else {
            $card.removeClass('js-file-upload-locked');
            if ($fileInput.length) {
                $fileInput.prop('disabled', false);
            }
            if ($zone.length) {
                $zone.attr('tabindex', '0');
                $zone.removeAttr('aria-disabled');
                $zone.removeAttr('title');
            }
        }
    }

    function validateCourseFileTypeSelected($form) {
        if (!courseFileFormShowsFileTypeField($form)) {
            return true;
        }
        const fileType = ($form.find('.js-ajax-file_type').val() || '').trim();
        if (fileType) {
            const $select = $form.find('.js-ajax-file_type');
            $select.removeClass('is-invalid');
            $select.closest('.form-group').find('.invalid-feedback').first().text('');
            return true;
        }
        const msg = (typeof webinarFileTypeRequiredLang !== 'undefined' && webinarFileTypeRequiredLang)
            ? webinarFileTypeRequiredLang
            : 'Please select a file type before uploading or saving.';
        const $select = $form.find('.js-ajax-file_type');
        $select.addClass('is-invalid');
        const $feedback = $select.closest('.form-group').find('.invalid-feedback').first();
        if ($feedback.length) {
            $feedback.text(msg);
        }
        if (typeof showToast === 'function') {
            showToast('error', (typeof webinarFileTypeLabelLang !== 'undefined' && webinarFileTypeLabelLang) ? webinarFileTypeLabelLang : 'File type', msg);
        } else {
            alert(msg);
        }
        return false;
    }

    $('body').on('click', '.js-save-course-content', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-content-form');

        if (!syncAndValidateTextLessonContent($form)) {
            return;
        }

        if ($form.hasClass('file-form') && !validateCourseFileTypeSelected($form)) {
            return;
        }

        // For file forms with R2/upload: require a file to be selected before submit
        if ($form.hasClass('file-form')) {
            const storage = $form.find('.js-file-storage').val() || $form.find('select[name*="[storage]"]').val() || 'upload';
            if (storage === 'r2' || storage === 'upload') {
                const action = $form.attr('data-action') || '';
                const isPanelFileStore = action.indexOf('/panel/files/store') !== -1;
                const $fileInput = $form.find('.js-ajax-upload-file-input');
                const hasFile = $fileInput.length && $fileInput[0].files && $fileInput[0].files.length > 0;
                // Only require a new file when creating a new course file.
                // When updating an existing section with an already uploaded video/file,
                // allow saving metadata changes without forcing a re-upload.
                if (isPanelFileStore && !hasFile) {
                    showUploadErrorForForm($form, 'File required', 'Please choose a file to upload before saving.');
                    return;
                }
                $form.find('.js-file-upload-input .invalid-feedback').removeClass('d-block').text('');

                // For new course files stored in R2, upload the file directly to R2 from the browser first,
                // then submit only the metadata + R2 path to Laravel. This bypasses App Platform timeouts.
                if (isPanelFileStore) {
                    handleDirectR2UploadAndSubmit($form, $this, storage);
                    return;
                }
            }
        }

        handleSendRequestItemForm($form, $this)
    });

    // =========
    // Files
    // ======

    function showUploadErrorForForm($form, title, message, options) {
        const opts = options || {};
        const $errorTarget = $form.find('.js-file-upload-input .invalid-feedback').first();

        if (typeof showToast === 'function' && !opts.silentToast) {
            showToast('error', title, message);
        } else if (!opts.silentAlert) {
            alert(title + ': ' + message);
        }

        if ($errorTarget.length) {
            $errorTarget.text(message).addClass('d-block');
        }
    }

    function handleDirectR2UploadAndSubmit($form, $button, storage) {
        try {
            const $fileInput = $form.find('.js-ajax-upload-file-input');
            const file = $fileInput.length && $fileInput[0].files && $fileInput[0].files[0] ? $fileInput[0].files[0] : null;

            if (!file) {
                showUploadErrorForForm($form, 'File required', 'Please choose a file to upload before saving.');
                return;
            }

            if (!validateCourseFileTypeSelected($form)) {
                return;
            }

            const maxSizeBytes = 2 * 1024 * 1024 * 1024; // 2GB
            if (file.size > maxSizeBytes) {
                showUploadErrorForForm($form, 'File too large', 'Maximum supported size is 2GB.');
                return;
            }

            const webinarId = $form.find('input[name="ajax[new][webinar_id]"]').val();
            const chapterId = $form.find('input[name="ajax[new][chapter_id]"]').val() || null;
            const token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            if (!webinarId) {
                showUploadErrorForForm($form, 'Missing course', 'Course ID is missing. Please refresh the page and try again.');
                return;
            }

            const $progressContainer = $form.find('.progress').first();
            const $progressBar = $progressContainer.length ? $progressContainer.find('.progress-bar') : null;

            $button.addClass('loadingbar').prop('disabled', true);

            // Step 1: ask Laravel for a pre-signed R2 upload URL
            $.ajax({
                url: '/panel/files/r2/presign',
                type: 'POST',
                data: {
                    webinar_id: webinarId,
                    chapter_id: chapterId,
                    file_name: file.name,
                    file_size: file.size,
                    file_mime: file.type || 'application/octet-stream',
                    _token: token,
                },
                success: function (res) {
                    if (!res || res.code !== 200 || !res.upload_url || !res.path) {
                        $button.removeClass('loadingbar').prop('disabled', false);
                        showUploadErrorForForm($form, 'Upload error', (res && res.msg) ? res.msg : 'Could not prepare upload. Please try again.');
                        return;
                    }

                    const uploadUrl = res.upload_url;
                    const r2Path = res.path;
                    const headers = res.headers || {};

                    if ($progressContainer.length && $progressBar && $progressBar.length) {
                        $progressContainer.removeClass('d-none');
                        $progressBar
                            .css('width', '0%')
                            .attr('aria-valuenow', 0)
                            .removeClass('bg-danger')
                            .addClass('bg-primary');
                    }

                    // Step 2: upload directly to R2 using XHR so we can track progress
                    const xhr = new XMLHttpRequest();
                    xhr.open('PUT', uploadUrl, true);

                    Object.keys(headers).forEach(function (key) {
                        if (headers[key]) {
                            xhr.setRequestHeader(key, headers[key]);
                        }
                    });

                    xhr.upload.onprogress = function (e) {
                        if ($progressContainer.length && $progressBar && $progressBar.length) {
                            let percent = 0;
                            if (e.lengthComputable && e.total > 0) {
                                percent = Math.round((e.loaded / e.total) * 100);
                            } else {
                                percent = parseInt($progressBar.attr('aria-valuenow') || '0', 10) + 1;
                            }
                            if (percent > 99) percent = 99;
                            if (percent < 1) percent = 1;
                            $progressBar.css('width', percent + '%').attr('aria-valuenow', percent);
                        }
                    };

                    xhr.onerror = function () {
                        $button.removeClass('loadingbar').prop('disabled', false);
                        if ($progressContainer.length && $progressBar && $progressBar.length) {
                            $progressBar
                                .addClass('bg-danger')
                                .removeClass('bg-primary')
                                .css('width', '100%')
                                .attr('aria-valuenow', 100);
                        }
                        showUploadErrorForForm($form, 'Upload error', 'Could not upload file to storage. Please check your connection and try again.');
                    };

                    xhr.ontimeout = function () {
                        $button.removeClass('loadingbar').prop('disabled', false);
                        if ($progressContainer.length && $progressBar && $progressBar.length) {
                            $progressBar
                                .addClass('bg-danger')
                                .removeClass('bg-primary')
                                .css('width', '100%')
                                .attr('aria-valuenow', 100);
                        }
                        showUploadErrorForForm($form, 'Upload timeout', 'The upload took too long and was stopped. Please try again with a stable connection.');
                    };

                    xhr.onabort = function () {
                        $button.removeClass('loadingbar').prop('disabled', false);
                        showUploadErrorForForm($form, 'Upload cancelled', 'The upload was cancelled before completion. Please try again if this was not intentional.');
                    };

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            if ($progressContainer.length && $progressBar && $progressBar.length) {
                                $progressBar.css('width', '100%').attr('aria-valuenow', 100);
                            }

                            // Step 3: clear the file input so Laravel doesn't receive the binary
                            try {
                                const emptyDataTransfer = new DataTransfer();
                                $fileInput[0].files = emptyDataTransfer.files;
                            } catch (e) {}

                            // Step 4: add hidden fields so Laravel knows the R2 path and size
                            $form.find('input[name="ajax[new][r2_path]"]').remove();
                            $form.find('input[name="ajax[new][r2_uploaded]"]').remove();
                            $form.find('input[name="ajax[new][r2_size_bytes]"]').remove();

                            $('<input>', {
                                type: 'hidden',
                                name: 'ajax[new][r2_path]',
                                value: r2Path,
                            }).appendTo($form);

                            $('<input>', {
                                type: 'hidden',
                                name: 'ajax[new][r2_uploaded]',
                                value: '1',
                            }).appendTo($form);

                            $('<input>', {
                                type: 'hidden',
                                name: 'ajax[new][r2_size_bytes]',
                                value: file.size,
                            }).appendTo($form);

                            // Step 5: submit metadata to Laravel as usual
                            handleSendRequestItemForm($form, $button);
                        } else {
                            $button.removeClass('loadingbar').prop('disabled', false);
                            if ($progressContainer.length && $progressBar && $progressBar.length) {
                                $progressBar
                                    .addClass('bg-danger')
                                    .removeClass('bg-primary')
                                    .css('width', '100%')
                                    .attr('aria-valuenow', 100);
                            }
                            let humanStatus = xhr.status;
                            if (xhr.status === 403) {
                                humanStatus = '403 Forbidden';
                            } else if (xhr.status === 413) {
                                humanStatus = '413 Payload Too Large';
                            } else if (xhr.status === 500) {
                                humanStatus = '500 Server Error';
                            }
                            showUploadErrorForForm($form, 'Upload error', 'Storage returned an error (' + humanStatus + '). Please try again.');
                        }
                    };

                    xhr.send(file);
                },
                error: function (err) {
                    $button.removeClass('loadingbar').prop('disabled', false);
                    let msg = 'Could not prepare upload. Please try again.';
                    if (err && err.responseJSON) {
                        if (err.responseJSON.msg) {
                            msg = err.responseJSON.msg;
                        } else if (err.responseJSON.errors) {
                            const firstKey = Object.keys(err.responseJSON.errors)[0];
                            if (firstKey && err.responseJSON.errors[firstKey] && err.responseJSON.errors[firstKey][0]) {
                                msg = err.responseJSON.errors[firstKey][0];
                            }
                        }
                    }
                    showUploadErrorForForm($form, 'Upload error', msg);
                }
            });
        } catch (error) {
            if (typeof console !== 'undefined' && console.error) {
                console.error('Direct R2 upload failed:', error);
            }
            showUploadErrorForForm($form, 'Upload error', 'Unexpected error while preparing upload. Please try again.');
            $button.removeClass('loadingbar').prop('disabled', false);
        }
    }

    function handleShowFileInputsBySource($form, source, fileType) {
        // Default to 'upload' (stored as R2 in backend) if source not provided
        if (!source || source === '') {
            source = 'upload';
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
            case 'r2':
                $fileTypeVolumeInputs.removeClass('d-none');

                // Remove downloadable input - always hide it
                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');

                if (source === 'external_link') {
                    $volumeInputs.removeClass('d-none');
                } else if (source === 'r2') {
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

        syncFileUploadInteractionLock($form);
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

        syncFileUploadInteractionLock($form);
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
