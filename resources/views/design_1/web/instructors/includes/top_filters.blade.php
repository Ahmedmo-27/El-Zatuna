<div class="instructors-lists-top-filters position-relative mt-28">
    <div class="instructors-lists-top-filters__mask"></div>

    <div class="position-relative z-index-2 d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between bg-[#072923] text-[#FAFFE0] p-12 rounded-24">
        <div></div>

        <div class="form-group mb-0 mt-16 mt-lg-0" style="width: 200px">
            <select name="sort" class="form-control select2" data-minimum-results-for-search="Infinity">
                <option disabled selected>{{ trans('public.sort_by') }}</option>
                <option value="">{{ trans('public.all') }}</option>
                <option value="top_rate" @if(request()->get('sort') == 'top_rate') selected="selected" @endif>{{ trans('site.top_rate') }}</option>
                <option value="top_sale" @if(request()->get('sort') == 'top_sale') selected="selected" @endif>{{ trans('site.top_sellers') }}</option>
            </select>
        </div>
    </div>
</div>
