@php
    $authUser = auth()->user();
    $showAllChecked = request()->has('show_all') ? request()->boolean('show_all') : true;
@endphp

<div class="ez-sidebar__head">
    <h3 class="ez-sidebar__title">Filter.</h3>
    <a href="{{ $pageBasePath }}" class="ez-sidebar__reset">Reset all</a>
</div>
<div class="ez-sidebar__count">{{ trans('update.courses') }}</div>

{{-- All Courses (auth user toggle) --}}
@if(!empty($authUser) and $authUser->isUser())
    <div class="ez-filter-group" aria-expanded="true">
        <button type="button" class="ez-filter-group__head js-ez-toggle">
            <span class="ez-filter-group__title">{{ trans('webinars.all_courses') }}</span>
            <span class="ez-filter-group__chev">
                <x-iconsax-lin-add class="icons" width="11" height="11"/>
            </span>
        </button>
        <div class="ez-filter-group__body">
            <label class="d-flex align-items-center cursor-pointer" for="filter_show_all" style="gap: 10px;">
                <input type="hidden" name="show_all" value="0">
                <input type="checkbox" name="show_all" value="1" id="filter_show_all" {{ $showAllChecked ? 'checked' : '' }}
                       style="accent-color: var(--ink); width: 16px; height: 16px;">
                <span style="font-size: 14px; color: var(--ink);">{{ trans('webinars.all_courses') }}</span>
            </label>
        </div>
    </div>
@endif

