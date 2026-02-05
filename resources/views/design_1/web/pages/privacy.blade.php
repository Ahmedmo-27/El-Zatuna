@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        <section class="max-w-[1600px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-3 bg-white/70 border border-[#ECF4B8] px-5 py-2 rounded-full shadow-sm">
                    <x-iconsax-lin-lock class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Privacy</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mt-6">Privacy Policy</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">How we protect your data</p>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pb-24">
            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-user-square class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Information Collection</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">We collect your name, email, university name, and payment information to provide our services.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-activity class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Usage Tracking</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">We track which videos you watch and which points you spend to provide personalized course recommendations.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-shield-tick class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Data Security</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">We use industry-standard encryption to protect your data. We do not sell your personal information to third-party advertisers.</p>
                </div>

                <div class="bg-white rounded-[28px] p-7 md:p-9 shadow-sm border border-[#ECF4B8]">
                    <div class="flex items-center gap-3 mb-3">
                        <x-iconsax-lin-profile-tick class="w-6 h-6 text-[#C8CD06]"/>
                        <h3 class="text-xl font-semibold">Student Rights</h3>
                    </div>
                    <p class="text-lg text-[#072923]/80">You may request to view, edit, or delete your personal data at any time by contacting our support team.</p>
                </div>
            </div>
        </section>
    </main>
@endsection
