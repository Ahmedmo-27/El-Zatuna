(function ($) {
    "use strict"


    var fileVideoPlayer;

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
        }
    };

    window.pauseVideoPlayer = function () {
        if (fileVideoPlayer !== undefined) {
            fileVideoPlayer.pause();
        }
    };


})(jQuery)
