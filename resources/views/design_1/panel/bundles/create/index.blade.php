@extends('design_1.web.layouts.app', ['appFooter' => false])

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("create-course") }}">
    <link rel="stylesheet" href="/assets/design_1/css/panel-elzatuna.css">
    <style>
        .create-course-progress-step.cursor-pointer {
            transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        }

        .create-course-progress-step.cursor-pointer:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(7, 41, 35, 0.08);
        }

        .create-course-nav-btn.cursor-pointer {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .create-course-nav-btn.cursor-pointer:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(7, 41, 35, 0.1);
        }

        #saveAsDraft {
            cursor: pointer;
            transition: color .2s ease, opacity .2s ease;
        }

        #saveAsDraft:hover {
            color: #072923 !important;
            opacity: 1;
        }
    </style>
@endpush

@section('content')
    <form method="post" action="/panel/bundles/{{ !empty($bundle) ? $bundle->id .'/update' : 'store' }}" id="webinarForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="current_step" value="{{ !empty($currentStep) ? $currentStep : 1 }}">
        <input type="hidden" name="draft" value="no" id="forDraft"/>
        <input type="hidden" name="get_next" value="no" id="getNext"/>
        <input type="hidden" name="get_step" value="0" id="getStep"/>


        <div class="container mt-80 pb-100">
            {{-- Progress --}}
            @include('design_1.panel.bundles.create.includes.progress')

            {{-- Steps Inputs --}}
            @include("design_1.panel.bundles.create.steps.step_{$currentStep}")
        </div>


        {{-- Bottom Actions --}}
        @include('design_1.panel.bundles.create.includes.bottom_actions')

    </form>
@endsection

@push('scripts_bottom')
    <script>
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var zoomJwtTokenInvalid = '{{ trans('webinars.zoom_jwt_token_invalid') }}';
        var hasZoomApiToken = '{{ (!empty($authUser->zoomApi) and !empty($authUser->zoomApi->api_key) and !empty($authUser->zoomApi->api_secret)) ? 'true' : 'false' }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
    </script>

    <script src="/assets/design_1/js/panel/create_webinar.min.js"></script>
    <script src="/assets/design_1/js/panel/webinar_content_locale.min.js"></script>
@endpush
