@extends('design_1.web.layouts.app', ['appFooter' => false, 'appHeader' => false])

@push('styles_top')
    @php
        $learningPageCssPath = getDesign1StylePath('learning_page');
        $learningPageCssSep = str_contains($learningPageCssPath, '?') ? '&' : '?';
        $learningPageCssVersion = file_exists(public_path('assets/design_1/css/parts/learning_page.min.css'))
            ? filemtime(public_path('assets/design_1/css/parts/learning_page.min.css'))
            : time();

        $coursePagesCssVersion = file_exists(public_path('assets/design_1/css/course-pages-elzatuna.css'))
            ? filemtime(public_path('assets/design_1/css/course-pages-elzatuna.css'))
            : time();
    @endphp

    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/vendors/plyr.io/plyr.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("learning_page_noticeboards") }}">
    <link rel="stylesheet" href="{{ $learningPageCssPath }}{{ $learningPageCssSep }}v={{ $learningPageCssVersion }}">
    <link rel="stylesheet" href="/assets/design_1/css/panel-elzatuna.css">
    <link rel="stylesheet" href="/assets/design_1/css/course-pages-elzatuna.css?v={{ $coursePagesCssVersion }}">

    <style>
        .learning-page__file-player-card {
            max-width: 1120px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            height: auto !important;
            aspect-ratio: 16 / 9;
            min-height: 240px;
            max-height: min(76vh, 700px);
            overflow: hidden !important;
        }

        .learning-page__file-player-card .js-file-player-el,
        .learning-page__file-player-card .plyr,
        .learning-page__file-player-card .plyr--video,
        .learning-page__file-player-card .plyr__video-wrapper,
        .learning-page__file-player-card iframe,
        .learning-page__file-player-card video {
            width: 100%;
            height: 100% !important;
        }

        .learning-page__file-player-card .plyr,
        .learning-page__file-player-card .plyr--video {
            display: flex;
            flex-direction: column;
            --plyr-color-main: #d4da2c;
            --plyr-video-control-color: #faffdf;
            --plyr-video-control-background: rgba(7, 41, 35, 0.75);
        }

        .learning-page__file-player-card .plyr__video-wrapper {
            flex: 1 1 auto;
            min-height: 0;
            background-color: #000;
        }

        .learning-page__file-player-card .plyr__controls {
            z-index: 12;
            background: linear-gradient(180deg, rgba(7, 41, 35, 0) 0%, rgba(7, 41, 35, 0.88) 52%) !important;
            padding: 12px 14px !important;
        }

        .learning-page__file-player-card .plyr__control--overlaid {
            border: 2px solid rgba(250, 255, 223, 0.8);
            background: rgba(7, 41, 35, 0.62);
        }

        .learning-page__file-player-card video,
        .learning-page__file-player-card .plyr__video-wrapper video {
            object-fit: contain;
            background-color: #000;
            display: block;
        }

        @media (max-width: 991px) {
            .learning-page__file-player-card {
                min-height: 220px;
                max-height: 60vh;
            }
        }
    </style>
@endpush

@section('content')
    <div class="learning-page d-flex bg-[#FAFFE0] text-[#072923] min-h-screen">
        <div class="learning-page__main">
            {{-- Top Header --}}
            @include('design_1.web.courses.learning_page.includes.top_header')

            {{-- Page Content --}}
            @include('design_1.web.courses.learning_page.includes.main_content')
        </div>

        {{-- Sidebar --}}
        @include('design_1.web.courses.learning_page.includes.sidebar.index')
    </div>

    {{-- Noticeboards --}}
    @include('design_1.web.courses.learning_page.noticeboards.index')
@endsection

