@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("instructors_lists") }}">
    <link rel="stylesheet" href="/assets/design_1/css/list-filters-elzatuna.css">
    <link rel="stylesheet" href="/assets/design_1/css/editorial-lists.css?v={{ @filemtime(public_path('assets/design_1/css/editorial-lists.css')) }}">
@endpush

@section("content")
    <main class="editorial-lists" style="background: var(--cream); color: var(--ink); padding-bottom: 80px;">

        {{-- ──────────────────────────────────────────────── HERO ──── --}}
        <section class="ez-list-hero">
            <div class="ez-container">
                <div class="ez-list-hero__eyebrow">
                    <span>—— THE ROSTER</span>
                    <span>· {{ count($users) }} {{ trans('home.instructors') }} ·</span>
                    <span>FALL ENROLLMENT '25</span>
                </div>

                <div class="ez-list-hero__grid">
                    <div>
                        <div class="ez-list-hero__kicker">
                            {{ trans('home.instructors') }} <span style="color: var(--ink);">·</span> elzatuna
                        </div>

                        <h1 class="ez-list-hero__title">
                            Tutors who<br>
                            <em>remember</em><br>
                            being stuck.
                        </h1>
                    </div>

                    <div style="padding-bottom: 12px;">
                        <p class="ez-list-hero__lede">
                            Every tutor on the roster passed your course in the
                            <span class="muted"> last 1–3 years, at the same university, with the same professor.</span>
                            They remember the tricky bits because they were stuck on them too.
                        </p>

                        <div class="ez-list-stats">
                            <div>
                                <div class="ez-list-stat__k">{{ count($users) }}</div>
                                <div class="ez-list-stat__v">on roster</div>
                            </div>
                            <div>
                                <div class="ez-list-stat__k">4.8</div>
                                <div class="ez-list-stat__v">avg rating</div>
                            </div>
                            <div>
                                <div class="ez-list-stat__k">&lt;48h</div>
                                <div class="ez-list-stat__v">reply</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form action="/instructors" class="js-get-view-data-by-timeout-change" data-container-id="listsContainer">
            <div class="ez-container">

                {{-- ── Top filters (pill toolbar) ── --}}
                @include("design_1.web.instructors.includes.top_filters")

                {{-- ── Sidebar + grid ── --}}
                <button type="button" class="ez-sidebar-toggle js-ez-sidebar-toggle" style="display:none;">
                    <x-iconsax-lin-setting-4 class="icons" width="14px" height="14px"/>
                    <span>Filters</span>
                </button>

                <div class="ez-lists-layout">

                    <aside class="ez-sidebar js-ez-sidebar">
                        @include("design_1.web.instructors.includes.left_filters")
                    </aside>

                    <div>
                        <div class="ez-list-stats-bar">
                            <span class="ez-list-stats-bar__count">
                                Showing {{ str_pad(count($users), 2, '0', STR_PAD_LEFT) }} tutors
                            </span>
                            <span style="color: var(--muted);">Filter by university, faculty, rating.</span>
                        </div>

                        <div id="listsContainer" data-body=".js-lists-body" data-view-data-path="/instructors">
                            @if(count($users))
                                <div class="js-lists-body ez-card-grid">
                                    @foreach($users as $user)
                                        @include('design_1.web.instructors.components.cards.grids.grid_card_1', ['instructor' => $user, 'idx' => $loop->iteration])
                                    @endforeach
                                </div>
                            @else
                                <div class="js-lists-body">
                                    <div class="ez-empty">
                                        <div class="ez-empty__title">Nobody matches that.</div>
                                        <div class="ez-empty__body">Clear a filter — or apply to fill the gap yourself.</div>
                                    </div>
                                </div>
                            @endif

                            {{-- Pagination --}}
                            <div id="pagination" class="js-ajax-pagination mt-40" data-container-id="listsContainer" data-container-items=".js-lists-body">
                                {!! $pagination !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>

    </main>
@endsection

@push('scripts_bottom')
    <script>
        var selectedCloseIcon = `<x-iconsax-lin-add class="icons close-icon" width="16px" height="16px"/>`;

        (function ($) {
            'use strict';

            $('body').on('change', '.js-instructors-rating-filter', function () {
                const $current = $(this);

                if ($current.is(':checked')) {
                    $('.js-instructors-rating-filter').not($current).prop('checked', false);
                }
            });
        })(jQuery);
    </script>

    <script src="{{ getDesign1ScriptPath("get_view_data") }}"></script>
    <script src="{{ getDesign1ScriptPath("range_slider_helpers") }}"></script>

    <script src="{{ getDesign1ScriptPath("instructors_lists") }}"></script>
@endpush
