@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        <section class="max-w-[1600px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-3 bg-white/70 border border-[#ECF4B8] px-5 py-2 rounded-full shadow-sm">
                    <x-iconsax-lin-shield-tick class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Cookies</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mt-6">Cookie Policy</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">How and why we use cookies</p>
                <div class="mt-10 flex justify-center">
                    <img src="/assets/design_1/img/no-result/notifications.svg" alt="Cookie Policy" class="w-72 md:w-96 lg:w-[520px] opacity-90" />
                </div>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pb-24">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-monitor-mobbile class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Essential Cookies</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">Required for login, security, and core platform functions. These cookies cannot be switched off.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-chart-2 class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Analytics Cookies</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">Help us understand feature usage and improve course discovery, speed, and reliability.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-setting-4 class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Preference Cookies</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">Remember selected language, interface settings, and personalization preferences.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-security-safe class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Managing Cookies</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">You can manage optional cookies from the cookie settings dialog or through your browser settings at any time.</p>
                </div>
            </div>
        </section>
    </main>
@endsection
