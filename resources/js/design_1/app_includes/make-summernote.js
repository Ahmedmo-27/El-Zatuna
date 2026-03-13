(function ($) {
    "use strict";

    window.makeSummernote = function ($content, cardHeight = null, onChange = undefined) {
        const height = cardHeight ? cardHeight : ($content.attr('data-height') ? $content.attr('data-height') : 300);

        $content.summernote({
            dialogsInBody: true,
            tabsize: 2,
            height: height,
            placeholder: $content.attr('placeholder'),

            // Provide a useful set of fonts and sizes for course descriptions
            fontNames: [
                'Arial',
                'Helvetica',
                'Times New Roman',
                'Playfair Display',
                'Courier New',
                'Roboto',
                'Verdana'
            ],
            fontSizes: [
                '10', '12', '14', '16', '18', '20', '24', '28', '32', '36'
            ],

            callbacks: {
                onChange: onChange
            },

            // Rich toolbar configuration to support styling, lists, media, and code view
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
    }

})(jQuery);
