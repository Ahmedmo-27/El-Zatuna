@php
    // Step 3 (pricing) removed: course price is set by admin when reviewing the course
    // Steps 4, 5, 6 (prerequisites, FAQ, quiz & certificate) are disabled in the new flow.
    // Course creation now focuses on:
    // 1) basic information, 2) extra information, 3) content, and optionally 4) message to reviewer.
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

    ];

    if (empty(getGeneralOptionsSettings('direct_publication_of_courses'))) {
        // Final review step is step 4 in the new flow.
        $progressSteps[4] = [
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
            $isLockedStep = empty($webinar) && $key > 1;
            $isPreviousStep = !empty($webinar) && $key < $currentStep;
        @endphp

        @if($isPreviousStep)
            <a href="/panel/courses/{{ $webinar->id }}/step/{{ $key }}" class="create-course-progress-step {{ $isActiveStep ? 'active d-flex' : 'd-none d-lg-flex' }} flex-column align-items-center text-center text-decoration-none cursor-pointer" style="--step-color: {{ $progressStep['color'] }}; --step-bg: {{ $progressStep['bg'] }};" data-tippy-content="{{ trans('public.' . $progressStep['name']) }}">
                <div class="create-course-progress-step__icon d-flex-center rounded-circle">
                    @svg("iconsax-lin-{$progressStep['icon']}", ['height' => 34, 'width' => 34, 'class' => 'create-course-progress-step__icon-svg'])
                </div>

                <div class="create-course-progress-step__content mt-12">
                    <p class="create-course-progress-step__number font-12 mb-0">{{ trans('webinars.progress_step', ['step' => $key,'count' => $stepCount]) }}</p>
                    <h6 class="create-course-progress-step__title font-14 font-weight-bold mt-4 mb-0">{{ trans('public.' . $progressStep['name']) }}</h6>
                </div>
            </a>
        @else
            <div class="{{ !$isLockedStep ? 'js-get-next-step' : '' }} create-course-progress-step {{ $isActiveStep ? 'active d-flex' : 'd-none d-lg-flex' }} {{ $isLockedStep ? 'is-locked' : '' }} flex-column align-items-center text-center {{ !$isLockedStep ? 'cursor-pointer' : '' }}" data-step="{{ $key }}" style="--step-color: {{ $progressStep['color'] }}; --step-bg: {{ $progressStep['bg'] }};" @if(!$isActiveStep and !$isLockedStep) data-tippy-content="{{ trans('public.' . $progressStep['name']) }}" @endif @if($isLockedStep) data-tippy-content="Save the first step first to unlock the rest." @endif>
                <div class="create-course-progress-step__icon d-flex-center rounded-circle">
                    @svg("iconsax-lin-{$progressStep['icon']}", ['height' => 34, 'width' => 34, 'class' => 'create-course-progress-step__icon-svg'])
                </div>

                <div class="create-course-progress-step__content mt-12">
                    <p class="create-course-progress-step__number font-12 mb-0">{{ trans('webinars.progress_step', ['step' => $key,'count' => $stepCount]) }}</p>
                    <h6 class="create-course-progress-step__title font-14 font-weight-bold mt-4 mb-0">{{ trans('public.' . $progressStep['name']) }}</h6>
                </div>
            </div>
        @endif
    @endforeach

</div>
