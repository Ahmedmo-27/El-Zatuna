@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
    <style>
        /* Chapter and content styling */
        .chapter-section {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin-top: 32px;
        }
        
        .chapter-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px;
            border-radius: 16px;
            border: 1px dashed #dee2e6;
        }
        
        .chapter-info {
            display: flex;
            align-items: center;
        }
        
        .chapter-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background-color: rgba(0, 123, 255, 0.2);
            border-radius: 12px;
            margin-right: 8px;
        }
        
        .add-chapter-btn {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #007bff;
        }
        
        .add-chapter-btn:hover {
            opacity: 0.8;
        }
        
        /* Content accordion styling */
        .content-accordion {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 12px;
            margin-top: 16px;
        }
        
        .content-accordion-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .content-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 120px 32px;
            text-align: center;
        }
        
        .empty-state-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 12px;
            background-color: rgba(0, 123, 255, 0.3);
            margin-bottom: 12px;
        }
        
        /* Add content dropdown */
        .add-content-dropdown {
            position: relative;
        }
        
        .add-content-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 220px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            margin-top: 8px;
        }
        
        .add-content-menu.show {
            display: block;
        }
        
        .add-content-menu-item {
            padding: 12px 16px;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: background-color 0.2s ease;
        }
        
        .add-content-menu-item:hover,
        .add-content-menu-item:focus {
            background-color: #f8f9fa;
            outline: none;
        }
        
        .add-content-menu-item:active {
            background-color: #e9ecef;
        }
        
        .add-content-menu-item-icon {
            margin-right: 8px;
            flex-shrink: 0;
        }
        
        /* Smooth animations */
        .fade-in {
            animation: fadeInSlide 0.4s ease;
        }
        
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Remove visibility: collapse from .collapse */
        .collapse {
            visibility: visible !important;
        }
    </style>
@endpush

<div class="chapter-section">
    <div class="chapter-header">
        <div class="chapter-info">
            <div class="chapter-icon">
                <x-iconsax-bul-category-2 class="icons text-primary" width="24px" height="24px"/>
            </div>
            <div>
                <h5 class="font-14 font-weight-bold mb-0">{{ trans('public.chapters') }}</h5>
                <p class="font-12 text-gray-500 mb-0 mt-4">{{ trans('update.define_different_sections_and_organize_the_content_inside_them') }}</p>
            </div>
        </div>
        <div class="js-add-chapter add-chapter-btn" data-webinar-id="{{ $webinar->id }}">
            <x-iconsax-lin-add class="icons text-primary" width="16px" height="16px"/>
            <span class="text-primary ml-4">{{ trans('public.new_chapter') }}</span>
        </div>
    </div>

    {{-- Chapter Items --}}
    @include('design_1.panel.webinars.create.includes.chapter_contents')
</div>

{{-- Hidden Forms for New Content --}}
@if($webinar->isWebinar())
    <div id="newSessionForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.session',['webinar' => $webinar])
    </div>
@endif

<div id="newFileForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.file',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('new_interactive_file'))
    <div id="newInteractiveFileForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.interactive_file',['webinar' => $webinar])
    </div>
@endif

<div id="newTextLessonForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.text_lesson',['webinar' => $webinar])
</div>

@if(getFeaturesSettings('webinar_assignment_status'))
    <div id="newAssignmentForm" class="d-none">
        @include('design_1.panel.webinars.create.includes.accordions.assignment',['webinar' => $webinar])
    </div>
@endif

<div id="newQuizForm" class="d-none">
    @include('design_1.panel.webinars.create.includes.accordions.quiz',['webinar' => $webinar, 'quizInfo' => null, 'webinarChapterPages' => true])
</div>

<div id="changeChapterModalHtml" class="d-none">
    @include("design_1.panel.webinars.create.modals.change_chapter")
</div>

@push('scripts_bottom')
    <script>
        var newChapterLang = '{{ trans('public.new_chapter') }}';
        var editChapterLang = '{{ trans('public.edit_chapter') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveSuccessLang = '{{ trans('webinars.success_store') }}';
        var quizzesSectionLang = '{{ trans('quiz.quizzes_section') }}';
        var newQuestionLang = '{{ trans('update.new_question') }}';
        var editQuestionLang = '{{ trans('update.edit_question') }}';
        var changeChapterLang = '{{ trans('update.change_chapter') }}';
    </script>

    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/assets/design_1/js/panel/quiz_create.min.js"></script>
    <script src="/assets/design_1/js/panel/file-drag-drop.js"></script>
@endpush
