@if($courseRow->price > 0)
    @if($courseRow->bestTicket() < $courseRow->price)
        <span class="" style="color: #000000 !important;">{{ handlePrice($courseRow->bestTicket(), true, true, false, null, true) }}</span>
        <span class="font-14 font-weight-400 text-[#000000] text-decoration-line-through {{ !empty($discountedPriceClass) ? $discountedPriceClass : 'ml-8' }}" style="color: #000000 !important;">{{ handlePrice($courseRow->price, true, true, false, null, true) }}</span>
    @else
        <span class="" style="color: #000000 !important;">{{ handlePrice($courseRow->price, true, true, false, null, true) }}</span>
    @endif
@else
    <span class="" style="color: #000000 !important;">{{ trans('public.free') }}</span>
@endif
