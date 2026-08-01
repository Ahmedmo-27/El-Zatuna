(function ($) {
    "use strict";

    const DEFAULT_CONFIG = {
        userName: '',
        userEmail: '',
        userId: '',
        blackScreenDuration: 8000,
        watermarkEnabled: true,
    };

    let config = {...DEFAULT_CONFIG};
    let globalListenersAttached = false;
    const protectedContainers = new WeakSet();
    const containerPlayers = new WeakMap();

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function getWatermarkLabel() {
        const parts = [];

        if (config.userName) {
            parts.push(config.userName);
        }

        if (config.userEmail) {
            parts.push(config.userEmail);
        }

        if (config.userId) {
            parts.push('ID: ' + config.userId);
        }

        return parts.join(' • ') || 'Protected content';
    }

    function buildWatermarkHtml() {
        const label = escapeHtml(getWatermarkLabel());
        const copies = [];

        for (let i = 0; i < 6; i++) {
            copies.push(
                `<span class="video-player-watermark__text" style="--watermark-index:${i}">${label}</span>`
            );
        }

        return `<div class="video-player-watermark-layer" aria-hidden="true">${copies.join('')}</div>`;
    }

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

        if (typeof window.fileVideoPlayer !== 'undefined' && window.fileVideoPlayer) {
            return window.fileVideoPlayer;
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

    function isAnyProtectedVideoPlaying() {
        return $('.learning-page__file-player-card.video-player-protected').toArray().some(function (container) {
            const video = findVideoElement(container);

            return video && !video.paused && !video.ended;
        });
    }

    function activateBlackShield(container, reason) {
        if (!container) {
            $('.learning-page__file-player-card.video-player-protected').each(function () {
                activateBlackShield(this, reason);
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
            deactivateBlackShield(container);
        }, config.blackScreenDuration);

        $container.data('blackShieldTimer', timer);
    }

    function deactivateBlackShield(container) {
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
            e.preventDefault();
            e.stopPropagation();
            deactivateBlackShield(container);
        });
    }

    function protectContainer(container, plyrPlayer) {
        if (!container || protectedContainers.has(container)) {
            if (container && plyrPlayer) {
                containerPlayers.set(container, plyrPlayer);
            }

            return;
        }

        const $container = $(container);

        $container.addClass('video-player-protected');

        if (config.watermarkEnabled && !$container.find('.video-player-watermark-layer').length) {
            $container.append(buildWatermarkHtml());
        }

        if (!$container.find('.video-player-black-shield').length) {
            $container.append(buildShieldHtml());
        }

        if (plyrPlayer) {
            containerPlayers.set(container, plyrPlayer);
        }

        attachContainerListeners(container);
        protectedContainers.add(container);
    }

    function refreshProtection() {
        $('.learning-page__file-player-card').each(function () {
            protectContainer(this);
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
                activateBlackShield(null, 'capture-stream');
                return originalVideoCaptureStream.apply(this, arguments);
            };
        }

        if (HTMLCanvasElement.prototype.captureStream) {
            const originalCanvasCaptureStream = HTMLCanvasElement.prototype.captureStream;

            HTMLCanvasElement.prototype.captureStream = function () {
                activateBlackShield(null, 'canvas-capture');
                return originalCanvasCaptureStream.apply(this, arguments);
            };
        }

        if (navigator.mediaDevices && typeof navigator.mediaDevices.getDisplayMedia === 'function') {
            const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia.bind(navigator.mediaDevices);

            navigator.mediaDevices.getDisplayMedia = function () {
                activateBlackShield(null, 'display-media');
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

        $(document).on('keyup.videoProtection', function (e) {
            if (e.key === 'PrintScreen' || e.keyCode === 44) {
                activateBlackShield(null, 'print-screen');

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText('').catch(function () {
                    });
                }
            }
        });

        $(document).on('keydown.videoProtection', function (e) {
            if (e.metaKey && e.shiftKey && ['3', '4', '5'].includes(String(e.key))) {
                activateBlackShield(null, 'mac-screenshot');
            }

            if (e.key === 'PrintScreen' || e.keyCode === 44) {
                activateBlackShield(null, 'print-screen');
            }
        });

        $(document).on('copy.videoProtection', function () {
            if (isAnyProtectedVideoPlaying()) {
                activateBlackShield(null, 'copy');
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden && isAnyProtectedVideoPlaying()) {
                activateBlackShield(null, 'visibility-hidden');
            }
        });

        window.addEventListener('blur', function () {
            if (isAnyProtectedVideoPlaying()) {
                activateBlackShield(null, 'window-blur');
            }
        });

        if (window.matchMedia) {
            const motionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');

            if (motionQuery.matches) {
                $('body').addClass('video-player-watermark-static');
            }
        }
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
            activateBlackShield(null, reason || 'manual');
        },
    };

})(jQuery);
