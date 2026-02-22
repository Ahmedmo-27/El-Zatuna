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

        let selected = [];
        let searchTimeout = null;

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
            if (!q || q.length < 1) {
                $results.empty();
                $addNewTerm.text('');
                $addNew.toggleClass('d-none', true);
                return;
            }
            $addNewTerm.text(q);
            $addNew.removeClass('d-none');
            $.get('/become-instructor/search-subjects', { q: q }, function (data) {
                const results = data.results || [];
                $results.empty();
                if (results.length === 0) {
                    $results.append(
                        $('<div class="p-2 text-[#072923]/50">No matching subjects. Use "Add different subject" below to add "' + $('<div>').text(q).html() + '".</div>')
                    );
                } else {
                    results.forEach(function (item) {
                        const textEsc = $('<div>').text(item.text).html();
                        const $row = $('<div class="js-result-row p-2 rounded-8 cursor-pointer hover:bg-[#F5F9E8]/50" data-id="' + item.id + '">' + textEsc + '</div>');
                        $results.append($row);
                    });
                }
            });
        }

        function showDropdown() {
            $dropdown.removeClass('d-none');
        }

        function hideDropdown() {
            setTimeout(function () { $dropdown.addClass('d-none'); }, 150);
        }

        $input.on('focus', function () {
            const q = $.trim($input.val());
            $addNewTerm.text(q || 'type above first');
            if (q) {
                doSearch(q);
            } else {
                $results.empty();
                $results.append($('<div class="p-2 text-[#072923]/50">Type to search existing subjects.</div>'));
                $addNew.removeClass('d-none');
            }
            showDropdown();
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

        $results.on('click', '.js-result-row', function () {
            const id = $(this).data('id');
            const text = $(this).text().trim();
            addSelected({ id: id, text: text });
            $input.val('');
            hideDropdown();
            $input.focus();
        });

        $addNew.on('click', function () {
            const term = $.trim($input.val()) || $.trim($addNewTerm.text());
            if (!term || term === 'type above first') return;
            const token = $('meta[name="csrf-token"]').attr('content') || $('#becomeInstructorForm input[name="_token"]').val();
            $.post('/become-instructor/create-subject', { title: term, _token: token }, function (data) {
                addSelected({ id: data.id, text: data.text || term });
                $input.val('');
                hideDropdown();
                $input.focus();
            }).fail(function () {
                addSelected({ id: term, text: term });
                $input.val('');
                hideDropdown();
            });
        });

        $tags.on('click', '.js-remove-tag', function (e) {
            e.preventDefault();
            removeSelected($(this).data('id'));
        });

        $wrapper.on('click', function (e) {
            if ($(e.target).closest('.js-occupations-input, .js-occupations-dropdown').length) return;
            hideDropdown();
        });

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
