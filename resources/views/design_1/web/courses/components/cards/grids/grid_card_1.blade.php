<div class="test-card-container d-block">
    <div class="course-grid-card-1 position-relative bg-[#072923] rounded-32 p-16">
    <div class="course-grid-card-1__mask"></div>

    <div class="position-relative z-index-2">
            <div class="course-grid-card-1__image bg-gray-200">
                @if($course->bestTicket() && $course->bestTicket(true)['percent'] > 0)
                    <div class="position-absolute z-index-1 bg-accent d-flex align-items-center justify-content-center py-4 px-8 mt-12 ml-12 rounded-pill">
                        <x-iconsax-bul-discount-shape class="icons text-white" width="20px" height="20px"/>
                        <span class="ml-4 text-white font-12">{{ $course->bestTicket(true)['percent'] }}% {{ trans('public.off') }}</span>
                    </div>
                @endif
                <a href="{{ $course->getUrl() }}" class="d-block w-full h-full">
                    <img src="{{ $course->getImage() }}" class="img-cover" alt="{{ $course->title }}">
                </a>
            </div>

        <div class="course-grid-card-1__body d-flex flex-column py-12 h-100">
            <div class="d-flex flex-column px-12 w-100">
                    <a href="{{ $course->getUrl() }}" class="text-decoration-none">
                        <h3 class="course-title font-16 font-weight-bold text-[#000000]" style="color: #000000 !important;">{{ clean($course->title,'title') }}</h3>
                    </a>

                @include('design_1.web.components.rate', ['rate' => $course->getRate(), 'rateCount' => $course->getRateCount(), 'rateClassName' => 'mt-12'])

                    <div class="d-flex align-items-center my-16">
                        <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="size-32 rounded-circle">
                        <img src="{{ $course->teacher->getAvatar(32) }}" class="img-cover rounded-circle" alt="{{ $course->teacher->full_name }}">
                        </a>

                    <div class="d-flex flex-column ml-4">
                            <a href="{{ $course->teacher->getProfileUrl() }}" target="_blank" class="font-14 font-weight-bold text-[#000000]" style="color: #000000 !important;">{{ $course->teacher->full_name }}</a>

                        @if(!empty($course->category))
                            <div class="d-inline-flex align-items-center gap-4 mt-2 font-12 text-[#000000]">
                                <span class="">{{ trans('public.in') }}</span>
                                    <a href="{{ $course->category->getUrl() }}" target="_blank" class="font-14 text-[#000000] text-ellipsis" style="color: #000000 !important;">{{ $course->category->title }}</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-auto pt-12 border-top-gray-100 px-12">
                <div class="d-flex align-items-center font-16 font-weight-bold text-[#000000]">
                        <a href="{{ $course->getUrl() }}" class="text-decoration-none text-[#000000] d-flex align-items-center" style="color: #000000 !important;">
                    @if(!empty($showCoursePoints))
                        <span>{{ trans('update.n_points', ['count' => $course->points]) }}</span>
                    @else
                        @include("design_1.web.courses.components.price_horizontal", ['courseRow' => $course])
                    @endif
                        </a>
                </div>

                <div class="d-flex align-items-center font-14 text-[#000000]">
                        <a href="{{ $course->getUrl() }}" class="text-decoration-none d-flex align-items-center" style="color: #000000 !important;">
                    <x-iconsax-lin-clock-1 class="icons text-[#000000]" style="color: #000000 !important;" width="16px" height="16px"/>
                    <span class="ml-4">{{ convertMinutesToHourAndMinute($course->duration) }}</span>
                    <span class="ml-4">{{ trans('home.hours') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
