@php
    $progressSteps = [
        1 => [
            'name' => 'basic_information',
            'icon' => 'note-2',
            'bg' => '#E8F1FF',
            'color' => '#1D4ED8',
        ],

        2 => [
            'name' => 'extra_information',
            'icon' => 'note-add',
            'bg' => '#F3E8FF',
            'color' => '#7C3AED',
        ],

        3 => [
            'name' => 'pricing',
            'icon' => 'empty-wallet',
            'bg' => '#ECFDF3',
            'color' => '#059669',
        ],

        4 => [
            'name' => 'content',
            'icon' => 'document-cloud',
            'bg' => '#FFF7ED',
            'color' => '#EA580C',
        ],

        5 => [
            'name' => 'prerequisites',
            'icon' => 'archive-tick',
            'bg' => '#EEF2FF',
            'color' => '#4F46E5',
        ],

        6 => [
            'name' => 'faq',
            'icon' => 'bill',
            'bg' => '#FEF3C7',
            'color' => '#D97706',
        ],

        7 => [
            'name' => 'quiz_certificate',
            'icon' => 'clipboard-tick',
            'bg' => '#FCE7F3',
            'color' => '#DB2777',
        ],

    ];

    if (empty(getGeneralOptionsSettings('direct_publication_of_courses'))) {
        $progressSteps[8] = [
            'name' => 'message_to_reviewer',
            'icon' => 'shield-search',
            'bg' => '#E5F7F2',
            'color' => '#0F766E',
        ];
    }

@endphp

<div class="position-relative d-flex flex-wrap align-items-start justify-content-between p-25 rounded-16 bg-white create-course-progress" style="gap: 24px; row-gap: 24px;">
    <div class="webinar-progress-mask"></div>

    @foreach($progressSteps as $key => $progressStep)
        @php
            $isActiveStep = ($currentStep == $key);
        @endphp

        <div class="js-get-next-step create-course-progress-step {{ $isActiveStep ? 'active d-flex' : 'd-none d-lg-flex' }} flex-column align-items-center text-center cursor-pointer" data-step="{{ $key }}" style="--step-color: {{ $progressStep['color'] }}; --step-bg: {{ $progressStep['bg'] }};" @if(!$isActiveStep) data-tippy-content="{{ trans('public.' . $progressStep['name']) }}" @endif>
            <div class="create-course-progress-step__icon d-flex-center rounded-circle">
                @svg("iconsax-lin-{$progressStep['icon']}", ['height' => 34, 'width' => 34, 'class' => 'create-course-progress-step__icon-svg'])
            </div>

            <div class="create-course-progress-step__content mt-12">
                <p class="create-course-progress-step__number font-12 mb-0">{{ trans('webinars.progress_step', ['step' => $key,'count' => $stepCount]) }}</p>
                <h6 class="create-course-progress-step__title font-14 font-weight-bold mt-4 mb-0">{{ trans('public.' . $progressStep['name']) }}</h6>
            </div>
        </div>
    @endforeach

</div>