@push('scripts_bottom')
    @php
        $videoPlayerHelpersScriptPath = getDesign1ScriptPath('video_player_helpers');
        $videoPlayerHelpersScriptSep = str_contains($videoPlayerHelpersScriptPath, '?') ? '&' : '?';
        $videoPlayerHelpersScriptVersion = file_exists(public_path('assets/design_1/js/parts/video_player_helpers.min.js'))
            ? filemtime(public_path('assets/design_1/js/parts/video_player_helpers.min.js'))
            : time();
    @endphp

    <script>
        var courseUrl = '{{ $course->getUrl() }}';
        var courseSlug = '{{ $course->slug }}';
        var courseLearningUrl = '{{ $course->getLearningPageUrl() }}';
        var defaultItemType = '{{ !empty(request()->get('type')) ? request()->get('type') : (!empty($userLearningLastView) ? $userLearningLastView->item_type : '') }}'
        var defaultItemId = '{{ !empty(request()->get('item')) ? request()->get('item') : (!empty($userLearningLastView) ? $userLearningLastView->item_id : '') }}'
        var loadFirstContent = {{ (!empty($dontAllowLoadFirstContent) and $dontAllowLoadFirstContent) ? 'false' : 'true' }}; // allow to load first content when request item is empty
        // Langs
        var learningPageEmptyContentTitleLang = '{{ trans('update.learning_page_empty_content_title') }}';
        var learningPageEmptyContentHintLang = '{{ trans('update.learning_page_empty_content_hint') }}';
        var pleaseWaitLang = '{{ trans('update.please_wait') }}';
        var pleaseWaitForTheContentLang = '{{ trans('update.please_wait_for_the_content_to_load') }}';
        var newCourseNoteLang = '{{ trans('update.new_course_note') }}';
        var editCourseNoteLang = '{{ trans('update.edit_course_note') }}';
        var courseNoteLang = '{{ trans('update.course_note') }}';
        var saveNoteLang = '{{ trans('update.save_note') }}';
        var deleteNoteLang = '{{ trans('update.delete_note') }}';
        var submittedOnLang = '{{ trans('update.submitted_on') }}';
        var editLang = '{{ trans('public.edit') }}';
        var accessDeniedLang = '{{ trans('update.access_denied') }}';
        var noteLang = '{{ trans('update.note') }}';
        var accessDeniedModalFooterHintLang = '{{ trans('update.your_access_will_be_delegated_automatically') }}';
        var rateAssignmentLang = '{{ trans('update.rate_assignment') }}';
        var passGradeLang = '{{ trans('update.pass_grade') }}';
        var submitGradeLang = '{{ trans('update.submit_grade') }}';
        var submitQuestionLang = '{{ trans('update.submit_question') }}';
        var courseCompletedLang = '{{ trans('update.course_completed') }}';

        var videoProtectionConfig = {
            userName: @json($user->full_name ?? ''),
            userEmail: @json($user->email ?? ''),
            userId: @json($user->id ?? ''),
            watermarkEnabled: true,
            blackScreenDuration: 8000,
        };
    </script>

    <script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
    <script src="/assets/vendors/plyr.io/plyr.min.js"></script>

    <script src="{{ $videoPlayerHelpersScriptPath }}{{ $videoPlayerHelpersScriptSep }}v={{ $videoPlayerHelpersScriptVersion }}"></script>
    <script>
        (function () {
            if (typeof window.makeVideoPlayerHtml !== 'function') {
                return;
            }

            const originalMakeVideoPlayerHtml = window.makeVideoPlayerHtml;

            // Learning page should always fill the responsive player container.
            window.makeVideoPlayerHtml = function (path, storage, height, tagId, thumbnail, mimeType) {
                const forcedHeight = '100%';

                return originalMakeVideoPlayerHtml(path, storage, forcedHeight, tagId, thumbnail, mimeType);
            };
        })();
    </script>
    <script src="{{ getDesign1ScriptPath("video_player_protection") }}"></script>
    <script>
        (function () {
            if (typeof window.VideoPlayerProtection === 'undefined') {
                return;
            }

            window.VideoPlayerProtection.init(window.videoProtectionConfig || {});
        })();
    </script>
    <script src="{{ getDesign1ScriptPath("learning_page_noticeboards") }}"></script>
    <script src="{{ getDesign1ScriptPath("learning_page") }}"></script>
@endpush
