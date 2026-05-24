@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">

        {{-- HERO --}}
        <section class="max-w-[1200px] mx-auto px-8 md:px-16 lg:px-24 pt-28 pb-8">
            <div class="text-center">
                <div class="inline-flex items-center gap-3 bg-white/70 border border-[#ECF4B8] px-5 py-2 rounded-full shadow-sm">
                    <x-iconsax-lin-lock class="w-5 h-5 text-[#C8CD06]"/>
                    <span class="text-sm font-semibold uppercase tracking-wide">Privacy</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mt-6">Privacy Policy</h1>
                <p class="mt-4 text-xl md:text-2xl text-[#072923]/70">How we protect your data</p>
            </div>
        </section>

        {{-- META STRIP (added context, not policy) --}}
        <section class="max-w-[1040px] mx-auto px-8 md:px-16 lg:px-24 mt-10">
            <div class="grid grid-cols-1 md:grid-cols-3 border-y border-[#072923]/15 py-5">
                <div class="flex flex-col items-center gap-1 px-4 md:border-r border-[#072923]/15">
                    <span class="text-[11px] font-mono uppercase tracking-[0.18em] text-[#072923]/60">Last Updated</span>
                    <span class="text-xl font-serif">May 2026</span>
                </div>
                <div class="flex flex-col items-center gap-1 px-4 md:border-r border-[#072923]/15">
                    <span class="text-[11px] font-mono uppercase tracking-[0.18em] text-[#072923]/60">Effective</span>
                    <span class="text-xl font-serif">Immediately</span>
                </div>
                <div class="flex flex-col items-center gap-1 px-4">
                    <span class="text-[11px] font-mono uppercase tracking-[0.18em] text-[#072923]/60">Read Time</span>
                    <span class="text-xl font-serif">2 minutes</span>
                </div>
            </div>
        </section>

        {{-- PROMISE - replaces the illustration --}}
        <section class="max-w-[980px] mx-auto px-8 md:px-16 lg:px-24 mt-24 text-center">
            <div class="text-[#C8CD06] font-serif italic text-[120px] md:text-[180px] leading-[0.6] h-[56px] md:h-[70px] select-none" aria-hidden="true">&ldquo;</div>
            <blockquote class="font-serif text-2xl sm:text-3xl md:text-5xl leading-tight tracking-tight text-[#072923] mt-2">
                Your data belongs to <em class="italic relative inline-block px-1">
                    <span class="relative z-10">you</span>
                    <span class="absolute inset-x-0 bottom-1 h-[0.36em] bg-[#C8CD06]/85 -skew-x-6 rounded-sm"></span>
                </em>.
                We collect only what we need to teach,
                and never sell what you trust us with.
            </blockquote>
            <div class="mt-7 inline-flex items-center gap-3 font-mono text-xs uppercase tracking-[0.22em] text-[#072923]/60">
                <span class="inline-block w-7 h-px bg-[#072923]/15"></span>
                The El Zatuna team
                <span class="inline-block w-7 h-px bg-[#072923]/15"></span>
            </div>
        </section>

        {{-- SECTION HEAD --}}
        <section class="max-w-[1040px] mx-auto px-8 md:px-16 lg:px-24 mt-28">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <div class="font-mono text-xs uppercase tracking-[0.22em] text-[#072923]/60 mb-2">The four pillars</div>
                    <h2 class="font-serif text-4xl md:text-5xl leading-none tracking-tight">What this covers</h2>
                </div>
                <p class="text-sm text-[#072923]/60 md:text-right md:max-w-[320px] leading-relaxed">
                    Four short statements that explain, plainly, what we keep, what we track,
                    how it stays safe, and what you can ask us to do with it.
                </p>
            </div>
        </section>

        {{-- POLICY CARDS - UNCHANGED WORDING --}}
        <section class="max-w-[1040px] mx-auto px-8 md:px-16 lg:px-24 pb-16 mt-7">
            <div class="grid md:grid-cols-2 gap-[18px]">

                <div class="relative bg-white rounded-[28px] p-9 border border-[#ECF4B8] shadow-sm hover:-translate-y-1 hover:border-[#C8CD06] transition">
                    <span class="absolute top-6 right-7 font-serif italic text-[44px] leading-none text-[#C8CD06]">01</span>
                    <div class="w-11 h-11 rounded-xl bg-[#FAFFE0] border border-[#ECF4B8] inline-flex items-center justify-center mb-4">
                        <x-iconsax-lin-user-square class="w-6 h-6 text-[#072923]"/>
                    </div>
                    <h3 class="text-[22px] font-semibold mb-2">Information Collection</h3>
                    <p class="text-[17px] leading-relaxed text-[#072923]/80 max-w-[44ch]">
                        We collect your name, email, university name, and payment information to provide our services.
                    </p>
                    <div class="mt-5 pt-4 border-t border-dashed border-[#072923]/15 flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[#072923]/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C8CD06]"></span>
                        What we keep
                    </div>
                </div>

                <div class="relative bg-white rounded-[28px] p-9 border border-[#ECF4B8] shadow-sm hover:-translate-y-1 hover:border-[#C8CD06] transition">
                    <span class="absolute top-6 right-7 font-serif italic text-[44px] leading-none text-[#C8CD06]">02</span>
                    <div class="w-11 h-11 rounded-xl bg-[#FAFFE0] border border-[#ECF4B8] inline-flex items-center justify-center mb-4">
                        <x-iconsax-lin-activity class="w-6 h-6 text-[#072923]"/>
                    </div>
                    <h3 class="text-[22px] font-semibold mb-2">Usage Tracking</h3>
                    <p class="text-[17px] leading-relaxed text-[#072923]/80 max-w-[44ch]">
                        We track which videos you watch and which points you spend to provide personalized course recommendations.
                    </p>
                    <div class="mt-5 pt-4 border-t border-dashed border-[#072923]/15 flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[#072923]/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C8CD06]"></span>
                        What we observe
                    </div>
                </div>

                <div class="relative bg-white rounded-[28px] p-9 border border-[#ECF4B8] shadow-sm hover:-translate-y-1 hover:border-[#C8CD06] transition">
                    <span class="absolute top-6 right-7 font-serif italic text-[44px] leading-none text-[#C8CD06]">03</span>
                    <div class="w-11 h-11 rounded-xl bg-[#FAFFE0] border border-[#ECF4B8] inline-flex items-center justify-center mb-4">
                        <x-iconsax-lin-shield-tick class="w-6 h-6 text-[#072923]"/>
                    </div>
                    <h3 class="text-[22px] font-semibold mb-2">Data Security</h3>
                    <p class="text-[17px] leading-relaxed text-[#072923]/80 max-w-[44ch]">
                        We use industry-standard encryption to protect your data. We do not sell your personal information to third-party advertisers.
                    </p>
                    <div class="mt-5 pt-4 border-t border-dashed border-[#072923]/15 flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[#072923]/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C8CD06]"></span>
                        How we protect it
                    </div>
                </div>

                <div class="relative bg-white rounded-[28px] p-9 border border-[#ECF4B8] shadow-sm hover:-translate-y-1 hover:border-[#C8CD06] transition">
                    <span class="absolute top-6 right-7 font-serif italic text-[44px] leading-none text-[#C8CD06]">04</span>
                    <div class="w-11 h-11 rounded-xl bg-[#FAFFE0] border border-[#ECF4B8] inline-flex items-center justify-center mb-4">
                        <x-iconsax-lin-profile-tick class="w-6 h-6 text-[#072923]"/>
                    </div>
                    <h3 class="text-[22px] font-semibold mb-2">Student Rights</h3>
                    <p class="text-[17px] leading-relaxed text-[#072923]/80 max-w-[44ch]">
                        You may request to view, edit, or delete your personal data at any time by contacting our support team.
                    </p>
                    <div class="mt-5 pt-4 border-t border-dashed border-[#072923]/15 flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-[#072923]/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#C8CD06]"></span>
                        What you can do
                    </div>
                </div>

            </div>

            {{-- FOOT - closing note --}}
            <div class="mt-16 border border-[#072923]/15 rounded-[28px] p-8 bg-[#FBFFE6] flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="flex items-center gap-4">
                    <x-iconsax-lin-message-question class="w-6 h-6 text-[#C8CD06] flex-shrink-0"/>
                    <div>
                        <div class="font-serif text-2xl leading-tight">Questions about your data?</div>
                        <div class="text-sm text-[#072923]/60 mt-1">Our team replies within one business day.</div>
                    </div>
                </div>
                <a href="{{ \Illuminate\Support\Facades\Route::has('web.contact') ? route('web.contact') : url('/contact') }}" class="bg-[#072923] text-[#FAFFE0] border border-[#072923] px-6 py-3.5 rounded-full text-sm font-medium hover:bg-[#0d3a31] transition whitespace-nowrap">
                    Contact support ->
                </a>
            </div>
        </section>

    </main>
@endsection
