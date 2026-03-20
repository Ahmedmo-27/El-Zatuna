@extends('design_1.web.layouts.app')

@section('content')
    <main class="home-page bg-[#FAFFE0] text-[#072923] min-h-screen space-y-20 md:space-y-28">

        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 pt-10 md:pt-16 pb-12 md:pb-18">
            <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-10 lg:gap-14 items-center">
            <div class="hero-left-col">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-semibold leading-tight">
                        Got <span class="text-[#C8CD06]">El Zatuna</span> !<br>
                        <span class="block mb-3">You're Already</span>
                        <span id="typing-text" class="text-[#C8CD06]">Ahead It</span><span class="cursor">|</span>
                    </h1>
                    <p class="mt-6 md:mt-8 text-sm sm:text-base md:text-lg lg:text-xl text-[#072923]/70 max-w-2xl font-family-arial leading-relaxed">
                        Join thousands of ambitious learners building their futures with El Zatuna’s
                        expert‑led courses. Connect with world‑class instructors, learn at your pace — all in one powerful learning platform.
                    </p>
                    
                    <div class="mt-8 md:mt-9 flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-4 sm:gap-5">
                        <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-bold px-8 sm:px-9 py-4 sm:py-[18px] rounded-full text-sm sm:text-base hover:bg-[#BDEA42] hover:scale-110 transition-all duration-300 shadow-2xl relative z-10 block opacity-100 !visible flex items-center justify-center gap-4"><x-iconsax-lin-book class="w-5 h-5 sm:w-6 sm:h-6"/> Enroll on courses</a>
                        <a href="/contact?type=request_course" class="border-2 border-[#072923] text-[#072923] font-bold px-8 sm:px-9 py-4 sm:py-[18px] rounded-full text-sm sm:text-base hover:bg-[#072923] hover:text-[#FAFFE0] transition-all duration-300 relative z-10 block opacity-100 !visible flex items-center justify-center gap-4"><x-iconsax-lin-sms class="w-5 h-5 sm:w-6 sm:h-6"/> Request Course</a>
                    </div>

                    <div data-trust-badge class="mt-10 md:mt-12 inline-flex items-center gap-4 rounded-2xl border border-[#E4EDAA] bg-[#F7FDD4] px-4 sm:px-5 py-3 shadow-[0_8px_24px_rgba(7,41,35,0.08)]">
                        <div class="flex -space-x-2.5">
                            <span class="h-8 w-8 rounded-full border-2 border-[#F7FDD4] bg-[#DCEB9B] text-[#072923] text-[11px] font-bold flex items-center justify-center shadow-sm">AR</span>
                            <span class="h-8 w-8 rounded-full border-2 border-[#F7FDD4] bg-[#0E3A31] text-[#FAFFE0] text-[11px] font-bold flex items-center justify-center shadow-sm">LM</span>
                            <span class="h-8 w-8 rounded-full border-2 border-[#F7FDD4] bg-[#AFCB2D] text-[#072923] text-[11px] font-bold flex items-center justify-center shadow-sm">SK</span>
                        </div>

                        <div class="leading-tight">
                            <div class="flex items-center gap-1 text-[#C8CD06]">
                                <x-iconsax-bol-star class="w-3.5 h-3.5"/>
                                <x-iconsax-bol-star class="w-3.5 h-3.5"/>
                                <x-iconsax-bol-star class="w-3.5 h-3.5"/>
                                <x-iconsax-bol-star class="w-3.5 h-3.5"/>
                                <x-iconsax-bol-star class="w-3.5 h-3.5"/>
                                <span class="ml-1 text-xs font-semibold text-[#072923]/75">4.9</span>
                            </div>
                            <p class="mt-1 text-sm sm:text-[15px] font-medium text-[#072923]/80">Trusted by 2,500+ successful students</p>
                        </div>
                    </div>
                </div>

                <div class="hero-right-col mt-10 lg:mt-0">
                    <div id="hero-3d-container" class="relative h-[280px] sm:h-[340px] md:h-[500px] w-full max-w-[560px] mx-auto overflow-visible border-2 sm:border-4 border-[#C8CD06] rounded-[32px] sm:rounded-[48px] bg-[#072923]/5 shadow-2xl">
                        <model-viewer
                            src="/3dmodels/robotzatuna.glb"
                            alt="Laptop 3D model"
                            camera-controls
                            auto-rotate
                            rotation-per-second="20deg"
                            camera-orbit="65deg 75deg 2.8m"
                            min-camera-orbit="auto auto 2.8m"
                            max-camera-orbit="auto auto 2.2m"
                            exposure="1"
                            shadow-intensity="1"
                            class="w-full h-full"
                        ></model-viewer>
                    </div>
                </div>
            </div>

            <div class="mt-14 bg-[#BDEA42] rounded-[28px] px-6 py-8 md:px-10 md:py-9">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                                        @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-profile-2user', 'value' => '257', 'label' => 'Students'])
                    @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-book', 'value' => number_format($professionalCoursesCount ?? 0), 'label' => 'Professional Courses'])
                    @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-briefcase', 'value' => number_format($instructorsCount ?? 0), 'label' => 'Skilled Instructors'])
                </div>
            </div>
        </section>

        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-16 md:py-20">
            <div class="grid lg:grid-cols-[1fr_1.1fr] gap-14 items-center">
            <div class="pl-2 md:pl-4">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold">Using <span class="text-[#C8CD06]">Advanced</span><br>Learning Features</h2>
                    <p class="mt-5 text-base md:text-lg text-[#072923]/70 max-w-lg leading-relaxed">Access modern tools, interactive content, and expert resources to master new skills and stay competitive.</p>
                    <ul class="mt-8 space-y-5 text-base md:text-lg">
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Flexible Learning Schedule</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Affordable Course Prices</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Expert Instructor Access</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Self‑Paced Progression</li>
                    </ul>
                    <a href="/classes" class="mt-14 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-8 py-4 rounded-full text-sm hover:bg-[#BDEA42] hover:scale-105 transition-all duration-200">Learn More</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-7">
                    @include('design_1.web.components.home.feature_card', ['icon' => 'iconsax-lin-tick-circle', 'title' => "Courses Club\nTo Support Student", 'description' => 'Support student in each courses to boost understanding and track progress effectively.'])
                    @include('design_1.web.components.home.feature_card', ['icon' => 'iconsax-lin-star', 'title' => "Explanation Practicing\nContent", 'description' => 'Earn official certificates upon completion to showcase skills and add credibility.'])
                    @include('design_1.web.components.home.feature_card', ['icon' => 'iconsax-lin-element-4', 'title' => "Courses Content\nSpecific To Your Uni", 'description' => 'Apply what you learn with real assignments that reinforce skills and deepen practical knowledge.', 'spanClass' => 'sm:col-span-2 sm:w-[calc(50%-14px)] sm:mx-auto'])
                </div>
            </div>
        </section>

        <section class="marquee-divider">
            <div class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16">
                <div class="marquee-divider__track text-sm md:text-base flex gap-16">
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                </div>
            </div>
        </section>

        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-16 md:py-20">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold mb-16">Explore <span class="text-[#C8CD06]">Upcoming</span><br>Courses</h2>
            <p class="text-base md:text-lg text-[#072923]/70 leading-relaxed mb-14">Stay ahead with fresh courses launching soon, designed to expand your skills and knowledge further.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-9">
                @foreach(($upcomingCourses ?? collect()) as $upcomingCourse)
                    @include('design_1.web.components.home.course_card_dark', [
                        'href' => $upcomingCourse->getUrl(),
                        'image' => $upcomingCourse->getImageCover() ?? $upcomingCourse->thumbnail ?? 'https://placehold.co/600x400/072923/FAFFE0',
                        'title' => $upcomingCourse->title,
                        'subtitle' => $upcomingCourse->teacher->full_name ?? 'Instructor',
                    ])
                @endforeach
            </div>
            <a href="/classes" class="mt-20 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm">View More</a>
        </section>

        @if(($discountedCourses ?? collect())->isNotEmpty())
        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-20 md:py-24">
            <div class="bg-[#072923] rounded-[32px] p-10 md:p-14 lg:p-16">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between text-[#FAFFE0] gap-10 md:gap-12">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-semibold"><span class="text-[#C8CD06]">Discounted</span> Courses</h2>
                        <p class="mt-5 text-sm text-[#FAFFE0]/70">Save more now with top courses at discounts</p>
                    </div>
                    <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-semibold px-5 py-2 rounded-full text-sm">View More</a>
                </div>

                <div class="mt-16 md:mt-20 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12">
                    @foreach(($discountedCourses ?? collect()) as $course)
                        @php
                            $ticketData = $course->bestTicket(true);
                            $discountedPrice = $ticketData['bestTicket'] ?? null;
                            $discountPercent = $ticketData['percent'] ?? 0;
                        @endphp

                        @continue(empty($discountPercent) || empty($discountedPrice) || $discountedPrice >= $course->price)

                        @include('design_1.web.components.home.course_card_light', [
                            'href' => $course->getUrl(),
                            'image' => $course->thumbnail ?? 'https://placehold.co/600x400/FAFFE0/072923',
                            'title' => $course->title,
                            'subtitle' => $course->teacher?->full_name ?? 'Instructor',
                            'originalPrice' => $course->price,
                            'discountedPrice' => $discountedPrice,
                            'discountPercent' => $discountPercent,
                        ])
                    @endforeach
                </div>
                <p class="mt-20 md:mt-24 pt-2 pb-4 text-xs text-[#FAFFE0]/70">Over $240K Saved With Exclusive Course Discounts</p>
            </div>
        </section>
        @endif

        @if($freeCourses->isNotEmpty())
        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-16 md:py-20">
            <h2 class="text-2xl md:text-3xl font-semibold">Free <span class="text-[#C8CD06]">Courses</span></h2>
            <p class="mt-2 text-sm text-[#072923]/70">Access top‑quality free courses anytime, expand your skills, and learn without spending a single dollar.</p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(($freeCourses ?? collect()) as $freeCourse)
                    @include('design_1.web.components.home.course_card_dark', [
                        'href' => $freeCourse->getUrl(),
                        'image' => $freeCourse->thumbnail ?? 'https://placehold.co/600x400/072923/FAFFE0',
                        'title' => $freeCourse->title,
                        'subtitle' => $freeCourse->teacher->full_name ?? 'Instructor',
                        'height' => 'h-[220px]',
                        'bodyPadding' => 'p-4',
                    ])
                @endforeach
            </div>
        </section>
        @endif

        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-16 md:py-20">
            <div class="grid lg:grid-cols-[1fr_1fr] gap-10 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold">Start Sharing Skills,<br><span class="text-[#C8CD06]">Build Courses</span></h2>
                    <p class="mt-4 text-base md:text-lg text-[#072923]/70">Join our platform, share your expertise, reach thousands of learners,<br>and earn income effortlessly online today.</p>
                    <ul class="mt-6 space-y-3 text-base md:text-lg">
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Flexible Teaching Schedule</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Global Student Reach</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Earn Extra Income</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Build Personal Brand</li>
                    </ul>
                    <a href="/become-instructor" class="mt-10 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm">Become instructor</a>
                </div>
                <div class="h-[260px] md:h-[320px] lg:h-[360px] flex items-center justify-center">
                    <img src="/pfpfallback.png" alt="Instructors" class="w-full max-w-[520px] md:max-w-[620px] lg:max-w-[700px] h-auto" />
                </div>
            </div>
        </section>

        @auth
            <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-18 md:py-22">
                <div class="bg-[#072923] rounded-[36px] p-10 md:p-14 lg:p-16 shadow-[0_20px_60px_rgba(7,41,35,0.2)]">
                    <div class="grid {{ (($subscriptionPlans ?? collect())->count() > 0) ? 'lg:grid-cols-[minmax(0,420px)_1fr]' : 'lg:grid-cols-1' }} gap-10 lg:gap-14 items-start">
                        <div class="text-[#FAFFE0] max-w-[560px]">
                            <span class="inline-flex items-center gap-2 rounded-full border border-[#C8CD06]/30 bg-[#C8CD06]/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-[#C8CD06]">Subscription Plans</span>
                            <h2 class="mt-6 text-[2.1rem] md:text-[2.55rem] leading-[1.12] font-bold text-[#FAFFE0]">Choose Your Learning Level</h2>
                            <p class="mt-5 text-base md:text-[1.35rem] leading-[1.6] text-[#FAFFE0]/80 max-w-[32ch]">Discover flexible subscription plans with premium content, structured paths, and ongoing support for your academic journey.</p>

                            <ul class="mt-10 space-y-5">
                                <li class="flex items-center gap-3 text-base md:text-[1.15rem] leading-[1.5]"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Unlimited course access</li>
                                <li class="flex items-center gap-3 text-base md:text-[1.15rem] leading-[1.5]"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Flexible payment options</li>
                                <li class="flex items-center gap-3 text-base md:text-[1.15rem] leading-[1.5]"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Regular content updates</li>
                            </ul>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-7">
                            @foreach(($subscriptionPlans ?? collect())->take(3) as $plan)
                                <div class="bg-[#FAFFE0] rounded-[28px] border border-[#E4EDAA] p-6 md:p-7 flex flex-col shadow-[0_10px_30px_rgba(7,41,35,0.1)]">
                                    <div class="h-12 w-12 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4 text-2xl">
                                        <x-iconsax-lin-crown class="w-7 h-7 text-[#072923]"/>
                                    </div>
                                    <h3 class="text-2xl font-bold text-[#072923]">{{ $plan->title }}</h3>
                                    @if(!empty($plan->subtitle))
                                        <p class="mt-1 text-sm text-[#072923]/65">{{ $plan->subtitle }}</p>
                                    @endif

                                    <div class="mt-4 text-4xl font-bold text-[#072923]">{{ handlePrice($plan->price, true, true, false, null, true) }}</div>

                                    <ul class="mt-4 space-y-2.5 text-sm text-[#072923]/75 flex-1">
                                        @if(!empty($plan->description))
                                            <li>{{ $plan->description }}</li>
                                        @endif
                                        <li class="flex items-center gap-2">
                                            <x-iconsax-lin-tick-circle class="w-4 h-4 text-[#C8CD06]"/>
                                            {{ $plan->days }} Days
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <x-iconsax-lin-tick-circle class="w-4 h-4 text-[#C8CD06]"/>
                                            @if(!empty($plan->infinite_use))
                                                Unlimited Uses
                                            @else
                                                {{ $plan->usable_count }} Uses
                                            @endif
                                        </li>
                                    </ul>

                                    <form class="mt-6" method="post" action="/cart/store">
                                        @csrf
                                        <input type="hidden" name="item_name" value="subscribe_id">
                                        <input type="hidden" name="item_id" value="{{ $plan->id }}">
                                        <button type="submit" class="w-full bg-[#C8CD06] text-[#072923] font-semibold py-3 rounded-full text-base">Purchase</button>
                                    </form>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </section>
        @endauth

        <section class="max-w-[1360px] mx-auto px-5 sm:px-8 lg:px-12 xl:px-16 py-16 md:py-20">
            <h2 class="text-2xl md:text-3xl font-semibold mb-10">Expert Instructors</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
                @foreach(($instructors ?? collect()) as $instructor)
                    <a href="/users/{{ $instructor->username ?? $instructor->id }}/profile" class="flex flex-col items-center justify-center gap-4 rounded-[20px] bg-[#072923] p-6 aspect-square w-full">
                        <img loading="lazy" src="{{ $instructor->avatar ?? $instructor->getAvatar(112) ?? '/assets/admin/img/avatar/avatar-4.png' }}" alt="{{ $instructor->full_name }}" class="h-28 w-28 rounded-full object-cover bg-[#FAFFE0]" onerror="this.onerror=null;this.src='/assets/admin/img/avatar/avatar-4.png';" />
                        <div class="text-[#FAFFE0] text-center">
                            <div class="font-semibold text-lg">{{ $instructor->full_name }}</div>
                            <div class="text-sm text-[#FAFFE0]/70">{{ $instructor->headline ?? 'Instructor' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-12 flex flex-col items-start gap-3">
                <p class="text-sm text-[#C8CD06]">{{ number_format($instructorsCount ?? 0) }} skilled instructors available to assist you every step of the way</p>
                <a href="/instructors" class="bg-[#C8CD06] text-[#072923] font-semibold px-5 py-2 rounded-full text-sm">All Instructors</a>
            </div>
        </section>

    </main>

@push('styles_top')
<style>
    .cursor {
        display: inline-block;
        width: 3px;
        background-color: #C8CD06;
        margin-left: 2px;
        animation: blink 1s step-end infinite;
        vertical-align: middle;
    }
    @keyframes blink {
        from, to { opacity: 0; }
        50% { opacity: 1; }
    }
</style>
@endpush

@push('scripts_bottom')
<script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Typing Animation logic
    const typingText = document.getElementById('typing-text');
    const phrases = ["Ahead It", "The Leader", "Mastering Skills", "Your Future Self"];
    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typeSpeed = 100;

    function type() {
        const currentPhrase = phrases[phraseIndex];
        
        if (isDeleting) {
            typingText.textContent = currentPhrase.substring(0, charIndex - 1);
            charIndex--;
            typeSpeed = 50;
        } else {
            typingText.textContent = currentPhrase.substring(0, charIndex + 1);
            charIndex++;
            typeSpeed = 150;
        }

        if (!isDeleting && charIndex === currentPhrase.length) {
            isDeleting = true;
            typeSpeed = 2000; // Pause at the end
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % phrases.length;
            typeSpeed = 500;
        }

        setTimeout(type, typeSpeed);
    }
    
    if (typingText) {
        type();
    }

    // GSAP Animations
    if (typeof gsap !== 'undefined') {
        // Hero section animations
        gsap.from('h1', { duration: 1.2, y: 80, opacity: 0, ease: 'power4.out' });
        gsap.from('h1 + p', { duration: 1, y: 40, opacity: 0, ease: 'power3.out', delay: 0.3 });
        gsap.from('[data-trust-badge]', { duration: 0.8, y: 20, opacity: 0, ease: 'power3.out', delay: 0.8 });
        
        // Stats bar animation
        gsap.from('.bg-\\[\\#BDEA42\\].rounded-\\[28px\\]', { 
            duration: 1.2, 
            y: 60, 
            opacity: 0, 
            ease: 'power3.out',
            delay: 1
        });
        
        // Section animations on scroll
        const sections = document.querySelectorAll('section');
        sections.forEach((section, index) => {
            if (index > 0) {
                gsap.from(section, {
                    y: 50,
                    opacity: 0,
                    duration: 1,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: section,
                        start: 'top 85%'
                    }
                });
            }
        });
        
        // Cards stagger animation
        const cards = document.querySelectorAll('.rounded-\\[24px\\], .rounded-\\[20px\\]');
        cards.forEach(card => {
            gsap.from(card, {
                y: 40,
                opacity: 0,
                duration: 0.8,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: card,
                    start: 'top 90%'
                }
            });
            
            // Hover effect
            card.addEventListener('mouseenter', () => {
                gsap.to(card, { scale: 1.03, duration: 0.3, ease: 'power2.out' });
            });
            card.addEventListener('mouseleave', () => {
                gsap.to(card, { scale: 1, duration: 0.3, ease: 'power2.out' });
            });
        });
        
        // Button hover animations
        const buttons = document.querySelectorAll('a.rounded-full, button.rounded-full');
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', () => {
                gsap.to(btn, { scale: 1.05, duration: 0.2, ease: 'power2.out' });
            });
            btn.addEventListener('mouseleave', () => {
                gsap.to(btn, { scale: 1, duration: 0.2, ease: 'power2.out' });
            });
        });
        
        // Subscription section animation
        const subscriptionSection = document.querySelector('.bg-\\[\\#072923\\].rounded-\\[32px\\]');
        if (subscriptionSection) {
            gsap.from(subscriptionSection, {
                y: 80,
                opacity: 0,
                duration: 1.2,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: subscriptionSection,
                    start: 'top 80%'
                }
            });
        }
        
        // Instructor cards stagger (REMOVED)
    }

});
</script>
@endpush
@endsection

