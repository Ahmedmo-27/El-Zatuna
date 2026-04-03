(function ($) {
    "use strict";


    $('body').on('click', '#followToggle', function (e) {
        e.preventDefault();

        const $this = $(this);
        $this.addClass('loadingbar').prop('disabled', true);
        const user_id = $this.attr('data-user-id');

        const path = '/users/' + user_id + '/follow';

        $.post(path, {}, function (result) {
            if (result && result.code === 200) {
                if (result.follow) {
                    $this.removeClass('btn-primary').addClass('btn-danger');
                    $this.text(unFollowLang);
                } else {
                    $this.removeClass('btn-danger').addClass('btn-primary');
                    $this.text(followLang);
                }

                if (typeof result.followers_count_short !== 'undefined') {
                    $('.js-user-followers-count')
                        .text(result.followers_count_short)
                        .attr('data-raw-count', result.followers_count);
                }

                if (typeof result.following_count_short !== 'undefined') {
                    $('.js-user-following-count')
                        .text(result.following_count_short)
                        .attr('data-raw-count', result.following_count);
                }
            }
        }).fail(function (xhr) {
            const title = 'Request failed';
            const message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Please try again.';

            if (typeof showToast === 'function') {
                showToast('error', title, message);
            }
        }).always(function () {
            $this.removeClass('loadingbar').prop('disabled', false);
        });
    });

    /**************
     * Send Message
     * **********/

    $('body').on('click', '.js-send-message', function (e) {
        e.preventDefault();

        const path = $(this).attr('data-path');

        handleBasicModal(path, sendMessageLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end">
                    <button type="button" class="js-send-message-submit btn btn-sm btn-primary">${sendMessageLang}</button>
                </div>`;
            $footer.html(footerHtml);

            refreshCaptcha()

        }, '', '32rem')

    });

    $('body').on('click', '.js-send-message-submit', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $this.closest('.js-custom-modal').find('form');
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path)
    });

    // Courses
    var tabsLoadMore = {}

    $('body').on('click', '.js-profile-tab-load-more-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const elId = $this.attr('data-el');
        const path = $this.attr('data-path');
        const $row = $(`#${elId}`)

        if (typeof tabsLoadMore[elId] === "undefined") {
            tabsLoadMore[elId] = {
                page: 1
            }
        }

        const page = tabsLoadMore[elId].page + 1;

        const data = {
            page: page
        }

        $this.addClass('loadingbar').prop('disabled', true);

        $.post(path, data, function (result) {
            if (result) {
                tabsLoadMore[elId].page = page;

                if (result.data) {
                    $row.append(result.data)
                }

                if (!result.has_more_item) {
                    $this.parent().remove()
                }
            }
        }).always(function () {
            $this.removeClass('loadingbar').prop('disabled', false);
        })
    })


    /*******
    * Calendar
    *  */



})(jQuery)
