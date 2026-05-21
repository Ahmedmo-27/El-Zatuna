<div class="ez-sidebar__head">
    <h3 class="ez-sidebar__title">Filter.</h3>
    <a href="/instructors" class="ez-sidebar__reset">Reset all</a>
</div>
<div class="ez-sidebar__count">{{ trans('home.instructors') }}</div>

{{-- Categories / Skills --}}
<div class="ez-filter-group" aria-expanded="true">
    <button type="button" class="ez-filter-group__head js-ez-toggle">
        <span class="ez-filter-group__title">{{ trans('product.instructor_skills') }}</span>
        <span class="ez-filter-group__chev">
            <x-iconsax-lin-add class="icons" width="11" height="11"/>
        </span>
    </button>
    <div class="ez-filter-group__body">
        <select class="js-skills-select form-control select2" data-minimum-results-for-search="Infinity" style="background: var(--cream); color: var(--ink); border: 1px solid var(--line);">
            <option value="">{{ trans('update.select_a_category') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->title }}</option>
            @endforeach
        </select>
        <div class="js-selected-category-filters d-flex flex-wrap gap-12 mt-12"></div>
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
            <label class="d-flex align-items-center cursor-pointer" for="rating_{{ $rateNum }}" style="gap: 10px;">
                <input type="checkbox" name="rating" id="rating_{{ $rateNum }}" value="{{ $rateNum }}"
                       class="js-instructors-rating-filter"
                       {{ (string) request()->get('rating') === (string) $rateNum ? 'checked' : '' }}
                       style="accent-color: var(--ink); width: 16px; height: 16px;">
                @include('design_1.web.components.rate', [
                     'rate' => $rateNum,
                     'rateCount' => false,
                     'rateClassName' => ''
                 ])
                <span style="font-size: 12px; color: var(--muted);">&amp; up</span>
            </label>
        @endforeach
    </div>
</div>

{{-- Other Options --}}
<div class="ez-filter-group" aria-expanded="true">
    <button type="button" class="ez-filter-group__head js-ez-toggle">
        <span class="ez-filter-group__title">{{ trans('update.other_options') }}</span>
        <span class="ez-filter-group__chev">
            <x-iconsax-lin-add class="icons" width="11" height="11"/>
        </span>
    </button>
    <div class="ez-filter-group__body">
        @foreach(['instructor_with_courses'] as $otherOption)
            <label class="d-flex align-items-center cursor-pointer" for="filter_meeting_options_{{ $otherOption }}" style="gap: 10px;">
                <input type="checkbox" name="meeting_options[]" value="{{ $otherOption }}" id="filter_meeting_options_{{ $otherOption }}"
                       style="accent-color: var(--ink); width: 16px; height: 16px;">
                <span style="font-size: 14px; color: var(--ink);">{{ trans('update.'.$otherOption) }}</span>
            </label>
        @endforeach
    </div>
</div>

{{-- CTA: become a tutor --}}
<div class="ez-sidebar__cta">
    <div class="ez-sidebar__cta-title">{{ trans('site.become_instructor') ?? 'Want to teach?' }}</div>
    <div class="ez-sidebar__cta-body">We pay tutors 60% of every fee — the highest split on the market. Apply in 14 days.</div>
    <a href="/become-instructor" class="ez-sidebar__cta-link">
        {{ trans('site.become_instructor') ?? 'Apply to teach' }} →
    </a>
</div>

@push('scripts_bottom')
<script>
(function(){
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
