<div class="ez-toolbar">
    {{-- Search --}}
    <div class="ez-toolbar__search">
        <x-iconsax-lin-search-normal-1 class="icons" width="16px" height="16px" style="color: var(--citron);"/>
        <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search') }} — course code, title, professor…">
    </div>

    {{-- Quick toggles --}}
    @foreach(['upcoming', 'free', 'discount'] as $topFilter1)
        <label class="ez-toolbar__divider ez-toolbar__toggle" for="top_filter_{{ $topFilter1 }}">
            <input id="top_filter_{{ $topFilter1 }}" type="checkbox" name="{{ $topFilter1 }}" value="on" {{ request()->get($topFilter1) == 'on' ? 'checked' : '' }}>
            <span class="ez-toolbar__toggle-switch"></span>
            <span class="ez-toolbar__toggle-label">{{ trans("update.{$topFilter1}") }}</span>
        </label>
    @endforeach

    {{-- Sort --}}
    <div class="ez-toolbar__divider" style="display: flex; align-items: center;">
        <span class="ez-toolbar__label">{{ trans('public.sort_by') }}</span>
        <select name="sort" class="ez-toolbar__select" onchange="this.form.submit()">
            <option value="">{{ trans('public.all') }}</option>
            @foreach(['newest', 'expensive', 'inexpensive', 'bestsellers', 'best_rates'] as $filterSort)
                <option value="{{ $filterSort }}" {{ (request()->get('sort') == $filterSort) ? 'selected' : '' }}>{{ trans("public.{$filterSort}") }}</option>
            @endforeach
        </select>
    </div>

    <input type="hidden" name="card" value="grid">
</div>
