@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

<div class="bg-white rounded-16 p-16 mt-32">

    {{-- Pricing Options (managed by admin) --}}
    <h3 class="font-14 font-weight-bold mb-8">{{ trans('update.pricing_options') }}</h3>
    <p class="font-12 text-gray-500 mb-24">
        {{ __('The price of this course is set by the admin. You can continue creating your content and send the course for review.') }}
    </p>

    <div class="form-group">
        <label class="form-group-label">{{ trans('update.access_days') }} ({{ trans('public.optional') }})</label>
        <span class="has-translation bg-gray-100 text-gray-500 w-auto px-8">{{ trans('public.days') }}</span>
        <input type="number" name="access_days" class="form-control @error('access_days') is-invalid @enderror" value="{{ !empty($webinar) ? $webinar->access_days : old('access_days') }}"/>

        @error('access_days')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror

        <p class="font-12 text-gray-500 mt-8">- {{ trans('update.access_days_input_hint') }}</p>
    </div>

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="subscribeSwitch" type="checkbox" name="subscribe" class="custom-control-input" {{ (!empty($webinar) and $webinar->subscribe) ? 'checked' :  '' }}>
                <label class="custom-control-label cursor-pointer" for="subscribeSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="subscribeSwitch">{{ trans('update.include_subscribe') }}</label>
            </div>
        </div>

        <p class="font-12 text-gray-500 mt-8">- {{ trans('forms.subscribe_hint') }}</p>
    </div>

</div>

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
@endpush
