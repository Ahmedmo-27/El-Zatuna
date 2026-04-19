(function ($) {
    "use strict"


    var fileVideoPlayer;
    var fileVideoRefreshInProgress = false;
    var lastFileVideoRefreshAt = 0;
    var videoPerformanceByFile = {};
    var videoPerformanceFlushTimer = null;

    window.convertVimeoLinkToPlay = function (path) {
        path = path.trim();

        if (path.includes('player.vimeo.com/video')) return path;

        if (!/^https?:\/\//i.test(path)) path = 'https://' + path;

        try {
            const url = new URL(path);
            if (url.hostname.replace(/^www\./, '') === 'vimeo.com') {
                const id = url.pathname.split('/').filter(Boolean).pop();
                if (/^\d+$/.test(id)) return `https://player.vimeo.com/video/${id}`;
            }
        } catch {
        }

        return path;
    }

    window.makeVideoPlayerHtml = function (path, storage, height, tagId, thumbnail = null, mimeType = null) {
        const controls = [
            'play-large',
            'rewind',
            'play',
            'fast-forward',
            'progress',
            'current-time',
            'duration',
            'mute',
            'volume',
            'settings',
            'fullscreen'
        ];

        const resolvedHeight = (height === null || height === undefined || height === '') ? '100%' : height;
        const isDirectVideoSource = (sourcePath, sourceMimeType = null) => {
            const normalizedMimeType = (sourceMimeType || '').toLowerCase();

            if (normalizedMimeType.startsWith('video/')) {
                return true;
            }

            return /\.(mp4|webm|ogg|m3u8|mov|m4v)(\?.*)?$/i.test((sourcePath || '').toLowerCase());
        };

        let html = '';
        let options = {
            autoplay: false,
            preload: 'auto',
            hideControls: true,
            controls,
            settings: ['speed', 'quality', 'captions'],
            previewThumbnails: {
                enabled: !!thumbnail,
                src: thumbnail ?? ''
            }
        };
        let usePlyr = true;

        if (storage === 'youtube') {
            html = `<div class="plyr__video-embed w-100 h-100" id="${tagId}" data-poster="${thumbnail ?? ''}">
              <iframe
                src="${path}?origin=${siteDomain}&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=0&amp;controls=0"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                class="img-cover rounded-16"
                data-poster="${thumbnail ?? ''}"
              ></iframe>
            </div>`;
            // Tighten Plyr options for YouTube
            options.clickToPlay = false;
            options.disableContextMenu = true;
            options.youtube = {
                rel: 0,
                modestbranding: 1,
                iv_load_policy: 3,
                fs: 0,
                disablekb: 1,
                playsinline: 1,
                controls: 0
            };
        } else if (storage === "vimeo") {
            let vimeoPath = convertVimeoLinkToPlay(path);

            html = `<div class="plyr__video-embed w-100 h-100" id="${tagId}" data-poster="${thumbnail ?? ''}">
              <iframe
                src="${vimeoPath}?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                allowfullscreen
                allowtransparency
                allow="autoplay"
                class="img-cover rounded-16"
                data-poster="${thumbnail ?? ''}"
              ></iframe>
            </div>`;

        } else if (storage === "secure_host") {
            if (isDirectVideoSource(path, mimeType)) {
                const sourceType = mimeType || 'video/mp4';

                html = `<video id="${tagId}" class="plyr-io-video" controls preload="auto" width="100%" height="${resolvedHeight}" data-poster="${thumbnail ?? ''}">
                    <source src="${path}" type="${sourceType}"/>
                </video>`;
            } else {
                html = '<iframe src="' + path + '" class="img-cover bg-gray-200" frameborder="0" allowfullscreen="true" ></iframe>';
                usePlyr = false;
            }
        } else {
            const sourceType = mimeType || 'video/mp4';

            html = `<video id="${tagId}" class="plyr-io-video" controls preload="auto" width="100%" height="${resolvedHeight}" data-poster="${thumbnail ?? ''}">
                <source src="${path}" type="${sourceType}"/>
            </video>`;
        }

        return {
            html: html,
            options: options,
            usePlyr,
        };
    };

    window.handleVideoByFileId = function (fileId, $contentEl, callback) {

        closeVideoPlayer();

        $.post('/course/getFilePath', {file_id: fileId}, function (result) {

            if (result && result.code === 200) {
                const storage = result.storage;

                const videoTagId = 'videoPlayer' + fileId;

                const {html, options, usePlyr} = makeVideoPlayerHtml(result.path, storage, '100%', videoTagId, undefined, result.mime_type);

                if ($contentEl) {
                    $contentEl.html(html);
                }

                if (usePlyr) {
                    fileVideoPlayer = new Plyr(`#${videoTagId}`, options);

                    // Auto-recover R2 stream when signed URL expires or stream stalls.
                    if (storage === 'r2') {
                        attachR2StreamRecovery(fileId, fileVideoPlayer, storage);
                    }
                }

                callback();
            } else {
                showToast("error", notAccessToastTitleLang, notAccessToastMsgLang);
            }
        }).fail(err => {
            showToast("error", notAccessToastTitleLang, notAccessToastMsgLang);
        });
    };

    window.closeVideoPlayer = function () {
        if (fileVideoPlayer !== undefined) {
            fileVideoPlayer.stop();
            fileVideoPlayer.destroy();
            fileVideoPlayer = undefined;
        }
    };

    window.pauseVideoPlayer = function () {
        if (fileVideoPlayer !== undefined) {
            fileVideoPlayer.pause();
        }
    };

    function getCsrfToken() {
        return window.csrfToken || $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val() || '';
    }

    function ensureVideoPerformanceEntry(fileId, storage = 'r2') {
        if (!videoPerformanceByFile[fileId]) {
            videoPerformanceByFile[fileId] = {
                fileId,
                storage,
                stalls: 0,
                recoveries: 0,
                bufferEvents: 0,
                totalRecoveryMs: 0,
                maxRecoveryMs: 0,
                lastPosition: 0,
                lastFlushedAt: 0,
                dirty: false,
            };
        }

        return videoPerformanceByFile[fileId];
    }

    function markVideoPerformanceDirty(fileId, storage = 'r2') {
        const entry = ensureVideoPerformanceEntry(fileId, storage);
        entry.dirty = true;
    }

    function scheduleVideoPerformanceFlush() {
        if (videoPerformanceFlushTimer !== null) {
            return;
        }

        videoPerformanceFlushTimer = setInterval(function () {
            flushVideoPerformanceMetrics(false);
        }, 60000);
    }

    function flushVideoPerformanceMetrics(forceFlush = false) {
        if (typeof courseSlug === 'undefined' || !courseSlug) {
            return;
        }

        const path = `/course/learning/${courseSlug}/video-performance`;
        const csrf = getCsrfToken();
        const now = Date.now();

        Object.keys(videoPerformanceByFile).forEach(function (fileId) {
            const entry = videoPerformanceByFile[fileId];

            if (!entry) {
                return;
            }

            const enoughTimePassed = (now - (entry.lastFlushedAt || 0)) >= 60000;

            if (!forceFlush && (!entry.dirty || !enoughTimePassed)) {
                return;
            }

            if (fileVideoPlayer && !Number.isNaN(fileVideoPlayer.currentTime)) {
                entry.lastPosition = Number(fileVideoPlayer.currentTime) || entry.lastPosition || 0;
            }

            const avgRecovery = entry.recoveries > 0
                ? (entry.totalRecoveryMs / entry.recoveries)
                : 0;

            $.post(path, {
                _token: csrf,
                file_id: Number(fileId),
                stalls: entry.stalls,
                recoveries: entry.recoveries,
                buffer_events: entry.bufferEvents,
                total_recovery_ms: Math.round(entry.totalRecoveryMs),
                avg_recovery_ms: Math.round(avgRecovery),
                max_recovery_ms: Math.round(entry.maxRecoveryMs),
                playback_seconds: Math.round(entry.lastPosition),
                last_position: Math.round(entry.lastPosition),
                source: 'r2_stream',
            });

            entry.lastFlushedAt = now;
            entry.dirty = false;
        });
    }

    function attachR2StreamRecovery(fileId, player, storage = 'r2') {
        if (!player || !player.media) {
            return;
        }

        const performanceEntry = ensureVideoPerformanceEntry(fileId, storage);
        scheduleVideoPerformanceFlush();

        player.media.addEventListener('timeupdate', function () {
            performanceEntry.lastPosition = Number(player.currentTime) || performanceEntry.lastPosition || 0;
        });

        player.media.addEventListener('waiting', function () {
            performanceEntry.bufferEvents += 1;
            markVideoPerformanceDirty(fileId, storage);
        });

        const maybeRefresh = function () {
            const now = Date.now();

            if (fileVideoRefreshInProgress || (now - lastFileVideoRefreshAt) < 5000) {
                return;
            }

            fileVideoRefreshInProgress = true;
            lastFileVideoRefreshAt = now;
            performanceEntry.stalls += 1;

            const resumeTime = Number(player.currentTime) || 0;
            const shouldResumePlayback = !player.paused;
            const recoveryStartedAt = (window.performance && typeof window.performance.now === 'function')
                ? window.performance.now()
                : Date.now();

            $.post('/course/getFilePath', {file_id: fileId}, function (result) {
                if (!(result && result.code === 200 && result.path)) {
                    markVideoPerformanceDirty(fileId, storage);
                    return;
                }

                const sourceType = result.mime_type || 'video/mp4';
                player.source = {
                    type: 'video',
                    sources: [
                        {
                            src: result.path,
                            type: sourceType,
                        }
                    ]
                };

                player.once('canplay', function () {
                    const endedAt = (window.performance && typeof window.performance.now === 'function')
                        ? window.performance.now()
                        : Date.now();
                    const recoveryMs = Math.max(0, endedAt - recoveryStartedAt);
                    performanceEntry.recoveries += 1;
                    performanceEntry.totalRecoveryMs += recoveryMs;
                    performanceEntry.maxRecoveryMs = Math.max(performanceEntry.maxRecoveryMs, recoveryMs);

                    if (resumeTime > 0) {
                        try {
                            player.currentTime = Math.max(0, resumeTime - 1);
                        } catch (e) {
                            // Ignore resume seek errors.
                        }
                    }

                    if (shouldResumePlayback) {
                        player.play().catch(function () {
                            // Browser autoplay policy may block this; user can click play.
                        });
                    }

                    markVideoPerformanceDirty(fileId, storage);
                });
            }).always(function () {
                fileVideoRefreshInProgress = false;
            });
        };

        player.media.addEventListener('stalled', maybeRefresh);
        player.media.addEventListener('error', maybeRefresh);
        player.on('error', maybeRefresh);
    }

    window.addEventListener('beforeunload', function () {
        flushVideoPerformanceMetrics(true);
    });

    document.addEventListener('visibilitychange', function () {
        if (document.visibilityState === 'hidden') {
            flushVideoPerformanceMetrics(true);
        }
    });


})(jQuery)
