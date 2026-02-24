@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-24">
            <div class="bg-white rounded-[32px] border border-[#ECF4B8] shadow-sm p-8 md:p-12 lg:p-16 text-center">
                <div class="inline-flex items-center gap-3 bg-[#FAFFE0] border border-[#ECF4B8] px-5 py-2 rounded-full">
                    <x-iconsax-lin-warning-2 class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Error 404</span>
                </div>

                <h1 class="text-5xl md:text-6xl font-bold mt-6">Page Not Found</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">The page you’re looking for doesn’t exist or was moved.</p>

                <div class="mt-10 flex justify-center">
                    <img src="/assets/design_1/img/no-result/notifications.svg" alt="404 Not Found" class="w-72 md:w-96 lg:w-[520px] opacity-90" />
                </div>

                <div class="mt-10 flex items-center justify-center gap-4 flex-wrap">
                    <a href="/" class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-[#C8CD06] text-[#072923] font-semibold hover:opacity-90 transition">
                        <x-iconsax-lin-home class="w-5 h-5"/>
                        Go Home
                    </a>

                    <a href="/contact" class="inline-flex items-center gap-2 px-7 py-3 rounded-full border border-[#072923]/20 text-[#072923] font-semibold hover:bg-[#FAFFE0] transition">
                        <x-iconsax-lin-message-question class="w-5 h-5"/>
                        Contact Support
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
