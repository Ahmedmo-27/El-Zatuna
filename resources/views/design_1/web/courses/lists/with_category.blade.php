@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("courses_lists") }}">
    <link rel="stylesheet" href="/assets/design_1/css/course-list-elzatuna.css">
    <link rel="stylesheet" href="/assets/design_1/css/list-filters-elzatuna.css">
    <link rel="stylesheet" href="/assets/design_1/css/editorial-lists.css?v={{ @filemtime(public_path('assets/design_1/css/editorial-lists.css')) }}">
@endpush

@section("content")
    <main class="editorial-lists" style="background: var(--cream); color: var(--ink); padding-bottom: 96px;">

        {{-- ──────────────────────────────────────────────── HERO ──── --}}
        <section class="ez-list-hero">
            <div class="ez-container">
                <div class="ez-list-hero__eyebrow">
                    <span>—— {{ strtoupper($category->title) }}</span>
                    <span>· {{ count($courses) }} courses ·</span>
                    <span>FALL '25</span>
                </div>

                <div class="ez-list-hero__grid">
                    <div>
                        <div class="ez-list-hero__kicker">
                            <a href="/" style="color: var(--muted);">{{ getPlatformName() }}</a>
                            <span style="color: var(--ink);">·</span>
                            {{ trans('update.courses') }}
                        </div>

                        <h1 class="ez-list-hero__title">{{ $category->title }}</h1>
                        @if(!empty($category->subtitle))
                            <p class="ez-list-hero__lede" style="margin-top: 24px;">
                                <span class="muted">{{ $category->subtitle }}</span>
                            </p>
                        @endif
                    </div>

                    <div style="padding-bottom: 12px;">
                        <p class="ez-list-hero__lede">
                            <span class="muted">Filter by university, faculty, professor, year.</span>
                            Every course is built around a specific syllabus at a specific
                            Egyptian university — taught by someone who passed it in the
                            last two years.
                        </p>

                        <div class="ez-list-stats">
                            <div>
                                <div class="ez-list-stat__k">{{ count($courses) }}</div>
                                <div class="ez-list-stat__v">live courses</div>
                            </div>
                            <div>
                                <div class="ez-list-stat__k">12</div>
                                <div class="ez-list-stat__v">universities</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form action="{{ $pageBasePath }}" class="js-get-view-data-by-timeout-change" data-container-id="listsContainer">
            <div class="ez-container">

                {{-- ── Top toolbar ── --}}
                @include("design_1.web.courses.lists.includes.top_filters")

                {{-- ── Sidebar + grid ── --}}
                <button type="button" class="ez-sidebar-toggle js-ez-sidebar-toggle" style="display:none;">
                    <x-iconsax-lin-setting-4 class="icons" width="14px" height="14px"/>
                    <span>Filters</span>
                </button>

                <div class="ez-lists-layout">

                    <aside class="ez-sidebar js-ez-sidebar">
                        @include("design_1.web.courses.lists.includes.left_filters")
                    </aside>

                    <div>
                        <div class="ez-list-stats-bar">
                            <span class="ez-list-stats-bar__count">
                                Showing {{ str_pad(count($courses), 2, '0', STR_PAD_LEFT) }} courses
                            </span>
                            <span style="color: var(--muted);">{{ $category->title }} catalog.</span>
                        </div>

                        <div id="listsContainer" data-body=".js-lists-body" data-view-data-path="{{ $pageBasePath }}">
                            @if(count($courses))
                                <div class="js-lists-body @if(request()->get('card') == 'list') row @else ez-card-grid @endif">
                                    @if(request()->get('card') == "list")
                                        @include('design_1.web.courses.components.cards.rows.index', ['courses' => $courses, 'rowCardClassName' => "col-12 mt-24"])
                                    @else
                                        @foreach($courses as $course)
                                            @include('design_1.web.courses.components.cards.grids.grid_card_1', ['course' => $course, 'idx' => $loop->iteration])
                                        @endforeach
                                    @endif
                                </div>
                            @else
                                <div class="js-lists-body">
                                    <div class="ez-empty">
                                        <div class="ez-empty__title">No courses available.</div>
                                        <div class="ez-empty__body">Try clearing some filters — or request a new course.</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Pagination --}}
                            <div id="pagination" class="js-ajax-pagination mt-40" data-container-id="listsContainer" data-container-items=".js-lists-body">
                                {!! $pagination !!}
                            </div>
                        </div>

                        {{-- Seo Content --}}
                        @if(!empty($category->bottom_seo_title) and !empty($category->bottom_seo_content))
                            <section class="bg-gray-100 p-16 rounded-24 border-gray-200 mt-48">
                                <h3 class="font-14">{{ $category->bottom_seo_title }}</h3>
                                <div class="mt-12 text-gray-500">{!! nl2br($category->bottom_seo_content) !!}</div>
                            </section>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </main>
@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="{{ getDesign1ScriptPath("swiper_slider") }}"></script>
    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>

    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>
    <script src="{{ getDesign1ScriptPath("courses_lists") }}"></script>
@endpush
