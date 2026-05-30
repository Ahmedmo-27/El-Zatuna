(function ($) {
    "use strict"

    $('body').on('click', '.js-submit-newsletter-btn', function (e) {
        e.preventDefault();

        const $this = $(this);
        let $form = $this.closest('.js-newsletter-form');

        if (!$form.length) {
            $form = $this.siblings('.js-newsletter-form').first();
        }

        if (!$form.length) {
            if (typeof showToast === 'function') {
                showToast('error', requestFailedLang, 'Newsletter form not found.');
            }
            return;
        }

        const path = "/newsletters";

        const $emailInput = $form.find('input[name="newsletter_email"]');
        const $allInputs = $form.find('input');
        const $feedback = $emailInput.closest('.form-group').find('.invalid-feedback').first();

        $allInputs.removeClass('is-invalid');
        if ($feedback.length) {
            $feedback.text('');
        }

        $this.addClass('loadingbar').prop('disabled', true);

        $.post(path, $form.find('input, textarea, select').serialize(), function (result) {
            if (result && result.code === 200) {
                if (typeof showToast === 'function') {
                    showToast('success', result.title ?? requestSuccessLang, result.msg ?? saveSuccessLang);
                }

                // Clear all newsletter fields (email + optional first name)
                $form.find('input[type="text"], input[type="email"]').val('');
            }
        }).fail(function (err) {
            let response = {};

            try {
                response = err.responseJSON ?? JSON.parse(err.responseText ?? '{}');
            } catch (e) {
                response = {};
            }

            if (response && response.errors && response.errors.newsletter_email) {
                const errorMessage = response.errors.newsletter_email[0];
                $emailInput.addClass('is-invalid');

                if ($feedback.length) {
                    $feedback.text(errorMessage);
                }

                if (typeof showToast === 'function') {
                    showToast('error', requestFailedLang, errorMessage);
                }
                return;
            }

            if (response && response.toast_alert && typeof showToast === 'function') {
                showToast('error', response.toast_alert.title, response.toast_alert.msg);
                return;
            }

            if (typeof showToast === 'function') {
                showToast('error', requestFailedLang, somethingWentWrongLang);
            }
        }).always(function () {
            $this.removeClass('loadingbar').prop('disabled', false);
        });
    })

})(jQuery)
