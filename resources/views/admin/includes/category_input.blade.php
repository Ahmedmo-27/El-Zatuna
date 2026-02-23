{{--
    Admin category selector: search existing categories + "Add new category" if the typed one doesn't exist.
    Same full functionality as occupations (search + add if doesn't exist). Used in Categories & Filters instead of the select.
    Expects: $categoryId (optional), $categoryTitle (optional), or $webinar with category; $categories list for initial display.
--}}
@php
    $webinar = $webinar ?? null;
    $categoryId = $categoryId ?? ($webinar ? $webinar->category_id : null);
    $categoryTitle = $categoryTitle ?? null;
    if (empty($categoryTitle) && $webinar && $webinar->category) {
        $categoryTitle = $webinar->category->title;
    }
    $initial = [];
    if (!empty($categoryId) && !empty($categoryTitle)) {
        $initial = [['id' => $categoryId, 'text' => $categoryTitle]];
    }
@endphp
<div class="js-admin-category-input form-group" data-initial="{{ e(json_encode($initial)) }}" data-search-url="{{ getAdminPanelUrl() }}/categories/search" data-quick-store-url="{{ getAdminPanelUrl() }}/categories/quick-store">
    <label class="input-label">{{ trans('public.category') }}</label>
    <p class="text-muted small mb-2">{{ trans('public.choose_category') }}. Type to search or add a new category if it doesn't exist.</p>

    <div class="position-relative">
        <input type="text" class="form-control js-admin-category-search" placeholder="Type category name..." autocomplete="off">

        <div class="js-admin-category-dropdown position-absolute d-none bg-white border rounded shadow-sm mt-1 w-100" style="max-height: 220px; overflow-y: auto; z-index: 1050;">
            <div class="js-admin-category-results p-2"></div>
            <div class="js-admin-category-add-new border-top p-2 text-muted cursor-pointer hover:bg-light" style="font-size: 13px;">
                <span class="js-admin-category-add-new-text">Add new category</span>: <span class="js-admin-category-add-new-term font-weight-bold text-primary"></span>
            </div>
        </div>
    </div>

    <div class="js-admin-category-tag mt-2" style="min-height: 28px;"></div>

    <input type="hidden" id="categories" name="category_id" value="{{ $categoryId ?? '' }}" class="js-admin-category-hidden" {{ !empty($required) ? 'required' : '' }}>

    @error('category_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
