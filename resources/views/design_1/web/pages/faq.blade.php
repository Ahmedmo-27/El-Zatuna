@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen" style="word-spacing: 0.16em;">
        <section class="max-w-[1600px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-3 bg-white/70 border border-[#ECF4B8] px-5 py-2 rounded-full shadow-sm">
                    <x-iconsax-lin-message-question class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">FAQ</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mt-6">Frequently Asked Questions</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">Points, access, and requests</p>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pb-24">
            <div class="space-y-4">
                <details class="group bg-white rounded-[28px] p-6 md:p-8 shadow-sm border border-[#ECF4B8]">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <div class="flex items-center gap-3">
                            <x-iconsax-lin-coin class="w-6 h-6 text-[#C8CD06]"/>
                            <span class="text-xl font-semibold">How do the "Points" work?</span>
                        </div>
                        <x-iconsax-lin-arrow-down-1 class="w-5 h-5 text-[#072923] transition-transform duration-200 group-open:rotate-180"/>
                    </summary>
                    <p class="mt-4 text-lg text-[#072923]/80">Instead of buying a single course, you use points. Your subscription gives you a balance of points that you can spend to unlock specific videos or modules from any course on the platform. This allows you to learn exactly what you need without paying for a whole course you might not finish.</p>
                </details>

                <details class="group bg-white rounded-[28px] p-6 md:p-8 shadow-sm border border-[#ECF4B8]">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <div class="flex items-center gap-3">
                            <x-iconsax-lin-category-2 class="w-6 h-6 text-[#C8CD06]"/>
                            <span class="text-xl font-semibold">Can I use my points for different subjects?</span>
                        </div>
                        <x-iconsax-lin-arrow-down-1 class="w-5 h-5 text-[#072923] transition-transform duration-200 group-open:rotate-180"/>
                    </summary>
                    <p class="mt-4 text-lg text-[#072923]/80">Absolutely. If you are a Business student but want to watch a single video from the Engineering department, you can use your points to unlock just that video.</p>
                </details>

                <details class="group bg-white rounded-[28px] p-6 md:p-8 shadow-sm border border-[#ECF4B8]">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <div class="flex items-center gap-3">
                            <x-iconsax-lin-document-upload class="w-6 h-6 text-[#C8CD06]"/>
                            <span class="text-xl font-semibold">How do I request a course not listed on the site?</span>
                        </div>
                        <x-iconsax-lin-arrow-down-1 class="w-5 h-5 text-[#072923] transition-transform duration-200 group-open:rotate-180"/>
                    </summary>
                    <p class="mt-4 text-lg text-[#072923]/80">Go to your dashboard and click “Request a Course.” Upload your syllabus or topic list. Our team of instructors will review it and aim to have the content live on the platform within one week.</p>
                </details>

                <details class="group bg-white rounded-[28px] p-6 md:p-8 shadow-sm border border-[#ECF4B8]">
                    <summary class="flex items-center justify-between cursor-pointer list-none">
                        <div class="flex items-center gap-3">
                            <x-iconsax-lin-video-play class="w-6 h-6 text-[#C8CD06]"/>
                            <span class="text-xl font-semibold">Can I watch videos offline?</span>
                        </div>
                        <x-iconsax-lin-arrow-down-1 class="w-5 h-5 text-[#072923] transition-transform duration-200 group-open:rotate-180"/>
                    </summary>
                    <p class="mt-4 text-lg text-[#072923]/80">Currently, videos are available for streaming only to protect our creators’ intellectual property.</p>
                </details>
            </div>
        </section>
    </main>
@endsection
