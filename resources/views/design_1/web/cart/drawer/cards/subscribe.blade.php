@php
    $cartTaxType = !empty($cartItemInfo['isProduct']) ? 'store' : 'general';
@endphp

<div class="d-flex align-items-center justify-content-between {{ $className ?? '' }}">
    <div class="d-flex align-items-center">
        <div class="mr-12">
            @php
                $img = $cartItemInfo['imgPath'] ?? null;
                $isImage = is_string($img) && (str_starts_with($img, 'http') || str_starts_with($img, '/') || str_contains($img, '.png') || str_contains($img, '.jpg') || str_contains($img, '.jpeg') || str_contains($img, '.svg'));
            @endphp

            @if($isImage)
                <img src="{{ $img }}" class="img-cover rounded-8" style="width: 48px; height: 48px;" alt="{{ $cartItemInfo['title'] }}">
            @else
                <div class="d-flex align-items-center justify-content-center rounded-8 bg-gray-100" style="width: 48px; height: 48px;">
                    <x-iconsax-lin-crown class="w-4 h-4 text-gray-500"/>
                </div>
            @endif
        </div>

        <div>
            <h6 class="font-12 text-dark">{{ $cartItemInfo['title'] }}</h6>
            @if(!empty($cartItemInfo['extraHint']))
                <div class="font-12 text-gray-500 mt-4">{{ $cartItemInfo['extraHint'] }}</div>
            @endif
        </div>
    </div>

    <div class="d-flex align-items-center">
        <span class="font-14 font-weight-bold text-primary">{{ handlePrice($cartItemInfo['price'], true, true, false, null, true, $cartTaxType) }}</span>
    </div>
</div>

