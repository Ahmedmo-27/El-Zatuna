@if($course->price > 0)
    @if($course->bestTicket() < $course->price)
        <span class="">{{ handlePrice($course->bestTicket(), true, true, false, null, true) }}</span>
        <span class="font-14 font-weight-400 text-gray-500 ml-8 text-decoration-line-through">{{ handlePrice($course->price, true, true, false, null, true) }}</span>
    @else
        <span class="">{{ handlePrice($course->price, true, true, false, null, true) }}</span>
    @endif
@else
    <span class="">{{ trans('public.free') }}</span>
@endif
