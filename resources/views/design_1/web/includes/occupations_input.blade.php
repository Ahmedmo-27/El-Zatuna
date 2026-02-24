{{-- Shared searchable occupations/subjects input: search + "Add different subject", same UX on become-instructor and registration step 3. Expects $occupationsInitial = [['id' => x, 'text' => y], ...] --}}
@php
    $occupationsInitial = $occupationsInitial ?? [];
@endphp
<div class="form-group js-occupations-wrapper" data-initial="{{ e(json_encode($occupationsInitial)) }}">
    <p class="text-sm text-[#072923]/60 mb-2">Select the subjects or topics you want to teach. Type to search existing ones.</p>

    <div class="position-relative">
        <input type="text" id="occupationsInput" class="form-control border-[#ECF4B8] focus:border-[#C8CD06] focus:ring-[#C8CD06] js-occupations-input" placeholder="Type a subject name..." autocomplete="off">

        <div class="js-occupations-dropdown position-absolute bg-white border border-[#ECF4B8] rounded-12 shadow-sm mt-1 d-none" style="top: 100%; left: 0; right: 0; max-height: 220px; overflow-y: auto; z-index: 1050;">
            <div class="js-occupations-results p-2"></div>
            <div class="js-occupations-add-new border-top border-[#ECF4B8] p-2 text-[#072923]/70 cursor-pointer hover:bg-[#F5F9E8]/50" style="font-size: 13px;">
                <span class="js-add-new-text">Add different subject</span> – <span class="js-add-new-term font-weight-medium text-[#C8CD06]"></span>
            </div>
        </div>
    </div>

    <div class="js-occupations-tags mt-8 d-flex flex-wrap gap-2" style="min-height: 24px;"></div>

    <div class="js-occupations-hidden-container"></div>

    <div class="js-occupations-loading mt-2 small text-muted d-none">
        <span class="spinner-border spinner-border-sm align-middle mr-1" role="status" aria-hidden="true"></span>
        <span class="align-middle">{{ trans('public.loading') }}</span>
    </div>

    <div class="js-occupations-error mt-2 alert alert-danger py-2 px-3 d-none" style="font-size: 13px;"></div>

    @error('occupations')
    <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
    @enderror
    @error('occupations.*')
    <div class="invalid-feedback d-block text-red-600">{{ $message }}</div>
    @enderror
</div>
