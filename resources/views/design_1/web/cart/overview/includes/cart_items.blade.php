@php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
@endphp

@php
    $courseCartItems = $carts->filter(function ($c) {
        return $c->webinar_id && !$c->file_id && !$c->chapter_id;
    });
    $sectionCartItems = $carts->whereNotNull('chapter_id');
    $hasCourseItems = $courseCartItems->count() > 0 || $sectionCartItems->count() > 0;
@endphp
@if($hasCourseItems)
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16">{{ trans('update.courses') }}</h5>

        @foreach($courseCartItems as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
        @foreach($sectionCartItems as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('file_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('public.files') }}</h5>

        @foreach($carts->whereNotNull('file_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('bundle_id')->count())
    <div class="card-before-line px-16">
        <h5 class="font-14 mb-16">{{ trans('update.bundles') }}</h5>

        @foreach($carts->whereNotNull('bundle_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.course', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif

@if($carts->whereNotNull('reserve_meeting_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('panel.meetings') }}</h5>

        @foreach($carts->whereNotNull('reserve_meeting_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.meeting', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif


@if($carts->whereNotNull('product_order_id')->count())
    <div class="card-before-line px-16 mt-16">
        <h5 class="font-14 mb-16">{{ trans('update.products') }}</h5>

        @foreach($carts->whereNotNull('product_order_id') as $cartItem)
            @include('design_1.web.cart.overview.includes.item_cards.product', [
                'cartItemInfo' => $cartItem->getItemInfo(),
            ])
        @endforeach
    </div>
@endif
