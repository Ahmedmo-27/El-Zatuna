{{-- Panel course category input: same UX as occupations (search + "Add different subject"). Single selection, name="category_id", id="categories". --}}
@php
    $webinar = $webinar ?? null;
    $categories = $categories ?? [];
    $categoryId = old('category_id', $webinar ? $webinar->category_id : null);
    $categoryTitle = $categoryTitle ?? null;
    if (empty($categoryTitle) && $webinar && $webinar->category && (string)$webinar->category_id === (string)$categoryId) {
        $categoryTitle = $webinar->category->title;
    }
    if (empty($categoryTitle) && !empty($categoryId) && !empty($categories)) {
        foreach ($categories as $cat) {
            if ((string)$cat->id === (string)$categoryId) { $categoryTitle = $cat->title; break; }
            if (!empty($cat->subCategories)) { foreach ($cat->subCategories as $sub) { if ((string)$sub->id === (string)$categoryId) { $categoryTitle = $sub->title; break 2; } } }
        }
    }
    $categoryInitial = [];
    if (!empty($categoryId) && !empty($categoryTitle)) {
        $categoryInitial = [['id' => $categoryId, 'text' => $categoryTitle]];
    }
    $categoryDescription = $categoryDescription ?? trans('public.choose_category');
@endphp
<div class="form-group js-panel-category-wrapper" data-initial="{{ e(json_encode($categoryInitial)) }}">
    @if(!empty($categoryDescription))
        <p class="text-sm text-[#072923]/60 mb-2">{{ $categoryDescription }}</p>
    @endif

    <div class="position-relative">
        <input type="text" class="form-control border-[#ECF4B8] focus:border-[#C8CD06] focus:ring-[#C8CD06] js-panel-category-input" placeholder="Type a category name..." autocomplete="off">

        <div class="js-panel-category-dropdown position-absolute bg-white border border-[#ECF4B8] rounded-12 shadow-sm mt-1 d-none" style="top: 100%; left: 0; right: 0; max-height: 220px; overflow-y: auto; z-index: 1050; -webkit-tap-highlight-color: transparent;" tabindex="-1">
            <div class="js-panel-category-results p-2"></div>
            <div class="js-panel-category-add-new border-top border-[#ECF4B8] p-2 text-[#072923]/70 cursor-pointer hover:bg-[#F5F9E8]/50" style="font-size: 13px; min-height: 44px; display: flex; align-items: center;">
                <span class="js-panel-category-add-new-text">Add different subject</span> – <span class="js-panel-category-add-new-term font-weight-medium text-[#C8CD06]"></span>
            </div>
        </div>
    </div>

    <div class="js-panel-category-tag mt-8 d-flex flex-wrap gap-2" style="min-height: 24px;"></div>

    <input type="hidden" id="categories" name="category_id" value="{{ $categoryId ?? '' }}" class="js-panel-category-hidden">

    <div class="js-panel-category-loading mt-2 small text-muted d-none">
        <span class="spinner-border spinner-border-sm align-middle mr-1" role="status" aria-hidden="true"></span>
        <span class="align-middle">{{ trans('public.loading') }}</span>
    </div>

    <div class="js-panel-category-error mt-2 alert alert-danger py-2 px-3 d-none" style="font-size: 13px;"></div>

    @error('category_id')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
    @enderror
</div>
