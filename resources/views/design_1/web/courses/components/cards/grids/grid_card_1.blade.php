@php
    $cardIdx = $idx ?? ($loop->iteration ?? 1);
    $colors = ['#C8CD06', '#BDEA42'];
    $accent = $colors[($cardIdx - 1) % 2];
    $rate = $course->getRate();
    $hasDiscount = $course->bestTicket() && $course->bestTicket(true)['percent'] > 0;
@endphp

<a href="{{ $course->getUrl() }}" class="ez-course-card">
    <div class="ez-course-card__media" style="background: {{ $accent }};">
        @if(!empty($course->getImage()))
            <img src="{{ $course->getImage() }}" alt="{{ $course->title }}" class="ez-course-card__image">
        @else
            <div class="ez-course-card__pattern" aria-hidden="true"></div>
        @endif

        <span class="ez-course-card__num">Nº {{ str_pad($cardIdx, 2, '0', STR_PAD_LEFT) }}</span>

        <div class="ez-course-card__tags">
            @if($hasDiscount)
                <span class="ez-course-card__tag ez-course-card__tag--discount">
                    {{ $course->bestTicket(true)['percent'] }}% {{ trans('public.off') }}
                </span>
            @endif
            @if($rate >= 4.8)
                <span class="ez-course-card__tag ez-course-card__tag--top">TOP RATED</span>
            @endif
        </div>

        @if(!empty($rate) and $rate > 0)
            <span class="ez-course-card__rate">
                <x-iconsax-bol-star-1 width="11px" height="11px"/>
                <span>{{ $rate }}</span>
            </span>
        @endif
    </div>

    <div class="ez-course-card__body">
        <h3 class="ez-course-card__title">{{ clean($course->title, 'title') }}</h3>

        @if(!empty($course) && (is_null($course->university_id) || !empty($course->university) || !empty($course->faculty)))
            <div class="ez-course-card__sub">
                <span>{{ !empty($course->university) ? $course->university->name : trans('update.all_universities') }}</span>
                @if(!empty($course->faculty))
                    <span> · {{ $course->faculty->name }}</span>
                @endif
            </div>
        @endif

        <div class="ez-course-card__teacher">
            <img src="{{ $course->teacher->getAvatar(40) }}" alt="{{ $course->teacher->full_name }}" class="ez-course-card__teacher-avatar">
            <div class="ez-course-card__teacher-meta">
                <span class="ez-course-card__teacher-name">{{ $course->teacher->full_name }}</span>
                @if(!empty($course->category))
                    <span class="ez-course-card__teacher-cat">{{ trans('public.in') }} {{ $course->category->title }}</span>
                @endif
            </div>
        </div>

        <div class="ez-course-card__footer">
            <div class="ez-course-card__meta">
                <x-iconsax-lin-clock-1 width="14" height="14" style="color: var(--muted);"/>
                <span>{{ convertMinutesToHourAndMinute($course->duration) }} {{ trans('home.hours') }}</span>
            </div>
            <div class="ez-course-card__price">
                @if(!empty($showCoursePoints))
                    {{ trans('update.n_points', ['count' => $course->points]) }}
                @else
                    @include("design_1.web.courses.components.price_horizontal", ['courseRow' => $course])
                @endif
            </div>
        </div>
    </div>
</a>