{{-- Category Filters And Options --}}
@if(!empty($category) and !empty($category->filters))
    @foreach($category->filters as $filter)
        <div class="ez-filter-group" aria-expanded="true">
            <button type="button" class="ez-filter-group__head js-ez-toggle">
                <span class="ez-filter-group__title">{{ $filter->title }}</span>
                <span class="ez-filter-group__chev">
                    <x-iconsax-lin-add class="icons" width="11" height="11"/>
                </span>
            </button>
            <div class="ez-filter-group__body">
                @foreach($filter->options as $option)
                    <label class="d-flex align-items-center cursor-pointer" for="filter_option_id_{{ $option->id }}" style="gap: 10px;">
                        <input type="checkbox" name="filter_option[]" value="{{ $option->id }}" id="filter_option_id_{{ $option->id }}"
                               style="accent-color: var(--ink); width: 16px; height: 16px;">
                        <span style="font-size: 14px; color: var(--ink);">{{ $option->title }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
@endif

{{-- Price --}}
<div class="ez-filter-group" aria-expanded="true">
    <button type="button" class="ez-filter-group__head js-ez-toggle">
        <span class="ez-filter-group__title">{{ trans('public.price') }}</span>
        <span class="ez-filter-group__chev">
            <x-iconsax-lin-add class="icons" width="11" height="11"/>
        </span>
    </button>
    <div class="ez-filter-group__body">
        <div class="d-flex align-items-center" style="gap: 8px;">
            <div class="form-group mb-0" style="flex: 1;">
                <input type="tel" readonly value="{{ trans('update.free') }}" class="js-filters-min-price form-control input-xs text-center"
                       style="background: var(--cream); color: var(--ink); border: 1px solid var(--line); font-size: 12px;">
            </div>
            <div class="form-group mb-0" style="flex: 1;">
                <input type="tel" readonly value="{{ handlePrice($filterMaxPrice) }}" class="js-filters-max-price form-control input-xs text-center"
                       style="background: var(--cream); color: var(--ink); border: 1px solid var(--line); font-size: 12px;">
            </div>
        </div>

        <div class="course-list-price-range range wrunner-value-bottom no-bottom-value-note mt-8"
             id="priceRange"
             data-minLimit="0"
             data-maxLimit="{{ $filterMaxPrice }}"
             data-step="100">
            <input type="hidden" name="min_price" value="" class="js-range-input-view-data">
            <input type="hidden" name="max_price" value="" class="js-range-input-view-data">
        </div>
    </div>
</div>

{{-- Instructor --}}
<div class="ez-filter-group" aria-expanded="true">
    <button type="button" class="ez-filter-group__head js-ez-toggle">
        <span class="ez-filter-group__title">{{ trans('public.instructor') }}</span>
        <span class="ez-filter-group__chev">
            <x-iconsax-lin-add class="icons" width="11" height="11"/>
        </span>
    </button>
    <div class="ez-filter-group__body">
        <select name="instructor" class="form-control searchable-select"
                data-allow-clear="true"
                data-placeholder="{{ trans('update.search_and_select_instructor') }}"
                data-api-path="/users/search"
                data-item-column-name="full_name"
                data-option="just_teachers"
                style="background: var(--cream); color: var(--ink); border: 1px solid var(--line);">
        </select>
    </div>
</div>

{{-- Rating --}}
<div class="ez-filter-group" aria-expanded="true">
    <button type="button" class="ez-filter-group__head js-ez-toggle">
        <span class="ez-filter-group__title">{{ trans('update.rating') }}</span>
        <span class="ez-filter-group__chev">
            <x-iconsax-lin-add class="icons" width="11" height="11"/>
        </span>
    </button>
    <div class="ez-filter-group__body">
        @foreach([5, 4, 3, 2, 1] as $rateNum)
            <label class="d-flex align-items-center justify-content-between cursor-pointer" for="rating_{{ $rateNum }}" style="gap: 10px;">
                <span class="d-flex align-items-center" style="gap: 10px;">
                    <input type="radio" name="rating" id="rating_{{ $rateNum }}" value="{{ $rateNum }}"
                           {{ (request()->get('rating') == $rateNum) ? 'checked' : '' }}
                           style="accent-color: var(--ink); width: 16px; height: 16px;">
                    @include('design_1.web.components.rate', [
                         'rate' => $rateNum,
                         'rateCount' => false,
                         'rateClassName' => ''
                     ])
                </span>
                <span style="font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--muted);">
                    {{ !empty($coursesRatingsCount[$rateNum]) ? $coursesRatingsCount[$rateNum] : 0 }}
                </span>
            </label>
        @endforeach
    </div>
</div>

{{-- CTA: request a course --}}
<div class="ez-sidebar__cta">
    <div class="ez-sidebar__cta-title">Can't find your course?</div>
    <div class="ez-sidebar__cta-body">Tell us your syllabus. We launch ~3 new courses every month based on requests.</div>
    <a href="/contact?type=request_course" class="ez-sidebar__cta-link">Request a course →</a>
</div>

@push('scripts_bottom')
<script>
(function(){
    // Filter group collapse toggle
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.js-ez-toggle');
        if (!btn) return;
        e.preventDefault();
        var group = btn.closest('.ez-filter-group');
        if (!group) return;
        var open = group.getAttribute('aria-expanded') !== 'false';
        group.setAttribute('aria-expanded', open ? 'false' : 'true');
        var body = group.querySelector('.ez-filter-group__body');
        if (body) body.style.display = open ? 'none' : 'grid';
    });

    // Sidebar show/hide toggle on mobile/tablet
    function initSidebarToggle() {
        var toggleBtn = document.querySelector('.js-ez-sidebar-toggle');
        var sidebar   = document.querySelector('.js-ez-sidebar');
        if (!toggleBtn || !sidebar) return;

        function checkBreakpoint() {
            if (window.innerWidth <= 991) {
                toggleBtn.style.display = 'flex';
            } else {
                toggleBtn.style.display = 'none';
                sidebar.classList.add('is-open');
            }
        }

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('is-open');
            var isOpen = sidebar.classList.contains('is-open');
            toggleBtn.querySelector('span').textContent = isOpen ? 'Hide filters' : 'Filters';
        });

        window.addEventListener('resize', checkBreakpoint);
        checkBreakpoint();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebarToggle);
    } else {
        initSidebarToggle();
    }
})();
</script>
@endpush
