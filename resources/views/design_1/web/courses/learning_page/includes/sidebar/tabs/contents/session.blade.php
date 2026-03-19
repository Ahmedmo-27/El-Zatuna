@php
    $checkSequenceContent = $session->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

    $sessionPersonalNote = $session->personalNote()->where('user_id', $authUser->id)->first();
    $hasPersonalNote = (!empty($sessionPersonalNote) and !empty($sessionPersonalNote->note));

    $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);
@endphp

@php
    $sessionDuration = !empty($session->duration) ? convertMinutesToHourAndMinute($session->duration) : null;
@endphp

<div class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-16 cursor-pointer js-content-tab-item {{ $hasSequenceContentError ? 'js-sequence-content-error-modal' : '' }}"
     data-type="{{ $type }}"
     data-id="{{ $session->id }}"
     data-passed-error="{{ !empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : '' }}"
     data-access-days-error="{{ !empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : '' }}"
>
    <div class="d-flex align-items-center">
        <div class="position-relative d-flex-center size-48 rounded-12 bg-gray-200">
            <x-iconsax-bul-video class="icons text-gray-500" width="24px" height="24px"/>

            @if($hasSequenceContentError)
                <div class="sidebar-item-lock-icon d-flex-center rounded-circle bg-white">
                    <x-iconsax-bol-lock-circle class="icons text-danger" width="16px" height="16px"/>
                </div>
            @endif
        </div>

        <div class="learning-page-item-details ml-8">
            <span class="d-block font-weight-bold font-14 text-dark">{{ truncate($session->title, 27) }}</span>
            <div class="learning-page-item-meta d-flex flex-wrap align-items-center gap-8 mt-4">
                <span class="learning-page-item-meta__chip d-inline-flex align-items-center gap-4">
                    <x-iconsax-lin-video class="icons" width="14px" height="14px"/>
                    <span>{{ trans('update.live') }}</span>
                </span>

                @if(!empty($sessionDuration))
                    <span class="learning-page-item-meta__chip d-inline-flex align-items-center gap-4">
                        <x-iconsax-lin-clock-1 class="icons" width="14px" height="14px"/>
                        <span>{{ $sessionDuration }}</span>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-8">
        @if($hasPersonalNote)
            <div class="">
                <x-iconsax-bul-note class="icons text-gray-500" width="16px" height="16px"/>
            </div>
        @endif

        @if(!empty($session->checkPassedItem()))
            <div class="">
                <x-iconsax-bul-tick-circle class="icons text-primary" width="16px" height="16px"/>
            </div>
        @endif
    </div>
</div>
