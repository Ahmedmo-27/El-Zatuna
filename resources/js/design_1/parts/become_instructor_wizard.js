(function ($) {
    "use strict"

    $('body').on('change', '.js-user-bank-input', function (e) {
        e.preventDefault();

        const $optionSelected = $(this).find("option:selected");
        const specifications = $optionSelected.attr('data-specifications')

        const $card = $('.js-bank-specifications-card');
        let html = '';

        if (specifications) {
            Object.entries(JSON.parse(specifications)).forEach(([index, item], key) => {

                html += '<div class="form-group">\n' +
                    '         <label class="form-group-label bg-white">' + item + '</label>\n' +
                    '         <input type="text" name="bank_specifications[' + index + ']" value="" class="form-control bg-white"/>\n' +
                    ' </div>'
            })
        }

        $card.html(html);
    });

    $('body').on('change', 'input[name="role"]', function () {
        const value = $(this).val();
        const type = (value === "teacher") ? "become_instructor" : "become_organization";

        $.post('/become-instructor/form-fields', {type: type}, function (result) {
            if (result) {
                $('.js-form-fields-card').html(result.html);

                formsDatetimepicker();
            }
        })
    });

    // Occupations/subjects: text field with search + always-visible "Add different subject" option
    function initOccupationsInput() {
        const $wrapper = $('.js-occupations-wrapper');
        if (!$wrapper.length) return;

        const $input = $wrapper.find('.js-occupations-input');
        const $dropdown = $wrapper.find('.js-occupations-dropdown');
        const $results = $wrapper.find('.js-occupations-results');
        const $addNew = $wrapper.find('.js-occupations-add-new');
        const $addNewTerm = $wrapper.find('.js-add-new-term');
        const $tags = $wrapper.find('.js-occupations-tags');
        const $hiddenContainer = $wrapper.find('.js-occupations-hidden-container');
        const $loading = $wrapper.find('.js-occupations-loading');
        const $error = $wrapper.find('.js-occupations-error');

        let selected = [];
        let searchTimeout = null;
        let hideDropdownTimeout = null;
        let lastSelectedAt = 0;
        let lastAddNewAt = 0;

        function setLoading(isLoading) {
            if (isLoading) {
                $loading.removeClass('d-none');
                $input.prop('disabled', true);
                $addNew.addClass('disabled');
            } else {
                $loading.addClass('d-none');
                $input.prop('disabled', false);
                $addNew.removeClass('disabled');
            }
        }

        function showError(message) {
            if (!message) {
                $error.addClass('d-none').text('');
                return;
            }
            $error.removeClass('d-none').text(message);
        }

        function syncHiddenInputs() {
            $hiddenContainer.empty();
            selected.forEach(function (item) {
                $hiddenContainer.append(
                    $('<input>').attr({ type: 'hidden', name: 'occupations[]', value: item.id })
                );
            });
        }

        function addSelected(item) {
            if (selected.some(function (s) { return String(s.id) === String(item.id); })) return;
            selected.push(item);
            renderTags();
            syncHiddenInputs();
        }

        function removeSelected(id) {
            selected = selected.filter(function (s) { return String(s.id) !== String(id); });
            renderTags();
            syncHiddenInputs();
        }

        function renderTags() {
            $tags.empty();
            selected.forEach(function (item) {
                const $tag = $('<span class="badge bg-[#F5F9E8] text-[#072923] px-3 py-1 rounded-8 d-inline-flex align-items-center gap-1">' +
                    '<span>' + $('<div>').text(item.text).html() + '</span>' +
                    '<button type="button" class="js-remove-tag btn btn-link p-0 text-[#072923]/60 hover:text-danger" style="font-size: 14px; line-height: 1;" data-id="' + item.id + '">&times;</button>' +
                    '</span>');
                $tags.append($tag);
            });
        }

        function doSearch(q) {
            showError('');
            if (q && q.length > 0) setLoading(true);
            $addNewTerm.text(q || 'type above first');
            $addNew.removeClass('d-none');
            $.get('/become-instructor/search-subjects', { q: q || '' })
                .done(function (data) {
                    const results = data.results || [];
                    $results.empty();
                    if (results.length === 0) {
                        if (q) {
                            $results.append(
                                $('<div class="p-2 text-[#072923]/50">No matching subjects. Use "Add different subject" below to add "' + $('<div>').text(q).html() + '".</div>')
                            );
                        } else {
                            $results.append($('<div class="p-2 text-[#072923]/50">No subjects found.</div>'));
                        }
                    } else {
                        results.forEach(function (item) {
                            const textEsc = $('<div>').text(item.text).html();
                            const $row = $('<div class="js-result-row p-2 rounded-8 cursor-pointer hover:bg-[#F5F9E8]/50" style="min-height: 44px; display: flex; align-items: center;" data-id="' + item.id + '">' + textEsc + '</div>');
                            $results.append($row);
                        });
                    }
                })
                .fail(function () {
                    showError('Could not load subjects. Please check your connection and try again.');
                })
                .always(function () {
                    if (q && q.length > 0) setLoading(false);
                });
        }

        function showDropdown() {
            $dropdown.removeClass('d-none');
        }

        function hideDropdown() {
            if (hideDropdownTimeout) clearTimeout(hideDropdownTimeout);
            hideDropdownTimeout = setTimeout(function () {
                hideDropdownTimeout = null;
                $dropdown.addClass('d-none');
            }, 280);
        }

        function cancelHideDropdown() {
            if (hideDropdownTimeout) {
                clearTimeout(hideDropdownTimeout);
                hideDropdownTimeout = null;
            }
        }

        $dropdown.on('mousedown touchstart', function (e) {
            e.preventDefault();
            cancelHideDropdown();
        });

        $input.on('focus', function () {
            cancelHideDropdown();
            const q = $.trim($input.val());
            if ($dropdown.hasClass('d-none')) {
                showDropdown();
                doSearch(q);
            }
        });

        $input.on('input', function () {
            const q = $.trim($input.val());
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                doSearch(q);
                if (q) showDropdown();
            }, 200);
        });

        $input.on('blur', function () {
            hideDropdown();
        });

        function selectResultRow($row) {
            var id = $row.data('id');
            var text = $row.text().trim();
            if (!id && text === '') return;
            if (Date.now() - lastSelectedAt < 400) return;
            lastSelectedAt = Date.now();
            setLoading(true);
            addSelected({ id: id, text: text });
            $input.val('');
            // keep dropdown open so admin can select multiple subjects
            setTimeout(function () { setLoading(false); }, 250);
        }

        $results.on('click', '.js-result-row', function (e) {
            e.preventDefault();
            selectResultRow($(this));
        });

        $results.on('touchend', '.js-result-row', function (e) {
            e.preventDefault();
            selectResultRow($(this));
        });

        function handleAddNewSubject() {
            if (Date.now() - lastAddNewAt < 400) return;
            const term = $.trim($input.val()) || $.trim($addNewTerm.text());
            if (!term || term === 'type above first') return;
            lastAddNewAt = Date.now();
            showError('');
            setLoading(true);
            const token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            $.post('/become-instructor/create-subject', { title: term, _token: token }, function (data) {
                addSelected({ id: data.id, text: data.text || term });
                $input.val('');
            }).fail(function () {
                addSelected({ id: term, text: term });
                showError('Subject was added locally, but we could not save it on the server. Please try again later.');
                $input.val('');
            }).always(function () {
                setLoading(false);
            });
        }

        $addNew.on('click', function (e) {
            e.preventDefault();
            handleAddNewSubject();
        }).on('touchend', function (e) {
            e.preventDefault();
            handleAddNewSubject();
        });

        $tags.on('click', '.js-remove-tag', function (e) {
            e.preventDefault();
            removeSelected($(this).data('id'));
        });

        $wrapper.on('click', function (e) {
            if ($(e.target).closest('.js-occupations-input').length || $(e.target).closest('.js-occupations-dropdown').length) return;
            if ($(e.target).closest('.js-occupations-tags').length) {
                hideDropdown();
                return;
            }
            $input.focus();
        });

        // Close when clicking anywhere outside the occupations component
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.js-occupations-wrapper').length) {
                hideDropdown();
            }
        });

        var $tabPane = $wrapper.closest('.tab-pane');
        if ($tabPane.length && $tabPane.attr('id')) {
            var tabId = $tabPane.attr('id');
            $(document).on('shown.bs.tab', 'a[href="#' + tabId + '"], a[data-bs-target="#' + tabId + '"], [data-toggle="tab"][href="#' + tabId + '"]', function () {
                var $w = $('#' + tabId).find('.js-occupations-wrapper');
                if ($w.length) $w.find('.js-occupations-input').trigger('focus');
            });
        }

        // Initial selected from server
        try {
            const initial = $wrapper.data('initial') || [];
            if (Array.isArray(initial) && initial.length) {
                initial.forEach(function (item) {
                    addSelected({ id: item.id, text: item.text });
                });
            }
        } catch (err) {}

        syncHiddenInputs();
    }

    $(document).ready(function () {
        initOccupationsInput();
    });

})(jQuery)
