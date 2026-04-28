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

        @if(!empty($course) && (!empty($course->university) || !empty($course->faculty)))
            <div class="mt-2 text-xs text-[#FAFFE0]/60">
                @if(!empty($course->university))
                    <span class="font-medium">{{ $course->university->name }}</span>
                @endif

                @if(!empty($course->faculty))
                    <span class="ml-2">- {{ $course->faculty->name }}</span>
                @endif
            </div>
        @endif
    </div>
</a>
