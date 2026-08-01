(function ($) {
    "use strict";

    const DEFAULT_CONFIG = {
        blackScreenDuration: 12000,
        captureBlackScreenDuration: 20000,
    };

    let config = {...DEFAULT_CONFIG};
    let globalListenersAttached = false;
    let captureLockUntil = 0;
    const protectedContainers = new WeakSet();
    const containerPlayers = new WeakMap();

    function buildShieldHtml() {
        return `
            <div class="video-player-black-shield" aria-hidden="true">
                <div class="video-player-black-shield__message">
                    <span class="video-player-black-shield__icon">⛔</span>
                    <span class="video-player-black-shield__title">Content hidden</span>
                    <span class="video-player-black-shield__hint">Screenshots and screen recording are not allowed</span>
                </div>
            </div>
        `;
    }

    function findVideoElement(container) {
        return container.querySelector('video') || container.querySelector('.plyr video');
    }

    function findPlyrPlayer(container) {
        const mapped = containerPlayers.get(container);

        if (mapped) {
            return mapped;
        }

        if (typeof window.activeFileVideoPlayer !== 'undefined' && window.activeFileVideoPlayer) {
            return window.activeFileVideoPlayer;
        }

        return null;
    }

    function pauseContainerPlayback(container) {
        const plyrPlayer = findPlyrPlayer(container);
        const video = findVideoElement(container);

        if (plyrPlayer && typeof plyrPlayer.pause === 'function') {
            plyrPlayer.pause();
            return;
        }

        if (video && !video.paused) {
            video.pause();
        }
    }

    function pauseAllProtectedPlayback() {
        $('.learning-page__file-player-card.video-player-protected').each(function () {
            pauseContainerPlayback(this);
        });
    }

    function isTypingTarget(target) {
        if (!target) {
            return false;
        }

        const tag = (target.tagName || '').toLowerCase();

        return tag === 'input'
            || tag === 'textarea'
            || tag === 'select'
            || target.isContentEditable;
    }

    function isAnyProtectedVideoPlaying() {
        return $('.learning-page__file-player-card.video-player-protected').toArray().some(function (container) {
            const video = findVideoElement(container);

            return video && !video.paused && !video.ended;
        });
    }

    function isCaptureShortcut(event) {
        const key = String(event.key || '');
        const keyCode = event.keyCode || event.which;
        const winKey = event.metaKey || event.getModifierState('Meta') || event.getModifierState('OS');
        const shiftKey = event.shiftKey || event.getModifierState('Shift');
        const altKey = event.altKey || event.getModifierState('Alt');
        const ctrlKey = event.ctrlKey || event.getModifierState('Control');

        if (key === 'PrintScreen' || keyCode === 44) {
            return true;
        }

        if (winKey && shiftKey && /^s$/i.test(key)) {
            return true;
        }

        if (winKey && shiftKey && /^g$/i.test(key)) {
            return true;
        }

        if (winKey && altKey && /^r$/i.test(key)) {
            return true;
        }

        if (winKey && /^g$/i.test(key)) {
            return true;
        }

        if (altKey && keyCode === 44) {
            return true;
        }

        if (event.metaKey && event.shiftKey && ['3', '4', '5'].includes(key)) {
            return true;
        }

        return false;
    }

    function isSnippingToolFallback(event) {
        if (isTypingTarget(event.target)) {
            return false;
        }

        if (!isAnyProtectedVideoPlaying()) {
            return false;
        }

        const key = String(event.key || '');

        if (/^s$/i.test(key) && event.shiftKey && !event.ctrlKey && !event.altKey) {
            return true;
        }

        return false;
    }

    function activateBlackShield(container, reason, options) {
        const opts = options || {};
        const duration = opts.duration || (
            reason && String(reason).indexOf('capture') === 0
                ? config.captureBlackScreenDuration
                : config.blackScreenDuration
        );

        if (opts.lockCapture) {
            captureLockUntil = Date.now() + duration;
        }

        if (!container) {
            pauseAllProtectedPlayback();

            $('.learning-page__file-player-card.video-player-protected').each(function () {
                activateBlackShield(this, reason, {...opts, duration: duration});
            });

            return;
        }

        const $container = $(container);
        const $shield = $container.find('.video-player-black-shield');

        if (!$shield.length) {
            return;
        }

        pauseContainerPlayback(container);

        $container.addClass('video-player-protected--blocked');
        $shield.addClass('video-player-black-shield--active').attr('data-reason', reason || 'unknown');

        clearTimeout($container.data('blackShieldTimer'));

        const timer = setTimeout(function () {
            if (Date.now() < captureLockUntil) {
                activateBlackShield(container, reason, {...opts, duration: captureLockUntil - Date.now()});
                return;
            }

            deactivateBlackShield(container);
        }, duration);

        $container.data('blackShieldTimer', timer);
    }

    function deactivateBlackShield(container) {
        if (Date.now() < captureLockUntil) {
            return;
        }

        const $container = $(container);
        const timer = $container.data('blackShieldTimer');

        if (timer) {
            clearTimeout(timer);
            $container.removeData('blackShieldTimer');
        }

        $container.removeClass('video-player-protected--blocked');
        $container.find('.video-player-black-shield')
            .removeClass('video-player-black-shield--active')
            .removeAttr('data-reason');
    }

    function attachContainerListeners(container) {
        const $container = $(container);

        $container.off('click.videoProtectionShield');
        $container.on('click.videoProtectionShield', '.video-player-black-shield--active', function (e) {
            if (Date.now() < captureLockUntil) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }

            e.preventDefault();
            e.stopPropagation();
            deactivateBlackShield(container);
        });
    }

    function protectContainer(container, plyrPlayer) {
        if (!container) {
            return;
        }

        if (plyrPlayer) {
            containerPlayers.set(container, plyrPlayer);
        }

        if (protectedContainers.has(container)) {
            return;
        }

        const $container = $(container);

        $container.addClass('video-player-protected');

        if (!$container.find('.video-player-black-shield').length) {
            $container.append(buildShieldHtml());
        }

        attachContainerListeners(container);
        protectedContainers.add(container);
    }

    function refreshProtection() {
        $('.learning-page__file-player-card').each(function () {
            protectContainer(this);
        });
    }

    function handleCaptureAttempt(reason) {
        activateBlackShield(null, reason || 'capture-shortcut', {
            lockCapture: true,
            duration: config.captureBlackScreenDuration,
        });
    }

    function hookCaptureApis() {
        if (window.__videoCaptureApisHooked) {
            return;
        }

        window.__videoCaptureApisHooked = true;

        if (HTMLVideoElement.prototype.captureStream) {
            const originalVideoCaptureStream = HTMLVideoElement.prototype.captureStream;

            HTMLVideoElement.prototype.captureStream = function () {
                handleCaptureAttempt('capture-stream');
                return originalVideoCaptureStream.apply(this, arguments);
            };
        }

        if (HTMLCanvasElement.prototype.captureStream) {
            const originalCanvasCaptureStream = HTMLCanvasElement.prototype.captureStream;

            HTMLCanvasElement.prototype.captureStream = function () {
                handleCaptureAttempt('canvas-capture');
                return originalCanvasCaptureStream.apply(this, arguments);
            };
        }

        if (navigator.mediaDevices && typeof navigator.mediaDevices.getDisplayMedia === 'function') {
            const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia.bind(navigator.mediaDevices);

            navigator.mediaDevices.getDisplayMedia = function () {
                handleCaptureAttempt('display-media');
                return originalGetDisplayMedia.apply(navigator.mediaDevices, arguments);
            };
        }
    }

    function attachGlobalListeners() {
        if (globalListenersAttached) {
            return;
        }

        globalListenersAttached = true;

        hookCaptureApis();

        document.addEventListener('keydown', function (e) {
            if (isCaptureShortcut(e) || isSnippingToolFallback(e)) {
                handleCaptureAttempt('capture-shortcut');

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText('').catch(function () {
                    });
                }
            }
        }, true);

        document.addEventListener('keyup', function (e) {
            if (isCaptureShortcut(e)) {
                handleCaptureAttempt('capture-shortcut');
            }
        }, true);

        window.addEventListener('blur', function () {
            if (Date.now() < captureLockUntil) {
                handleCaptureAttempt('capture-blur');
                return;
            }

            if (isAnyProtectedVideoPlaying()) {
                handleCaptureAttempt('capture-blur');
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                return;
            }

            if (Date.now() < captureLockUntil || isAnyProtectedVideoPlaying()) {
                handleCaptureAttempt('capture-visibility');
            }
        });

        setInterval(function () {
            if (Date.now() >= captureLockUntil) {
                return;
            }

            $('.learning-page__file-player-card.video-player-protected').each(function () {
                const $container = $(this);

                if (!$container.hasClass('video-player-protected--blocked')) {
                    activateBlackShield(this, 'capture-guard', {
                        duration: captureLockUntil - Date.now(),
                    });
                }
            });
        }, 250);
    }

    window.VideoPlayerProtection = {
        init: function (userConfig) {
            config = {...DEFAULT_CONFIG, ...(userConfig || {})};
            attachGlobalListeners();
            refreshProtection();
        },

        protectContainer: protectContainer,

        refresh: refreshProtection,

        triggerBlackScreen: function (reason) {
            handleCaptureAttempt(reason || 'manual');
        },
    };

})(jQuery);
