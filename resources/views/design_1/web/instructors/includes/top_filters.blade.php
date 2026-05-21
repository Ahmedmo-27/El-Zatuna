<div class="ez-toolbar">
    <div class="ez-toolbar__search">
        <x-iconsax-lin-search-normal-1 class="icons" width="16px" height="16px" style="color: var(--citron);"/>
        <input type="text" name="search" value="{{ request()->get('search') }}" placeholder="{{ trans('public.search') }} — name, university, course…">
    </div>

    <div class="ez-toolbar__divider" style="display: flex; align-items: center;">
        <span class="ez-toolbar__label">{{ trans('public.sort_by') }}</span>
        <select name="sort" class="ez-toolbar__select" onchange="this.form.submit()">
            <option value="">{{ trans('public.all') }}</option>
            <option value="top_rate" @if(request()->get('sort') == 'top_rate') selected @endif>{{ trans('site.top_rate') }}</option>
            <option value="top_sale" @if(request()->get('sort') == 'top_sale') selected @endif>{{ trans('site.top_sellers') }}</option>
        </select>
    </div>
</div>
