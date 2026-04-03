@php
    $webinar = $webinar ?? null;
    $categories = $categories ?? [];
    $categoryId = old('category_id', $webinar ? $webinar->category_id : null);
@endphp

<select name="category_id" id="categories" class="select2 @error('category_id') is-invalid @enderror" required>
    <option {{ !empty($categoryId) ? '' : 'selected' }} disabled>{{ trans('public.choose_category') }}</option>

    @foreach($categories as $category)
        @if(!empty($category->subCategories) and $category->subCategories->count() > 0)
            <optgroup label="{{ $category->title }}">
                @foreach($category->subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" {{ ((string)$categoryId === (string)$subCategory->id) ? 'selected' : '' }}>{{ $subCategory->title }}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $category->id }}" {{ ((string)$categoryId === (string)$category->id) ? 'selected' : '' }}>{{ $category->title }}</option>
        @endif
    @endforeach
</select>

<p class="mt-8 font-12 text-gray-500">{{ trans('public.choose_category') }}</p>

@error('category_id')
    <div class="invalid-feedback d-block">
        {{ $message }}
    </div>
@enderror
