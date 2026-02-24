@extends('design_1.web.layouts.app')

@php
    $statusCode = $statusCode ?? 404;

    $defaultTitle = ($statusCode == 404)
        ? 'Page Not Found'
        : 'Something Went Wrong';

    $defaultDescription = ($statusCode == 404)
        ? 'The page you are looking for does not exist or has been moved.'
        : 'An unexpected error occurred. Please try again or return to the homepage.';

    $displayTitle = !empty($errorSettings['title']) ? $errorSettings['title'] : $defaultTitle;
    $displayDescription = !empty($errorSettings['description']) ? $errorSettings['description'] : $defaultDescription;
    $displayImage = !empty($errorSettings['image']) ? $errorSettings['image'] : '/assets/design_1/img/no-result/notifications.svg';
    $displayButtonTitle = !empty($errorSettings['button']['title']) ? $errorSettings['button']['title'] : 'Go Home';
    $displayButtonLink = !empty($errorSettings['button']['link']) ? $errorSettings['button']['link'] : '/';
@endphp

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-24">
            <div class="bg-white rounded-[32px] border border-[#ECF4B8] shadow-sm p-8 md:p-12 lg:p-16 text-center">
                <div class="inline-flex items-center gap-3 bg-[#FAFFE0] border border-[#ECF4B8] px-5 py-2 rounded-full">
                    <x-iconsax-lin-warning-2 class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Error {{ $statusCode }}</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-bold mt-6">{{ $displayTitle }}</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">{!! nl2br($displayDescription) !!}</p>

                <div class="mt-10 flex justify-center">
                    <img src="{{ $displayImage }}" alt="Error {{ $statusCode }}" class="w-72 md:w-96 lg:w-[520px] opacity-90" />
                </div>

                <div class="mt-10 flex items-center justify-center gap-4 flex-wrap">
                    <a href="{{ $displayButtonLink }}" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#C8CD06] text-[#072923] font-semibold hover:opacity-90 transition">
                        <x-iconsax-lin-home class="w-5 h-5"/>
                        {{ $displayButtonTitle }}
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
