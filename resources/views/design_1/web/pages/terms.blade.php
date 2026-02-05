@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        <section class="max-w-[1600px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-16">
            <div class="text-center">
                <div class="inline-flex items-center gap-3 bg-white/70 border border-[#ECF4B8] px-5 py-2 rounded-full shadow-sm">
                    <x-iconsax-lin-document-text class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Elzatuna</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mt-6">Terms &amp; Conditions</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">Welcome to elzatuna</p>
            </div>
        </section>

        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pb-24">
            <div class="bg-white rounded-[28px] p-8 md:p-12 shadow-sm border border-[#ECF4B8]">
                <p class="text-xl md:text-2xl text-[#072923]/80 mb-8">By using our platform, you agree to the following terms:</p>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-[#FAFFE0] rounded-2xl p-6 border border-[#ECF4B8]">
                        <div class="flex items-center gap-3 mb-3">
                            <x-iconsax-lin-shield-tick class="w-6 h-6 text-[#C8CD06]"/>
                            <h3 class="text-xl font-semibold">Eligibility</h3>
                        </div>
                        <p class="text-lg text-[#072923]/80">You must be a university student to subscribe.</p>
                    </div>

                    <div class="bg-[#FAFFE0] rounded-2xl p-6 border border-[#ECF4B8]">
                        <div class="flex items-center gap-3 mb-3">
                            <x-iconsax-lin-lock class="w-6 h-6 text-[#C8CD06]"/>
                            <h3 class="text-xl font-semibold">Account Security</h3>
                        </div>
                        <p class="text-lg text-[#072923]/80">Your account and subscription are for personal use only. Sharing credentials or recording/distributing content is prohibited and will result in immediate termination without refund.</p>
                    </div>

                    <div class="bg-[#FAFFE0] rounded-2xl p-6 border border-[#ECF4B8]">
                        <div class="flex items-center gap-3 mb-3">
                            <x-iconsax-lin-coin class="w-6 h-6 text-[#C8CD06]"/>
                            <h3 class="text-xl font-semibold">The Point System</h3>
                        </div>
                        <p class="text-lg text-[#072923]/80">Points are a virtual currency to access content. They are valid while your subscription is active, non-transferable, and not redeemable for cash.</p>
                    </div>

                    <div class="bg-[#FAFFE0] rounded-2xl p-6 border border-[#ECF4B8]">
                        <div class="flex items-center gap-3 mb-3">
                            <x-iconsax-lin-calendar-2 class="w-6 h-6 text-[#C8CD06]"/>
                            <h3 class="text-xl font-semibold">Course Requests</h3>
                        </div>
                        <p class="text-lg text-[#072923]/80">We aim to fulfill course requests within 7 business days, subject to expert availability. If a request can’t be met in time, you’ll be notified via email.</p>
                    </div>

                    <div class="bg-[#FAFFE0] rounded-2xl p-6 border border-[#ECF4B8] md:col-span-2">
                        <div class="flex items-center gap-3 mb-3">
                            <x-iconsax-lin-warning-2 class="w-6 h-6 text-[#C8CD06]"/>
                            <h3 class="text-xl font-semibold">Limitation of Liability</h3>
                        </div>
                        <p class="text-lg text-[#072923]/80">While we strive for academic excellence, elzatuna is a supplemental resource.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
