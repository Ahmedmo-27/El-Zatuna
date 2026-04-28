@php
    $href = $href ?? '#';
    $image = $image ?? '';
    $title = $title ?? '';
    $subtitle = $subtitle ?? 'Instructor';
    $originalPrice = $originalPrice ?? null;
    $discountedPrice = $discountedPrice ?? null;
    $discountPercent = $discountPercent ?? null;
@endphp

<a href="{{ $href }}" class="block rounded-[24px] bg-[#FAFFE0] overflow-hidden">
    <div class="h-[220px] w-full relative">
        <img loading="lazy" src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">

        @if(!empty($discountPercent) && $discountPercent > 0)
            <span class="absolute top-4 left-4 bg-[#C8CD06] text-[#072923] font-semibold text-xs px-3 py-1 rounded-full">{{ round($discountPercent) }}% OFF</span>
        @endif
    </div>
    <div class="p-7 text-[#072923]">
        <div class="font-semibold text-base">{{ $title }}</div>
        <div class="mt-3 text-xs text-[#072923]/70">{{ $subtitle }}</div>

        @if(!empty($course) && (!empty($course->university) || !empty($course->faculty)))
            <div class="mt-2 text-xs text-[#072923]/60">
                @if(!empty($course->university))
                    <span class="font-medium">{{ $course->university->name }}</span>
                @endif

                @if(!empty($course->faculty))
                    <span class="ml-2">- {{ $course->faculty->name }}</span>
                @endif
            </div>
        @endif

        @if(!empty($originalPrice) && !empty($discountedPrice) && $discountedPrice < $originalPrice)
            <div class="mt-4 flex items-center gap-3">
                <span class="text-lg font-semibold">{{ handlePrice($discountedPrice, true, true, false, null, true) }}</span>
                <span class="text-sm text-[#072923]/60 line-through">{{ handlePrice($originalPrice, true, true, false, null, true) }}</span>
            </div>
        @endif
    </div>
</a>
