(function ($) {
    "use strict"

    /* dropdown */
    // **
    // **
    $('.dropdown-toggle').dropdown();

    /**
     * close swl
     * */
    $('body').on('click', '.close-swl', function (e) {
        e.preventDefault();
        Swal.close();
    });


    /**
     * Tooltip
     * */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    window.tippyTooltip = function () {
        tippy.setDefaultProps({
            delay: 50,
            animation: 'shift-away',
        });

        tippy('[data-tippy-content]');
    }

    $(document).ready(function () {
        tippyTooltip()
    })

    /**
     * select
     * */

    /* on tag
    * data-allow-clear="false"
    * data-placeholder=""
    * multiple
    * data-minimum-results-for-search="Infinity" => disable search input
    * */
    window.handleSelect2 = ($element) => {
        const placeholder = $element.attr('data-placeholder')
        const dropdownParent = $element.attr('data-dropdown-parent') ?? 'body'

        return $element.select2({
            placeholder: placeholder,
            width: '100%',
            dropdownParent: $(dropdownParent),
        });
    }

    window.handleSearchableSelect = ($element) => {
        const column = $element.attr('data-item-column-name')
        const placeholder = $element.attr('data-placeholder')
        const apiPath = $element.attr('data-api-path')
        const option = $element.attr('data-option')
        const webinarId = $element.attr('data-webinar-id')
        const itemId = $element.attr('data-item-id')
        const dropdownParent = $element.attr('data-dropdown-parent') ?? 'body'

        $element.select2({
            placeholder: placeholder,
            minimumInputLength: 3,
            allowClear: true,
            width: '100%',
            dropdownParent: $(dropdownParent),
            ajax: {
                url: apiPath,
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params) {
                    return {
                        term: params.term,
                        option: option,
                        webinar_id: webinarId,
                        item_id: itemId,
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item[column] ?? '',
                                id: item['id'] ?? null
                            }
                        })
                    };
                }
            }
        });
    }


    $(document).ready(function () {
        const searchableSelect = $('.searchable-select');
        const select2 = $('.select2');

        if (searchableSelect && searchableSelect.length) {
            handleSearchableSelect(searchableSelect)
        }

        if (select2 && select2.length) {
            for (const select2El of select2) {
                handleSelect2($(select2El))
            }
        }
    })

    /**
     * select
     * */


    /*
    * loading Swl
    * */
    window.loadingSwl = () => {
        const loadingHtml = '<div class="d-flex align-items-center justify-content-center py-56 "><img src="/assets/default/images/loading.svg" width="80" height="80"></div>';
        Swal.fire({
            html: loadingHtml,
            showCancelButton: false,
            showConfirmButton: false,
            width: '24rem',
        });
    };

    //
    // delete sweet alert
    $('body').on('click', '.delete-action', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const href = $(this).attr('href');

        const title = $(this).attr('data-title') ?? deleteAlertTitle;
        const msg = $(this).attr('data-msg') ?? deleteAlertHint;
        const confirm = $(this).attr('data-confirm') ?? deleteAlertConfirm;

        const bodyHtml = `<div class="d-flex-center flex-column text-center p-32">
                <div class="d-flex-center size-72 rounded-16 bg-gray">
                    <div class="d-flex-center size-64 rounded-16 bg-danger">${bulDangerIcon}</div>
                </div>

                <h4 class="font-14 text-dark mt-8">${title}</h4>
                <p class="text-gray-500 mt-8">${msg}</p>
            </div>`;

        const $footerHtml = `<div class="d-flex align-items-center gap-24 justify-content-end">
            <button type="button" class="close-swl btn btn-transparent">${deleteAlertCancel}</button>
            <button type="button" id="swlDelete" data-href="${href}" class="btn btn-danger">${confirm}</button>
        </div>`;

        const html = makeModalHtml(deleteRequestLang, closeIcon, bodyHtml, $footerHtml)

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            width: '36rem',
            allowOutsideClick: () => !Swal.isLoading(),
            didOpen: function () {
            },
        })

    });

    $('body').on('click', '#swlDelete', function (e) {
        e.preventDefault();
        var $this = $(this);
        const href = $this.attr('data-href');

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.get(href, function (result) {
            if (result && result.code === 200) {
                const title = result.title ?? deleteAlertSuccess;
                const msg = result.msg ?? deleteAlertSuccessHint;

                Swal.fire({
                    title: title,
                    html: `<div class="text-center mt-8 mb-12">${msg}</div>`,
                    showConfirmButton: false,
                    icon: 'success',
                });
                setTimeout(() => {

                    if (typeof result.redirect_to !== "undefined" && result.redirect_to !== undefined && result.redirect_to !== null && result.redirect_to !== '') {
                        window.location.href = result.redirect_to;
                    } else {
                        window.location.reload();
                    }
                }, 1000)
            } else {
                const title = result.title ?? deleteAlertFail;
                const msg = result.msg ?? deleteAlertFailHint;

                Swal.fire({
                    title: title,
                    html: `<div class="text-center mt-8 mb-12">${msg}</div>`,
                    icon: 'error',
                })

                $this.removeClass('loadingbar primary').prop('disabled', false);
            }
        }).fail(err => {
            Swal.fire({
                title: deleteAlertFail,
                html: `<div class="text-center mt-8 mb-12">${deleteAlertFailHint}</div>`,
                icon: 'error',
            })

            $this.removeClass('loadingbar primary').prop('disabled', false);
        })
    })


    // ********************************************
    // ********************************************
    // form serialize to Object
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    window.serializeObjectByTag = (tagId) => {
        var o = {};
        var a = tagId.find('input, textarea, select').serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };


    /*
    * Custom Toast
    * */

    function handleCustomToastHide($el) {
        $el.removeClass('show');

        setTimeout(function () {
            $el.remove();
        }, 600)
    }

    window.customToast = function (html, hideAfter = 5000) {
        const randomId = 'id-' + randomString(6);

        $('body').append(`<div class="custom-toast-alert" id="${randomId}">${html}</div>`)

        const $toastEl = $('#' + randomId);

        setTimeout(function () {
            $toastEl.addClass('show');
        }, 100)

        // Hide Toast
        setTimeout(function () {
            handleCustomToastHide($toastEl)
        }, hideAfter)
    }

    $('body').on('click', '.js-close-toast-alert', function (e) {
        e.preventDefault();

        handleCustomToastHide($(this).closest('.custom-toast-alert'))
    })

    /*
    * Handle ajax FORBIDDEN requests
    * */
    $(document).on('ajaxError', function (event, xhr) {
        if (xhr.status === 401 || xhr.status === 403) {
            showToast('error', forbiddenRequestToastTitleLang, forbiddenRequestToastMsgLang)
        }
    });


    window.randomString = function (count = 5) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < count; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    };

    $('body').on('click', '#goToTop', function (e) {
        e.preventDefault();

        $('html, body').animate({
            scrollTop: 0
        }, 500);
    })

    $('body').on('change', 'input[type="file"]', function () {
        const value = this.value;

        if (value) {
            const splited = value.split('\\');

            if (splited.length) {
                $(this).closest('.custom-file').find('.custom-file-text').text(splited[splited.length - 1])
            }
        }
    })


    $('body').on('click', '.js-language-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const flag = $this.find('img').attr('src')
        const parent = $this.closest('.js-language-select');

        parent.find('input[name="locale"]').val(value);
        parent.find('.js-lang-title').text(title);
        parent.find('.language-toggle-flag img').attr('src', flag);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });

    $('body').on('click', '.js-currency-dropdown-item', function () {
        const $this = $(this);
        const value = $this.attr('data-value');
        const title = $this.attr('data-title');
        const parent = $this.closest('.js-currency-select');

        parent.find('input[name="currency"]').val(value);
        parent.find('.js-lang-title').text(title);

        if (!parent.hasClass('js-dont-submit')) {
            parent.find('form').trigger('submit')
        }
    });


    function handleAccordionArrow($accordion, show, target) {
        const $arrow = $accordion.find('.collapse-arrow-icon[href="' + target + '"]')

        if ($arrow.hasClass('arrow-right')) {
            if (show) {
                $arrow.addClass('arrow-down')
            } else {
                $arrow.removeClass('arrow-down')
            }
        } else {
            if (show) {
                $arrow.removeClass('arrow-down')
            } else {
                $arrow.addClass('arrow-down')
            }
        }
    }

    window.handleAccordionCollapse = function () {
        $("[data-toggle='collapse']").each(function () {
            const $this = $(this);
            const target = $this.attr('href');
            const $accordion = $this.closest('.accordion');
            const $target = $(target);

            if ($target.hasClass('show')) {
                $target.slideDown();
                handleAccordionArrow($accordion, true, target);
            } else {
                $target.slideUp();
                handleAccordionArrow($accordion, false, target);
            }

            // $this.off('click') => Prevent multiple click events from being recorded
            // Resolving the issue of accordions opening and closing multiple times

            $this.off('click').on('click', function () {

                const parent = $this.attr('data-parent')
                const $parent = $(`${parent}`);
                const collapseJustOne = ($this.attr("data-collapse") === "one")

                if ($parent.length && collapseJustOne) {
                    $parent.find('.accordion__collapse.show').each(function () {
                        const $openTarget = $(this);
                        const $openTargetAccordion = $openTarget.closest('.accordion')

                        $openTarget.slideUp().removeClass('show');
                        handleAccordionArrow($openTargetAccordion, false, target);
                    });
                }


                if ($target.hasClass('show')) {
                    $target.slideUp().removeClass('show');
                    handleAccordionArrow($accordion, false, target);
                } else {
                    $target.addClass('show').slideDown();
                    handleAccordionArrow($accordion, true, target);
                }

                return false;
            });
        });
    };
    handleAccordionCollapse();

    $('body').on('click', '.cancel-accordion', function () {
        $(this).closest('.accordion').remove();
    })

    window.makeModalHtml = function (title, cIcon, html, footer = null, subtitle = null) {
        return `<div class="js-custom-modal rounded-top-20 soft-shadow-5 pt-24">
            <div class="d-flex align-items-center justify-content-between px-16">
                <div class="">
                    <h3 class="font-16 text-black">${title}</h3>

                    ${subtitle ? '<p class="mt-8 font-14 text-gray-500">' + subtitle + '</p>' : ''}
                </div>

                <button class="modal-close-btn close-swl">${cIcon}</button>
            </div>

            <div class="position-relative py-8 custom-swl-modal-body has-footer px-16">
                ${html}
            </div>

            ${footer ? `<div class="custom-modal-footer bg-gray-100 p-16 w-100 rounded-bottom-20">${footer}</div>` : ''}
        </div>`;
    }

    $('body').on('click', '.js-login-toast', function (e) {
        e.preventDefault();

        if (notLoginToastTitleLang && notLoginToastMsgLang) {
            showToast('error', notLoginToastTitleLang, notLoginToastMsgLang);
        }
    });

    window.handleTranslations = function (translations, defaultLocale, column, justTranslateByLocale = false) {
        let text = null;

        if (Object.keys(translations).length) {
            Object.keys(translations).forEach(key => {
                const translation = translations[key];

                if (translation.locale === defaultLocale) {
                    text = translation[column]
                }
            })

            if (!text && !justTranslateByLocale) {
                text = translations[0][column]
            }
        }

        return text;
    }

    $('body').on('click', '.js-copy-input', function (e) {
        e.preventDefault();

        const $this = $(this);
        const copyText = $this.attr('data-copy-text');
        const doneText = $this.attr('data-done-text');
        const $input = $this.closest('.form-group').find('input');

        $input.removeAttr('disabled');
        $input.focus();
        $input.select();
        document.execCommand("copy");
        navigator.clipboard.writeText($input.val());

        $this.attr('data-original-title', doneText)
            .tooltip('show');
        $this.attr('data-original-title', copyText);
    })

    window.lockBodyScroll = function (lock) {
        const root = document.getElementsByTagName('html')[0];

        if (lock) {
            root.classList.add('close-body-scroll');
        } else {
            root.classList.remove('close-body-scroll');
        }
    }

    /* input-step */
    $('body').on('click', '.input-step .plus', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $input = $this.closest('.input-step').find('input');

        const val = $input.val() ? Number($input.val()) : 0;

        $input.val(val + 1)
    })

    $('body').on('click', '.input-step .minus', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $input = $this.closest('.input-step').find('input');

        const val = $input.val() ? Number($input.val()) : 0;

        if ((val - 1) >= 0)
            $input.val(val - 1)
    })

    /*****
     * Event shown.cs.tab => when sho
     * ****/
    $('body').on('click', '[data-tab-toggle]', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $parent = $this.closest('.custom-tabs');
        const href = $this.attr('data-tab-href');

        $parent.find('[data-tab-toggle]').removeClass('active');
        $parent.find('.custom-tabs-content').removeClass('active');

        $this.addClass('active');

        const $target = $(href);
        $target.addClass('active');

        // Trigger custom event
        const customEvent = $.Event('shown.cs.tab', {bubbles: true});
        $target.trigger(customEvent);
    })


    // ********************************************
    // ********************************************
    // date & time piker

    window.makeDateRangePicker = function ($el, drops = 'down') {
        const format1 = $el.attr('data-format') ?? 'YYYY-MM-DD';
        const timepicker1 = !!$el.attr('data-timepicker');

        $el.daterangepicker({
            locale: {
                format: format1,
                cancelLabel: clearLang
            },
            drops: drops,
            autoUpdateInput: false,
            timePicker: timepicker1,
            timePicker24Hour: true,
            opens: 'right'
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format(format1) + ' - ' + picker.endDate.format(format1));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.makeDateTimepicker = function ($el, drops = 'down') {
        const format2 = $el.attr('data-format') ?? 'YYYY-MM-DD HH:mm';
        const showDropdowns = !!($el.attr('data-show-drops'));

        $el.daterangepicker({
            locale: {
                format: format2,
                cancelLabel: clearLang
            },
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            autoUpdateInput: false,
            showDropdowns: showDropdowns,
            drops: drops,
            period: 'day' | 'month' | 'year'
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.makeSingleDatePicker = function ($el, drops = 'down') {
        const format3 = $el.attr('data-format') ?? 'YYYY-MM-DD';
        const showDropdowns = !!($el.attr('data-show-drops'));

        $el.daterangepicker({
            locale: {
                format: format3,
                cancelLabel: clearLang
            },
            singleDatePicker: true,
            timePicker: false,
            autoUpdateInput: false,
            showDropdowns: showDropdowns,
            drops: drops,
        });
        $el.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });

        $el.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    }

    window.resetDatePickers = (drops = 'down') => {

        /*
        * drops => down | up
        * */

        if (jQuery().daterangepicker) {
            const $dateRangePicker = $('.date-range-picker');
            makeDateRangePicker($dateRangePicker, drops)

            const $datetimepicker = $('.datetimepicker');
            makeDateTimepicker($datetimepicker, drops)

            const $datepicker = $('.datepicker');
            makeSingleDatePicker($datepicker, drops)

        }
    };


    // Timepicker
    window.handleClockPicker = function ($el) {
        if (jQuery().timepicker) {
            $el.timepicker({
                icons: {
                    up: 'chevron-up-icon',
                    down: 'chevron-down-icon'
                },
                showMeridian: false,
            });
        }
    }

    /*
    * Select locale change
    * */
    $('body').on('change', '.js-reload-when-selected', function (e) {
        e.preventDefault();

        const value = $(this).val();

        if (value) {
            let url = window.location.origin + window.location.pathname;

            url += (url.indexOf('?') > -1) ? '&' : '?';

            url += 'locale=' + value;

            window.location.href = url;
        }
    })

    /*
    * lists draggable sort
    * */
    $(document).ready(function () {

        const $defaultInitDatePickers = $('.js-default-init-date-picker');

        if ($defaultInitDatePickers.length) {
            const drops = $defaultInitDatePickers.attr("data-drops") ?? 'down';
            resetDatePickers(drops);
        }


        function updateToDatabase(path, idString) {
            $.post(path, {items: idString}, function (result) {
                if (result && result.title && result.msg) {
                    showToast('success', result.title, result.msg)
                }
            });
        }

        function setSortable(target) {
            if (target.length) {
                target.sortable({
                    group: 'no-drop',
                    handle: '.move-icon',
                    axis: "y",
                    update: function (e, ui) {
                        var sortData = target.sortable('toArray', {attribute: 'data-id'});
                        var path = e.target.getAttribute('data-path');

                        updateToDatabase(path, sortData.join(','))
                    }
                });
            }
        }

        const items = [];

        var draggableContentLists = $('.draggable-content-lists');
        if (draggableContentLists.length) {
            for (let item of draggableContentLists) {
                items.push($(item).attr('data-drag-class'))
            }
        }

        if (items.length) {
            for (let item of items) {
                const tag = $('.' + item);

                if (tag.length) {
                    setSortable(tag);
                }
            }
        }
    });

    /*
    *
    * */
    window.handleSendRequestItemForm = function ($form, $this, path = null, formActionAttr = "data-action", scrollToError = true) {
        let action = path ? path : $form.attr(formActionAttr);

        $this.addClass('loadingbar').prop('disabled', true);
        $form.find('input').removeClass('is-invalid');
        $form.find('textarea').removeClass('is-invalid');
        $form.find('.invalid-feedback').text('');

        const $customAlertEl = $form.find(".js-form-custom-alert");
        if ($customAlertEl.length > 0) {
            $customAlertEl.addClass("d-none").removeClass("d-flex")
        }

        let formData = new FormData();

        const items = $form.find('input, textarea, select').serializeArray();
        $.each(items, function () {
            formData.append(this.name, this.value);
        });

        const uploadFiles = $form.find('.js-ajax-upload-file-input');
       
        if (uploadFiles.length) {
            for (const uploadFileEl of uploadFiles) {
                const uploadFile = $(uploadFileEl);
                const file = uploadFile.prop('files') && uploadFile.prop('files')[0];

                if (uploadFile.length && file) {
                    const name = uploadFile.attr('data-upload-name') || uploadFile.attr('name') || 'upload_file';
                    formData.append(name, file);
                }
            }
        }

        const images = $form.find('.js-create-property-images');
        for (const image of images) {
            const $image = $(image);

            if ($image && $image.prop('files') && $image.prop('files')[0]) {
                formData.append('images[]', $image.prop('files')[0]);
            }
        }

        // Upload progress bar (for panel course file uploads to R2)
        const hasFileUploads = uploadFiles.length > 0;
        const isPanelFileRequest = typeof action === 'string' && action.indexOf('/panel/files/') !== -1;
        const $progressContainer = (hasFileUploads && isPanelFileRequest) ? $form.find('.progress').first() : null;
        const $progressBar = ($progressContainer && $progressContainer.length) ? $progressContainer.find('.progress-bar') : null;

        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            xhr: function () {
                const xhr = $.ajaxSettings.xhr();

                if (xhr.upload && $progressContainer && $progressBar && $progressBar.length) {
                    $progressContainer.removeClass('d-none');
                    $progressBar
                        .css('width', '0%')
                        .attr('aria-valuenow', 0);

                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            let percent = Math.round((e.loaded / e.total) * 100);

                            // Keep a little room for server-side processing; complete to 100% on success
                            if (percent > 99) {
                                percent = 99;
                            }

                            $progressBar
                                .css('width', percent + '%')
                                .attr('aria-valuenow', percent);
                        }
                    }, false);
                }

                return xhr;
            },
            success: function (result) {
                if (result && result.code === 200) {
                    if ($progressContainer && $progressBar && $progressBar.length) {
                        $progressBar
                            .css('width', '100%')
                            .attr('aria-valuenow', 100);
                    }

                    // Panel file upload: only treat as success when file was actually stored (must have path)
                    const isPanelFileUpload = typeof action === 'string' && action.indexOf('/panel/files/') !== -1;
                    const hasStoredFile = result.path && (result.path + '').length > 0;
                    if (isPanelFileUpload && !hasStoredFile) {
                        $this.removeClass('loadingbar').prop('disabled', false);
                        if (typeof showToast === 'function') {
                            showToast('error', 'Upload failed', 'File was not uploaded. Please choose a file and try again.');
                        }
                        return;
                    }

                    const dontReloadPage = (typeof result.dont_reload !== "undefined" && result.dont_reload);

                    const isCartStore = (typeof action === 'string' && action.indexOf('/cart/store') !== -1);
                    const title = isCartStore ? 'Cart' : (result.title ?? requestSuccessLang);
                    const msg = isCartStore ? 'Course added successfully!' : (result.msg ?? saveSuccessLang);

                    showToast('success', title, msg);

                    const timeout = (result.redirect_timeout) ? Number(result.redirect_timeout) : 500;

                    if (dontReloadPage) {
                        if (typeof Swal !== "undefined") {
                            Swal.close();
                        }
                    } else {
                        setTimeout(() => {
                            if (result.redirect_to && result.redirect_to !== 'null') {
                                window.location.href = result.redirect_to;
                            } else {
                                window.location.reload();
                            }
                        }, timeout)
                    }
                }
            },
            error: function (err) {
                $this.removeClass('loadingbar').prop('disabled', false);
                
                if ($progressContainer && $progressBar && $progressBar.length) {
                    $progressBar
                        .addClass('bg-danger')
                        .removeClass('bg-primary')
                        .css('width', '100%')
                        .attr('aria-valuenow', 100);

                    setTimeout(function () {
                        $progressContainer.addClass('d-none');
                        $progressBar
                            .removeClass('bg-danger')
                            .addClass('bg-primary')
                            .css('width', '0%')
                            .attr('aria-valuenow', 0);
                    }, 1500);
                }
                
                // Safely parse response JSON - handle cases where response might be HTML or invalid JSON
                var errors = {};
                try {
                    if (err.responseJSON) {
                        errors = err.responseJSON;
                    } else if (err.responseText) {
                        // Try to parse as JSON if it's not already an object
                        var parsed = typeof err.responseText === 'string' ? JSON.parse(err.responseText) : err.responseText;
                        if (parsed && typeof parsed === 'object') {
                            errors = parsed;
                        }
                    }
                } catch (e) {
                    // If parsing fails, errors remains empty object
                    errors = {};
                }
                
                var status = err.status || 0;
                var statusText = err.statusText || '';
                var errorMessage = err.responseText || '';

                // Handle 413 (Content Too Large) errors specifically
                // Check status code, status text, or error message for 413/Content Too Large
                var is413Error = status === 413 || 
                                statusText.toLowerCase().indexOf('content too large') !== -1 ||
                                statusText.toLowerCase().indexOf('request entity too large') !== -1 ||
                                errorMessage.toLowerCase().indexOf('413') !== -1 ||
                                errorMessage.toLowerCase().indexOf('content too large') !== -1 ||
                                errorMessage.toLowerCase().indexOf('request entity too large') !== -1 ||
                                (status === 0 && err.responseText && err.responseText.toLowerCase().indexOf('413') !== -1);

                if (is413Error) {
                    const errorMessage = 'The server rejected the upload. This often means the server\'s upload limit is lower than your file size (the site supports up to 2GB). If your file is small (e.g. under 100MB), ask your administrator to increase the server limit: nginx uses client_max_body_size; PHP uses upload_max_filesize and post_max_size.';
                    
                    // Show custom alert if available
                    if ($customAlertEl.length > 0) {
                        $customAlertEl.removeClass("d-none").addClass("d-flex");
                        $customAlertEl.find("span").text(errorMessage);
                    } else {
                        // Fallback to toast notification
                        showToast('error', 'File Upload Error', errorMessage);
                    }
                    
                    // Try to find file upload input and show error
                    const $fileInput = $form.find('.js-ajax-upload-file-input');
                    if ($fileInput.length) {
                        $fileInput.addClass('is-invalid');
                        const $formGroup = $fileInput.closest('.form-group');
                        if ($formGroup.length) {
                            let $feedback = $formGroup.find('.invalid-feedback');
                            if (!$feedback.length) {
                                $feedback = $('<div class="invalid-feedback"></div>');
                                $formGroup.append($feedback);
                            }
                            $feedback.text(errorMessage);
                        }
                    }
                    
                    // Refresh Captcha if needed
                    if ($form.find(".js-ajax-captcha").length) {
                        refreshCaptcha();
                    }
                    
                    return;
                }

                if (errors && errors.errors) {
                    Object.keys(errors.errors).forEach((key) => {
                        const error = errors.errors[key];
                        const ky = key.replaceAll('.', '-');

                        let element = $form.find('.js-ajax-' + ky);

                        element.addClass('is-invalid');
                        element.closest('.form-group').find('.invalid-feedback').text(error[0]);
                    });

                    if (scrollToError) {
                        const $swlModalBody = $('.custom-swl-modal-body');
                        const $elScroll = $form.find('.is-invalid');

                        if ($swlModalBody.length) {
                            $swlModalBody.animate({
                                scrollTop: $elScroll.offset().top
                            }, 1000);
                        } else {
                            $('html, body').animate({
                                scrollTop: $elScroll.offset().top
                            }, 1000);
                        }
                    }
                }

                // Custom Alert - Check if errors exists and has custom_alert property before accessing
                if (errors && typeof errors === 'object' && errors.custom_alert && $customAlertEl.length > 0) {
                    $customAlertEl.removeClass("d-none").addClass("d-flex")
                    $customAlertEl.find("span").text(errors.custom_alert)
                }

                // toast - Check if errors exists before accessing properties
                if (errors && errors.toast_alert) {
                    showToast('error', errors.toast_alert.title, errors.toast_alert.msg)
                }

                // Refresh Captcha
                if ($form.find(".js-ajax-captcha").length) {
                    refreshCaptcha();
                }
            }
        });
    }


    window.validatePrice = function (input) {
        const $input = $(input);
        const value = $input.val();
        const $error = $input.closest('.form-group').find('.invalid-feedback');

        $error.text('');

        if (/^\d*\.?\d*$/.test(value)) {
            $input.removeClass('is-invalid');
        } else {
            $input.addClass('is-invalid');
            $error.text(priceInvalidHintLang ?? 'Price Invalid');
        }
    }

    // =========
    // Basic Modal
    // ======
    window.handleBasicModal = function (path, title, callback, subtitle = null, modalSize = '34rem') {

        const html = `<div class="basic-modal-body">
                <div class="js-loading-card d-flex align-items-center justify-content-center py-40">
                    <img src="/assets/design_1/img/loading.svg" width="80" height="80">
                </div>
            </div>`;

        Swal.fire({
            html: makeModalHtml(title, closeIcon, html, '&nbsp;', subtitle),
            showCancelButton: false,
            showConfirmButton: false,
            width: modalSize,
            didOpen: function () {
                const $body = $('.basic-modal-body');
                const $footer = $('.custom-modal-footer');

                $.get(path, function (result) {
                    $body.find('.js-loading-card').remove();
                    $body.html(result.html);

                    if (typeof callback !== "undefined") {
                        callback(result, $body, $footer);
                    }
                }).fail(err => {
                    console.log(err)
                })
            }
        });
    }


    // **************************
    // file manager conf

    $('body').on('click', '.panel-file-manager', function (e) {
        e.preventDefault();
        $(this).filemanager('file', {prefix: '/laravel-filemanager'})
    });

    /*
    * // handle limited account modal
    * */
    window.handleFireSwalModal = function (html, size = 30) {
        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            width: size + 'rem',
        });
    };


    /*****
     * Submit Form
     * ****/
    $('body').on('click', '.js-submit-form-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form');

        $this.addClass('loadingbar').prop('disabled', true);

        $form.trigger('submit');
    })


    $(document).ready(function () {
        $('img.js-avatar-img').on('error', function () {
            if (defaultAvatarPath) {
                $(this).attr('src', defaultAvatarPath);
            }
        });
    });

    window.updateQueryParamAndReload = function (key, value) {
        let url = window.location.href;
        let separator = (url.indexOf("?") === -1) ? "?" : "&";
        let newParam = key + "=" + value;

        if (url.indexOf(key + "=") >= 0) {
            let regex = new RegExp(key + "=[^&]*");
            url = url.replace(regex, newParam);
        } else {
            url += separator + newParam;
        }

        window.location.href = url;
    }


    $('body').on('click', '.js-custom-file-clear', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $parent = $this.closest('.custom-file');

        const text = $this.attr('data-text') ?? '';

        $parent.find('input').val('');
        $parent.find('.custom-file-text').text(text);

        $this.remove()
    })

})(jQuery)
