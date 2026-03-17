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
            'bg' => '#FFF7ED',
            'color' => '#EA580C',
        ],

        4 => [
            'name' => 'content',
            'icon' => 'document-cloud',
            'bg' => '#E5F7F2',
            'color' => '#0F766E',
        ],

        5 => [
            'name' => 'faq',
            'icon' => 'bill',
            'bg' => '#FEF3C7',
            'color' => '#B45309',
        ],

        6 => [
            'name' => 'message_to_reviewer',
            'icon' => 'shield-search',
            'bg' => '#E0F2FE',
            'color' => '#0369A1',
        ],

    ];

@endphp

<div class="position-relative d-flex flex-wrap align-items-start justify-content-between p-25 rounded-16 bg-white create-course-progress" style="gap: 24px; row-gap: 24px;">
    <div class="webinar-progress-mask"></div>

    @foreach($progressSteps as $key => $progressStep)
        @php
            $isActiveStep = ($currentStep == $key);
            $isLockedStep = empty($bundle) && $key > 1;
            $isPreviousStep = !empty($bundle) && $key < $currentStep;
        @endphp

        @if($isPreviousStep)
            <div class="js-get-next-step create-course-progress-step d-none d-lg-flex flex-column align-items-center text-center cursor-pointer" data-step="{{ $key }}" style="--step-color: {{ $progressStep['color'] }}; --step-bg: {{ $progressStep['bg'] }};" data-tippy-content="{{ trans('public.' . $progressStep['name']) }}">
                <div class="create-course-progress-step__icon d-flex-center rounded-circle">
                    @svg("iconsax-lin-{$progressStep['icon']}", ['height' => 34, 'width' => 34, 'class' => 'create-course-progress-step__icon-svg'])
                </div>

                <div class="create-course-progress-step__content mt-12">
                    <p class="create-course-progress-step__number font-12 mb-0">{{ trans('webinars.progress_step', ['step' => $key,'count' => $stepCount]) }}</p>
                    <h6 class="create-course-progress-step__title font-14 font-weight-bold mt-4 mb-0">{{ trans('public.' . $progressStep['name']) }}</h6>
                </div>
            </div>
        @else
            <div class="{{ !$isLockedStep ? 'js-get-next-step' : '' }} create-course-progress-step {{ $isActiveStep ? 'active d-flex' : 'd-none d-lg-flex' }} {{ $isLockedStep ? 'is-locked' : '' }} flex-column align-items-center text-center {{ !$isLockedStep ? 'cursor-pointer' : '' }}" data-step="{{ $key }}" style="--step-color: {{ $progressStep['color'] }}; --step-bg: {{ $progressStep['bg'] }};" @if(!$isActiveStep and !$isLockedStep) data-tippy-content="{{ trans('public.' . $progressStep['name']) }}" @endif @if($isLockedStep) data-tippy-content="{{ trans('public.save_first_step_to_unlock') }}" @endif>
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
