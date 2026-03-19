@php
    $icon = $icon ?? 'iconsax-lin-star';
    $value = $value ?? '';
    $label = $label ?? '';
@endphp

<div class="flex items-center justify-center gap-4">
    <div class="h-12 w-12 rounded-full bg-[#FAFFE0] flex items-center justify-center text-xl">
        <x-dynamic-component :component="$icon" class="w-6 h-6 text-[#072923]"/>
    </div>
    <div>
        <div class="text-3xl font-semibold">{{ $value }}</div>
        <div class="text-xs text-[#072923]/70">{{ $label }}</div>
    </div>
</div>
