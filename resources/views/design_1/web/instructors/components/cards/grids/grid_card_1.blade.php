@php
    $cardIdx = $idx ?? ($loop->iteration ?? 1);
    $colors = ['#C8CD06', '#BDEA42', '#C8CD06', '#BDEA42'];
    $accent = $colors[($cardIdx - 1) % 4];
    $rate = $instructor->rates();
    $webinarsCount = $instructor->webinars()->where('status', 'active')->count();
    $bioText = !empty($instructor->bio) ? trim(strip_tags($instructor->bio)) : '';
@endphp

<a href="{{ $instructor->getProfileUrl() }}" class="ez-instructor-card">
    <div class="ez-instructor-card__media" style="background: {{ $accent }};">
        <img src="{{ $instructor->getAvatar(240) }}" alt="{{ $instructor->full_name }}" class="ez-instructor-card__avatar">

        <span class="ez-instructor-card__num">Nº {{ str_pad($cardIdx, 2, '0', STR_PAD_LEFT) }}</span>

        <span class="ez-instructor-card__rate">
            <x-iconsax-bol-star-1 width="11px" height="11px"/>
            <span>{{ (!empty($rate) and $rate > 0) ? $rate : '—' }}</span>
        </span>

        @if($instructor->verified)
            <span class="ez-instructor-card__verified" data-tippy-content="{{ trans('public.verified') }}">
                <x-tick-icon class="icons" width="12px" height="12px"/>
            </span>
        @endif
    </div>

    <h3 class="ez-instructor-card__name">{{ truncate($instructor->full_name, 28) }}</h3>
    <div class="ez-instructor-card__sub">
        {{ !empty($bioText) ? truncate($bioText, 70) : trans('public.instructor') }}
    </div>

    <div class="ez-instructor-card__footer">
        @if($webinarsCount > 0)
            <span class="ez-instructor-card__footer-courses">{{ trans('update.count_courses', ['count' => $webinarsCount]) }}</span>
        @else
            <span class="ez-instructor-card__footer-new">{{ trans('public.instructor') }}</span>
        @endif
        <span class="ez-instructor-card__footer-link">{{ trans('public.view_profile') }} →</span>
    </div>
</a>
