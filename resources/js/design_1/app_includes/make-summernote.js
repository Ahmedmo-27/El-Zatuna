(function ($) {
    "use strict";

    var FONT_NAMES = [
        'Arial',
        'Helvetica',
        'Times New Roman',
        'Playfair Display',
        'Courier New',
        'Roboto',
        'Verdana'
    ];

    function updateFontNameButton($editor, fontNames) {
        var $toolbar = $editor.next('.note-editor').find('.note-toolbar');
        var $btn = $toolbar.find('.note-current-fontname');
        if (!$btn.length) return;
        var displayFont = fontNames[0];
        try {
            var sel = window.getSelection();
            var range = sel && sel.rangeCount ? sel.getRangeAt(0) : null;
            var node = range ? range.startContainer : null;
            if (node) {
                var el = node.nodeType === 3 ? node.parentElement : node;
                if (el && el.nodeType === 1) {
                    var font = window.getComputedStyle(el).fontFamily.replace(/^["']|["']$/g, '').split(',')[0].trim();
                    if (font) {
                        for (var i = 0; i < fontNames.length; i++) {
                            if (fontNames[i] && (font.indexOf(fontNames[i]) !== -1 || fontNames[i].indexOf(font) !== -1)) {
                                displayFont = fontNames[i];
                                break;
                            }
                        }
                        if (displayFont === fontNames[0] && font) {
                            displayFont = font;
                        }
                    }
                }
            }
            $btn.text(displayFont);
        } catch (e) {
            $btn.text(displayFont);
        }
    }

    window.makeSummernote = function ($content, cardHeight = null, onChange = undefined) {
        const height = cardHeight ? cardHeight : ($content.attr('data-height') ? $content.attr('data-height') : 300);
        var fontNames = FONT_NAMES;

        $content.summernote({
            dialogsInBody: true,
            tabsize: 2,
            height: height,
            placeholder: $content.attr('placeholder'),

            fontNames: fontNames,
            fontSizes: [
                '10', '12', '14', '16', '18', '20', '24', '28', '32', '36'
            ],

            callbacks: {
                onChange: onChange,
                onInit: function() {
                    var $editor = $content;
                    setTimeout(function() {
                        var $toolbar = $editor.next('.note-editor').find('.note-toolbar');
                        var $fontBtn = $toolbar.find('.note-current-fontname');
                        if ($fontBtn.length) {
                            $fontBtn.text(fontNames[0]);
                        }
                    }, 0);
                },
                onFocus: function() {
                    updateFontNameButton($content, fontNames);
                },
                onBlur: function() {
                    updateFontNameButton($content, fontNames);
                }
            },

            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ]
        });

        $content.next('.note-editor').find('.note-editable').on('keyup mouseup', function() {
            updateFontNameButton($content, fontNames);
        });
    }

})(jQuery);
