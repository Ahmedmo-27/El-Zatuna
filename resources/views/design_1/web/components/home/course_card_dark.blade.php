@php
    $href = $href ?? '#';
    $image = $image ?? '';
    $title = $title ?? '';
    $subtitle = $subtitle ?? 'Instructor';
    $height = $height ?? 'h-[200px]';
    $bodyPadding = $bodyPadding ?? 'p-6';
@endphp

<a href="{{ $href }}" class="block rounded-[24px] bg-[#072923] overflow-hidden">
    <div class="{{ $height }} w-full">
        <img loading="lazy" src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover">
    </div>
    <div class="{{ $bodyPadding }} text-[#FAFFE0]">
        <div class="font-semibold text-base leading-relaxed">{{ $title }}</div>
        <div class="mt-1 text-sm text-[#FAFFE0]/70">{{ $subtitle }}</div>
    </div>
</a>
