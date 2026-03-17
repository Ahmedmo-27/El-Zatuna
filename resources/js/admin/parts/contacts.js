(function ($) {
    "use strict";

    $('body').on('click', '.js-show-description', function (e) {
        e.preventDefault();

        const message = $(this).parent().find('.js-contact-message').val();

        const $modal = $('#contactMessage');
        $modal.find('.modal-body').text(message);

        $modal.modal('show');
    });
})(jQuery);
