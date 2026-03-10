@php
    $href = $href ?? '#';
    $image = $image ?? '';
    $title = $title ?? '';
    $subtitle = $subtitle ?? 'Instructor';
@endphp

<a href="{{ $href }}" class="block rounded-[24px] bg-[#FAFFE0] overflow-hidden">
    <div class="h-[220px] w-full">
        <img loading="lazy" src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
    </div>
    <div class="p-7 text-[#072923]">
        <div class="font-semibold text-base">{{ $title }}</div>
        <div class="mt-3 text-xs text-[#072923]/70">{{ $subtitle }}</div>
    </div>
</a>
