@if(!empty($contactSettings['latitude']) and !empty($contactSettings['longitude']))
    <div class="region-map contactus-map with-default-initial rounded-8 bg-gray-100" id="contactMap"
         data-latitude="{{ $contactSettings['latitude'] }}"
         data-longitude="{{ $contactSettings['longitude'] }}"
         data-zoom="{{ $contactSettings['map_zoom'] ?? 12 }}"
         data-dragging="false"
         data-zoomControl="true"
         data-scrollWheelZoom="false"
    >
        <img src="/assets/design_1/img/map/pin_large.svg" class="marker" width="40" height="40">
    </div>
@else
    <div class="contact-page-map-placeholder d-flex-center text-center p-20">
        <div>
            <h5 class="font-16 font-weight-bold text-[#FAFFE0] mb-8">{{ trans('update.address') }}</h5>
            <p class="font-14 text-[#FAFFE0]/80 mb-0">
                {{ trans('site.not_defined') }}
            </p>
        </div>
    </div>
@endif
