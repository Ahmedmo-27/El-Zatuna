@php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
@endphp

<div class="webinar-card webinar-list-card d-flex align-items-center justify-content-between mt-16">
    <div class="d-flex align-items-center">
        <div class="webinar-image mr-16">
            @php
                $img = $cartItemInfo['imgPath'] ?? null;
                $isImage = is_string($img) && (str_starts_with($img, 'http') || str_starts_with($img, '/') || str_contains($img, '.png') || str_contains($img, '.jpg') || str_contains($img, '.jpeg') || str_contains($img, '.svg'));
            @endphp

            @if($isImage)
                <img src="{{ $img }}" class="img-cover rounded-8" alt="{{ $cartItemInfo['title'] }}">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-8 bg-gray-100" style="width: 64px; height: 64px;">
                    <x-iconsax-lin-crown class="w-5 h-5 text-gray-500"/>
                </div>
            @endif
        </div>

        <div class="webinar-details">
            <h6 class="font-12 text-dark">{{ $cartItemInfo['title'] }}</h6>
            @if(!empty($cartItemInfo['extraHint']))
                <div class="font-12 text-gray-500 mt-4">{{ $cartItemInfo['extraHint'] }}</div>
            @endif
        </div>
    </div>

    <div class="d-flex align-items-center">
        <span class="font-16 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
    </div>
</div>

