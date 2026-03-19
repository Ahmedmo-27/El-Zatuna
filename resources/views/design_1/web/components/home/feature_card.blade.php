@php
    $icon = $icon ?? 'iconsax-lin-star';
    $title = $title ?? '';
    $description = $description ?? '';
    $spanClass = $spanClass ?? '';
@endphp

<div class="{{ $spanClass }} bg-[#072923] text-[#FAFFE0] rounded-[24px] p-9 h-[220px] flex flex-col gap-5">
    <div class="h-10 w-10 rounded-full bg-[#FAFFE0] text-[#072923] flex items-center justify-center self-end">
        <x-dynamic-component :component="$icon" class="w-6 h-6"/>
    </div>
    <div>
        <div class="font-semibold text-base leading-relaxed whitespace-pre-line">{{ $title }}</div>
        <p class="mt-4 text-sm text-[#FAFFE0]/75 leading-relaxed">{{ $description }}</p>
    </div>
</div>
