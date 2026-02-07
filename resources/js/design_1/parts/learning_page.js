(function ($) {
    "use strict"

    var $mainContent = $('#mainContent');

    $(document).ready(function () {
        handleDefaultItemLoaded();

        handleTrackSpentTime();
    });

    function handleTrackSpentTime() {
        const path = `${courseLearningUrl}/track-time`;

        setInterval(function () {
            $.post(path, {}, function (result) {
                if (result && result.force_reload) {
                    window.location.reload();
                }
            })
        }, 10000)
    }

    function contentEmptyStateHtml() {
        const html = `<div class="bg-white rounded-24 p-16">
            <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160">
                <div class="">
                    <img src="/assets/design_1/img/courses/learning_page/empty_state.svg" alt="" class="img-fluid" width="285px" height="212px">
                </div>

                <h3 class="mt-12 font-16">${learningPageEmptyContentTitleLang}</h3>
                <div class="mt-8 text-gray-500">${learningPageEmptyContentHintLang}</div>
            </div>
        </div>`

        $mainContent.html(html)
    }

    function handleDefaultItemLoaded() {
        const allItems = $('.js-content-tab-item');

        if (allItems && allItems.length && defaultItemType && defaultItemType !== '' && defaultItemId && defaultItemId !== '') {
            for (const item of allItems) {
                const $item = $(item);
                const type = $item.attr('data-type');
                const id = $item.attr('data-id');

                if (type === defaultItemType && id === defaultItemId) {
                    $item.trigger('click');

                    activeAccordionByItem($item)
                }
            }
        } else if (allItems && loadFirstContent && loadFirstContent !== 'false') {
            if (allItems.length) {
                const item = allItems[0];
                const $item = $(item);
                $item.trigger('click');

                activeAccordionByItem($item)
            } else {
                contentEmptyStateHtml();
            }
        }
    }

    function activeAccordionByItem($item) {
        const $accordion = $item.closest('.js-accordion-parent');

        if ($accordion.length) {
            const $btn = $accordion.find('.js-accordion-collapse-arrow');
            $btn.trigger('click');

            const $scroller = $('#learningPageSidebar .simplebar-content-wrapper');
            setTimeout(function () {
                $scroller.animate({
                    scrollTop: $item.offset().top - 200
                }, 500);
            }, 1500)
        }
    }

    function handleLoadingHtml() {
        const html = `<div class="bg-white rounded-24 p-16">
            <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160">
                <div class="">
                    <img src="/assets/default/img/loading.svg" alt="" class="img-fluid" width="80px" height="80px">
                </div>

                <h3 class="mt-12 font-16">${pleaseWaitLang}</h3>
                <div class="mt-8 text-gray-500">${pleaseWaitForTheContentLang}</div>
            </div>
        </div>`;

        $mainContent.html(html)
    }

    function addItemToUrlBar(itemId, itemType, extraData = {}) {
        let currentPath = window.location.pathname;
        let newPath = currentPath.replace('/forum', ''); // when i forum page

        const params = new URLSearchParams();
        params.set('type', itemType);
        params.set('item', itemId);

        if (extraData) {
            for (const key in extraData) {
                params.set(key, extraData[key]);
            }
        }

        window.history.pushState({}, '', `${newPath}?${params.toString()}`);
    }

    function handleVideoPlayer() {
        const $players = $('.js-file-player-el');

        if ($players.length) {
            for (const plyr of $players) {
                const player = new Plyr(plyr);
                
                // Add video completion tracking
                const $playerElement = $(plyr);
                const videoId = $playerElement.attr('id');
                
                if (videoId && videoId.includes('fileVideo')) {
                    const fileId = videoId.replace('fileVideo', '');
                    
                    // Track video progress
                    player.on('timeupdate', function(event) {
                        const currentTime = player.currentTime;
                        const duration = player.duration;
                        
                        if (duration > 0) {
                            const percentWatched = (currentTime / duration) * 100;
                            
                            // Auto-complete when video reaches 90%
                            if (percentWatched >= 90) {
                                autoMarkItemComplete('file_id', fileId);
                                // Remove event listener to avoid multiple calls
                                player.off('timeupdate');
                            }
                        }
                    });
                }
            }
        }
    }

    function handleContentItemHtml(itemId, itemType, extraData = {}) {
        const path = `/course/learning/${courseSlug}/itemInfo`
        const data = {
            id: itemId,
            type: itemType,
            ...extraData,
        };

        $.post(path, data, function (result) {
            if (result.code === 200) {
                $mainContent.html(result.html);

                tippyTooltip();
                handleVideoPlayer();
                
                // Initialize document scroll tracking for text lessons
                if (itemType === 'text_lesson') {
                    setTimeout(handleDocumentScrollTracking, 500);
                }
            }
        }).fail(err => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })

        addItemToUrlBar(itemId, itemType, extraData)
    }

    $('body').on('click', '.js-content-tab-item', function (e) {
        const $this = $(this);

        if (!$this.hasClass('active')) {
            const type = $this.attr('data-type');
            const id = $this.attr('data-id');
            const extraKey = $this.attr('data-extra-key') ?? null;
            const extraValue = $this.attr('data-extra-value') ?? null;

            let extraData = {};
            if (extraKey && extraValue) {
                extraData[extraKey] = extraValue;
            }

            $('.js-content-tab-item').removeClass('active');
            $this.addClass('active');

            $('#learningPageSidebar').removeClass('show-drawer')

            if (!$this.hasClass('js-sequence-content-error-modal')) {
                handleLoadingHtml();
                handleContentItemHtml(id, type, extraData)
            }
        }
    })

    $('body').on('click', '.js-learning-file-video-player-btn', function (e) {
        e.preventDefault();

        const $this = $(this)
        const $el = $this.closest('.js-learning-file-video-player-box');
        const fileId = $this.attr('data-id');

        handleVideoByFileId(fileId, $el, function () {

        });
    })


    /**
     * Auto Mark Item as Complete
     * */
    const autoCompletedItems = new Set();

    function autoMarkItemComplete(itemType, itemId) {
        const key = `${itemType}_${itemId}`;
        
        // Check if already auto-completed in this session
        if (autoCompletedItems.has(key)) {
            return;
        }

        const path = `/course/learning/${courseSlug}/autoMarkComplete`;
        const data = {
            item: itemType,
            item_id: itemId
        };

        $.post(path, data, function (result) {
            if (result.code === 200 && !result.already_completed) {
                // Mark as completed in session
                autoCompletedItems.add(key);
                
                // Update progress bar
                const $percentBar = $('.js-course-learning-progress-bar-percent');
                const $percentNum = $('.js-course-learning-progress-percent');
                
                if (result.learning_progress_percent && $percentBar.length) {
                    $percentBar.css('width', result.learning_progress_percent + '%');
                    $percentNum.text(`${result.learning_progress_percent}%`);
                }
                
                // Update checkbox if exists
                const $checkbox = $(`.js-passed-item-toggle[data-item-name="${itemType}"][value="${itemId}"]`);
                if ($checkbox.length && !$checkbox.is(':checked')) {
                    $checkbox.prop('checked', true);
                }
                
                // Show toast notification
                if (result.title && result.msg) {
                    showToast("success", result.title, result.msg);
                }
                
                // Check for course completion
                if (result.learning_progress_percent && result.learning_progress_percent >= 100) {
                    handleCourseCompletedModal();
                }
            }
        }).fail(err => {
            // Silently fail - this is auto-completion, don't disturb user
            console.log('Auto-complete failed:', err);
        });
    }

    /**
     * Track Document (Text Lesson) Scrolling
     * */
    function handleDocumentScrollTracking() {
        const $scrollContainer = $('.learning-page__main-content .simplebar-content-wrapper');
        const $textLessonContent = $('.js-text-lesson-content');
        
        if ($textLessonContent.length && $scrollContainer.length) {
            const textLessonId = $textLessonContent.attr('data-text-lesson-id');
            
            if (textLessonId) {
                let scrollCheckEnabled = true;
                
                $scrollContainer.on('scroll.textLesson', function() {
                    if (!scrollCheckEnabled) return;
                    
                    const scrollTop = $scrollContainer.scrollTop();
                    const scrollHeight = $scrollContainer[0].scrollHeight;
                    const clientHeight = $scrollContainer.height();
                    
                    // Check if scrolled to bottom (with 100px tolerance)
                    if (scrollTop + clientHeight >= scrollHeight - 100) {
                        scrollCheckEnabled = false; // Disable further checks
                        $scrollContainer.off('scroll.textLesson'); // Remove event listener
                        autoMarkItemComplete('text_lesson_id', textLessonId);
                    }
                });
            }
        }
    }

    /**
     * I Passed Item Toggle
     * */
    $('body').on('change', '.js-passed-item-toggle', function (e) {
        const $this = $(this);
        const courseSlug = $this.attr("data-course-slug");
        const item = $this.attr("data-item-name");
        const itemId = $this.val();
        const status = this.checked;

        const path = `/course/${courseSlug}/learningStatus`;

        const data = {
            item: item, item_id: itemId, status: status
        };

        const $percentBar = $('.js-course-learning-progress-bar-percent');
        const $percentNum = $('.js-course-learning-progress-percent');

        $.post(path, data, function (result) {
            showToast("success", result.title, result.msg);

            if (result.learning_progress_percent && $percentBar.length) {
                $percentBar.css('width', result.learning_progress_percent + '%');
                $percentNum.text(`${result.learning_progress_percent}%`)
            }

            if (result.learning_progress_percent && result.learning_progress_percent >= 100) {
                handleCourseCompletedModal()
            }

        }).fail(err => {
            $this.prop('checked', !status);
            showToast('error', oopsLang, somethingWentWrongLang);
        });
    });

    function handleCourseCompletedModal() {
        const path = `/course/${courseSlug}/learning-status-completed-modal`;

        handleBasicModal(path, courseCompletedLang, function (result, $body, $footer) {
            $footer.remove()
        }, '', '37rem')
    }

    /******
     * Personal Note
     * ****/
    $('body').on('click', '.js-add-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemType = $this.attr('data-item-type')
        const itemId = $this.attr('data-item-id')

        const path = `${courseLearningUrl}/personal-note/get-form?item_id=${itemId}&item_type=${itemType}`;

        handleBasicModal(path, newCourseNoteLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-end mt-25">
                <button type="button" class="js-save-personal-note btn btn-primary">${saveNoteLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '42rem')
    })

    $('body').on('click', '.js-save-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-personal-note-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path)
    })

    $('body').on('click', '.js-edit-personal-note', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemType = $this.attr('data-item-type')
        const itemId = $this.attr('data-item-id')

        const path = `${courseLearningUrl}/personal-note/get-details?item_id=${itemId}&item_type=${itemType}`;

        handleBasicModal(path, courseNoteLang, function (result, $body, $footer) {

            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                        <div class="">
                            <span class="d-block font-12 text-gray-500">${submittedOnLang}</span>
                            <span class="d-block font-12 text-gray-500 font-weight-bold mt-2">${result.submitted_on}</span>
                        </div>

                        <div class="d-flex align-items-center gap-24">
                            <a href="/course/personal-notes/${result.note_id}/delete" class="delete-action text-danger">${deleteNoteLang}</a>

                            <button type="button" class="js-add-personal-note btn btn-primary"
                                    data-item-id="${result.item_id}"
                                    data-item-type="${result.item_type}"
                            >${editLang}</button>
                        </div>
                    </div>`;

            $footer.html(footerHtml);

        }, '', '42rem')
    })

    /************
     * Session
     *
     * */
    $('body').on('click', '.js-check-again-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemId = $this.attr('data-id')
        const itemType = "session";

        handleLoadingHtml();
        handleContentItemHtml(itemId, itemType)
    })


    /************
     * Sequence Content
     * */
    $('body').on('click', '.js-sequence-content-error-modal', function (e) {
        e.preventDefault();
        const $this = $(this);
        const type = $this.attr('data-type');
        const id = $this.attr('data-id');

        const path = `/course/learning/${courseSlug}/itemSequenceContentInfo?type=${type}&item=${id}`;

        handleBasicModal(path, accessDeniedLang, function (result, $body, $footer) {
            const footerHtml = `<div class="">
                <h5 class="font-14 text-black">${noteLang}</h5>
                <p class="mt-4 font-12 text-gray-500">${accessDeniedModalFooterHintLang}</p>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });


    /************
     * Assignment Conversation
     * */
    $('body').on('click', '.js-send-assignment-conversation', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('.js-assignment-conversation-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });

    $('body').on('click', '.js-show-submit-rate', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-path');

        handleBasicModal(path, rateAssignmentLang, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-between">
                <div class="">
                    <div class="font-weight-bold text-black">${result.pass_grade}</div>
                    <div class="mt-4 font-12 text-gray-500">${passGradeLang}</div>
                </div>
                <button type="button" class="js-submit-rate-btn btn btn-primary btn-lg">${submitGradeLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-rate-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-assigment-submit-grade-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });


    /************
     * Forum
     * */
    $('body').on('click', '.js-forum-pin-toggle', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        if ($this.hasClass('text-gray-500')) {
            $this.removeClass('text-gray-500').addClass('text-warning');
        } else {
            $this.removeClass('text-warning').addClass('text-gray-500');
        }

        $.post(path, {}, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg);
            }
        }).fail(function () {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    });

    $('body').on('click', '.js-forum-question-action', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-submit-forum-question-btn btn btn-primary">${submitQuestionLang}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-forum-question-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-forum-question-form');
        const path = $form.attr('data-action')

        handleSendRequestItemForm($form, $this, path);
    });

    $('body').on('click', '.js-mark-as-resolved-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');
        const confirm = $this.attr('data-confirm');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-confirm-mark-as-resolved btn btn-primary" data-action="${path}">${confirm}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-confirm-mark-as-resolved', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        $this.addClass('loadingbar').prop('disabled', true);

        $.post(path, {}, function (result) {
            if (result.code === 200) {
                showToast('success', result.title, result.msg);

                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        }).fail(() => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    })

    $('body').on('click', '.js-answer-action-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');

        loadingSwl();
        const data = {};

        $.post(path, data, function (result) {
            if (result.code === 200) {
                const html = `<div class="d-flex-center flex-column text-center my-24">
                    <h4 class="font-14">${result.title}</h4>
                    <div class="mt-8 font-12 text-gray-500">${result.msg}</div>
                </div>`;

                Swal.fire({
                    html: html,
                    showConfirmButton: false,
                    icon: 'success',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1000)
            }
        }).fail(() => {
            showToast('error', oopsLang, somethingWentWrongLang);
        })
    })

    $('body').on('click', '.js-reply-forum-question', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $this.closest('form')
        const path = $form.attr('action');

        handleSendRequestItemForm($form, $this, path);
    })

    $('body').on('click', '.js-edit-forum-answer', function (e) {
        e.preventDefault();
        const $this = $(this);
        const path = $this.attr('data-action');
        const title = $this.attr('data-title');
        const confirm = $this.attr('data-confirm');

        handleBasicModal(path, title, function (result, $body, $footer) {
            const footerHtml = `<div class="d-flex align-items-center justify-content-end">

                <button type="button" class="js-submit-answer-update btn btn-primary">${confirm}</button>
            </div>`;
            $footer.html(footerHtml);

        }, '', '37rem')
    });

    $('body').on('click', '.js-submit-answer-update', function (e) {
        e.preventDefault();
        const $this = $(this);
        const $form = $('.js-forum-answer-form');
        const path = $form.attr('data-action');

        handleSendRequestItemForm($form, $this, path);
    })


    $('body').on('click', '.js-toggle-show-learning-page-sidebar-drawer', function (e) {
        e.preventDefault();

        const $sidebar = $('#learningPageSidebar');
        $sidebar.toggleClass('show-drawer')
    })

})(jQuery)
