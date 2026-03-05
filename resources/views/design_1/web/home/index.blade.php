@extends('design_1.web.layouts.app')

@section('content')
    <main class="home-page bg-[#FAFFE0] text-[#072923] min-h-screen space-y-16 md:space-y-24">

        <section class="max-w-[1700px] mx-auto px-12 sm:px-8 md:px-24 lg:px-32 xl:px-44 pl-20 md:pl-28 lg:pl-36 xl:pl-44 pt-16 md:pt-24 pb-20 md:pb-32">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-10 lg:gap-20 items-center">
            <div class="hero-left-col pl-12 sm:pl-16 md:pl-24 lg:pl-32 xl:pl-40">
                    <h1 class="text-3xl sm:text-4xl md:text-6xl lg:text-7xl font-semibold leading-tight">
                        Got <span class="text-[#C8CD06]">El Zatuna</span> !<br>
                        <span class="block mb-3">You're Already</span>
                        <span id="typing-text" class="text-[#C8CD06]">Ahead It</span><span class="cursor">|</span>
                    </h1>
                    <p class="mt-6 md:mt-8 text-sm sm:text-base md:text-lg lg:text-xl text-[#072923]/70 max-w-2xl font-family-arial leading-relaxed">
                        Join thousands of ambitious learners building their futures with El Zatuna’s
                        expert‑led courses. Connect with world‑class instructors, learn at your pace — all in one powerful learning platform.
                    </p>
                    
                    <div class="mt-8 md:mt-10 flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-4 sm:gap-6">
                        <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-bold px-8 sm:px-9 py-4 sm:py-[18px] rounded-full text-sm sm:text-base hover:bg-[#BDEA42] hover:scale-110 transition-all duration-300 shadow-2xl relative z-10 block opacity-100 !visible flex items-center justify-center gap-4"><x-iconsax-lin-book class="w-5 h-5 sm:w-6 sm:h-6"/> Enroll on courses</a>
                        <a href="/contact" class="border-2 border-[#072923] text-[#072923] font-bold px-8 sm:px-9 py-4 sm:py-[18px] rounded-full text-sm sm:text-base hover:bg-[#072923] hover:text-[#FAFFE0] transition-all duration-300 relative z-10 block opacity-100 !visible flex items-center justify-center gap-4"><x-iconsax-lin-sms class="w-5 h-5 sm:w-6 sm:h-6"/> Request Course</a>
                    </div>

                    <div class="mt-16 md:mt-14 inline-flex items-center gap-3 bg-[#FAFFE0] border border-[#ECF4B8] rounded-full px-3 sm:px-4 py-2">
                        <div class="flex -space-x-2">
                            <div class="h-7 w-7 rounded-full bg-[#A3B18A]"></div>
                            <div class="h-7 w-7 rounded-full bg-[#072923]"></div>
                            <div class="h-7 w-7 rounded-full bg-[#C8CD06]"></div>
                        </div>
                        <div class="text-xs sm:text-sm">
                            <div class="flex items-center gap-0.5 text-[#C8CD06]">
                                <x-iconsax-bol-star class="w-3 h-3"/>
                                <x-iconsax-bol-star class="w-3 h-3"/>
                                <x-iconsax-bol-star class="w-3 h-3"/>
                                <x-iconsax-bol-star class="w-3 h-3"/>
                                <x-iconsax-bol-star class="w-3 h-3"/>
                            </div>
                            <div class="text-[#072923]/70">Trusted by 2500+ Successful student</div>
                        </div>
                    </div>
                </div>

                <div class="hero-right-col pr-0 md:pr-8 lg:pr-16 xl:pr-24 mt-10 lg:mt-0">
                    <div id="hero-3d-container" class="relative h-[300px] sm:h-[360px] md:h-[540px] w-full max-w-[620px] lg:-ml-12 mx-auto lg:mx-0 overflow-visible border-2 sm:border-4 border-[#C8CD06] rounded-[32px] sm:rounded-[48px] bg-[#072923]/5 shadow-2xl">
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

            <div class="mt-16 bg-[#BDEA42] rounded-[28px] px-8 py-10 md:px-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-briefcase', 'value' => '257', 'label' => 'Skillful Instructor'])
                    @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-book', 'value' => '29', 'label' => 'Professional Courses'])
                    @include('design_1.web.components.home.stat_item', ['icon' => 'iconsax-lin-building-3', 'value' => '6', 'label' => 'Official Organizations'])
                </div>
            </div>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 pl-16 md:pl-28 lg:pl-36 py-24">
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
            <div class="max-w-[1700px] mx-auto px-6 md:px-12">
                <div class="marquee-divider__track text-sm md:text-base flex gap-16">
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text mx-10">Learn. Grow. Lead.</span>
                </div>
            </div>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-24">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold mb-16">Explore <span class="text-[#C8CD06]">Upcoming</span><br>Courses</h2>
            <p class="text-base md:text-lg text-[#072923]/70 leading-relaxed mb-14">Stay ahead with fresh courses launching soon, designed to expand your skills and knowledge further.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-9">
                @foreach(($upcomingCourses ?? collect()) as $upcomingCourse)
                    @include('design_1.web.components.home.course_card_dark', [
                        'href' => '/upcoming_courses/' . $upcomingCourse->slug,
                        'image' => $upcomingCourse->getImageCover() ?? $upcomingCourse->thumbnail ?? 'https://placehold.co/600x400/072923/FAFFE0',
                        'title' => $upcomingCourse->title,
                        'subtitle' => $upcomingCourse->teacher->full_name ?? 'Instructor',
                    ])
                @endforeach
            </div>
            <a href="/upcoming_courses" class="mt-20 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm">View More</a>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-28">
            <div class="bg-[#072923] rounded-[32px] p-10 md:p-14 lg:p-16">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between text-[#FAFFE0] gap-10 md:gap-12">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-semibold"><span class="text-[#C8CD06]">Discounted</span> Courses</h2>
                        <p class="mt-5 text-sm text-[#FAFFE0]/70">Save more now with top courses at discounts</p>
                    </div>
                    <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-semibold px-5 py-2 rounded-full text-sm">View More</a>
                </div>

                @php
                    $discountedCourses = $discountedCourses ?? collect();
                    $discountedCards = $discountedCourses->isNotEmpty() ? $discountedCourses : collect([1, 2, 3]);
                @endphp

                <div class="mt-16 md:mt-20 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12">
                    @foreach(($discountedCards ?? collect()) as $course)
                        @include('design_1.web.components.home.course_card_light', [
                            'href' => is_object($course) ? $course->getUrl() : '/classes',
                            'image' => is_object($course) ? ($course->thumbnail ?? 'https://placehold.co/600x400/FAFFE0/072923') : 'https://placehold.co/600x400/FAFFE0/072923',
                            'title' => is_object($course) ? $course->title : 'Discounted course',
                            'subtitle' => is_object($course) && $course->teacher ? $course->teacher->full_name : 'Instructor',
                        ])
                    @endforeach
                </div>
                <p class="mt-20 md:mt-24 pt-2 pb-4 text-xs text-[#FAFFE0]/70">Over $240K Saved With Exclusive Course Discounts</p>
            </div>
        </section>

        @if($freeCourses->isNotEmpty())
        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-24">
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

        <section class="max-w-[1600px] mx-auto px-6 md:px-8 pl-16 md:pl-28 lg:pl-36 py-20">
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
            <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-20">
                <div class="bg-[#072923] rounded-[32px] p-6 md:p-12">
                    <div class="grid lg:grid-cols-[1fr_2fr] gap-10 items-start">
                        <div class="text-[#FAFFE0]">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="h-14 w-14 bg-[#C8CD06] rounded-lg flex items-center justify-center text-2xl">
                                    <x-iconsax-lin-teacher class="w-8 h-8 text-[#072923]"/>
                                </div>
                                <div class="h-14 w-14 bg-[#BDEA42] rounded-lg flex items-center justify-center text-2xl">
                                    <x-iconsax-lin-star class="w-8 h-8 text-[#072923]"/>
                                </div>
                            </div>
                            <h2 class="text-3xl md:text-4xl font-bold mb-4" style="color: #FF6B35;">Subscription</h2>
                            <p class="text-sm mb-6">Discover flexible subscription plans that unlock access to courses, resources, and exclusive educational content. Choose your level.</p>
                            
                            <ul class="space-y-3 mb-8">
                                <li class="flex items-center gap-2 text-sm"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Unlimited course access</li>
                                <li class="flex items-center gap-2 text-sm"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Flexible payment options</li>
                                <li class="flex items-center gap-2 text-sm"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Regular content updates</li>
                            </ul>
                            
                            <button class="bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm flex items-center gap-2">
                                 <x-iconsax-lin-crown class="w-5 h-5"/> Vip Member
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-[#FAFFE0] rounded-[24px] p-6 flex flex-col">
                                <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4 text-2xl">
                                    <x-iconsax-lin-cup class="w-8 h-8 text-[#072923]"/>
                                </div>
                                <h3 class="text-xl font-bold text-[#072923] mb-2">Starter Access</h3>
                                <p class="text-xs text-[#072923]/60 mb-4">Ideal for beginners eager to start learning.</p>
                                <div class="text-3xl font-bold text-[#072923] mb-4">$20</div>
                                <ul class="space-y-2 mb-6 flex-1">
                                    <li class="text-xs text-[#072923]/70">Select top beginner courses, gain access to kickstart your learning journey.</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70 mt-3"><x-iconsax-lin-tick-circle class="w-4 h-4 text-[#C8CD06]"/> 15 Days of Subscription</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70"><x-iconsax-lin-tick-circle class="w-4 h-4 text-[#C8CD06]"/> 100 Subscriptions</li>
                                </ul>
                                <button class="w-full bg-[#C8CD06] text-[#072923] font-semibold py-3 rounded-full text-sm">Purchase</button>
                            </div>

                            <div class="bg-[#FAFFE0] rounded-[24px] p-6 flex flex-col">
                                <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4 text-2xl">
                                    <x-iconsax-lin-crown class="w-8 h-8 text-[#072923]"/>
                                </div>
                                <h3 class="text-xl font-bold text-[#072923] mb-2">Pro Plus</h3>
                                <p class="text-xs text-[#072923]/60 mb-4">Advanced tools for serious learners.</p>
                                <div class="text-3xl font-bold text-[#072923] mb-4">$100</div>
                                <ul class="space-y-2 mb-6 flex-1">
                                    <li class="text-xs text-[#072923]/70">Designed for serious learners who want in-depth content and exclusive resources.</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70 mt-3"><span class="text-[#C8CD06]">✔</span> 30 Days of Subscription</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70"><span class="text-[#C8CD06]">✔</span> 1000 Subscriptions</li>
                                </ul>
                                <button class="w-full bg-[#C8CD06] text-[#072923] font-semibold py-3 rounded-full text-sm">Purchase</button>
                            </div>

                            <div class="bg-[#FAFFE0] rounded-[24px] p-6 flex flex-col">
                                <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4 text-2xl">
                                    <x-iconsax-lin-star class="w-8 h-8 text-[#072923]"/>
                                </div>
                                <h3 class="text-xl font-bold text-[#072923] mb-2">Elite Mastery</h3>
                                <p class="text-xs text-[#072923]/60 mb-4">Exclusive access for expert users.</p>
                                <div class="text-3xl font-bold text-[#072923] mb-4">$40</div>
                                <ul class="space-y-2 mb-6 flex-1">
                                    <li class="text-xs text-[#072923]/70">Exclusive access for expert users, personalized support and advanced content for mastery.</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70 mt-3"><span class="text-[#C8CD06]">✔</span> 30 Days of Subscription</li>
                                    <li class="flex items-center gap-2 text-xs text-[#072923]/70"><span class="text-[#C8CD06]">✔</span> 800 Subscriptions</li>
                                </ul>
                                <button class="w-full bg-[#C8CD06] text-[#072923] font-semibold py-3 rounded-full text-sm">Purchase</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endauth

        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-20">
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
                <p class="text-sm text-[#C8CD06]">400+ skilled instructors available to assist you every step of the way</p>
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
        gsap.from('.inline-flex.items-center.gap-3', { duration: 0.8, y: 20, opacity: 0, ease: 'power3.out', delay: 0.8 });
        
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

