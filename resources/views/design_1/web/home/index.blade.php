@extends('design_1.web.layouts.app')

@section('content')
    <main class="home-page bg-[#FAFFE0] text-[#072923] min-h-screen space-y-12 md:space-y-20" style="transform: scale(0.97); transform-origin: top center;">

        <section class="max-w-[1700px] mx-auto px-12 md:px-24 lg:px-32 xl:px-44 pt-24 pb-32">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-16 lg:gap-24 items-center">
                <div class="hero-left-col pl-14 md:pl-24 lg:pl-32 xl:pl-40">
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-semibold leading-tight">
                        Got <span class="text-[#C8CD06]">El Zatuna</span> !<br>
                        You're Already<br> <span id="typing-text" class="text-[#C8CD06]">Ahead It</span><span class="cursor">|</span>
                    </h1>
                    <p class="mt-8 text-lg md:text-xl lg:text-2xl text-[#072923]/70 max-w-2xl">
                        Join thousands of ambitious learners building their futures with El Zatuna’s
                        expert‑led courses. Connect with world‑class instructors, learn at your pace — all in one powerful learning platform.
                    </p>
                    
                    <div class="mt-10 flex flex-wrap items-center gap-6">
                        <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-bold px-12 py-6 rounded-full text-2xl hover:bg-[#BDEA42] hover:scale-110 transition-all duration-300 shadow-2xl relative z-10 block opacity-100 !visible flex items-center gap-3"><x-iconsax-lin-book class="w-8 h-8"/> Enroll on courses</a>
                        <a href="/contact" class="border-2 border-[#072923] text-[#072923] font-bold px-12 py-6 rounded-full text-2xl hover:bg-[#072923] hover:text-[#FAFFE0] transition-all duration-300 relative z-10 block opacity-100 !visible flex items-center gap-3"><x-iconsax-lin-sms class="w-8 h-8"/> Request Course</a>
                    </div>

                    <div class="mt-12 inline-flex items-center gap-3 bg-[#FAFFE0] border border-[#ECF4B8] rounded-full px-4 py-2">
                        <div class="flex -space-x-2">
                            <div class="h-7 w-7 rounded-full bg-[#A3B18A]"></div>
                            <div class="h-7 w-7 rounded-full bg-[#072923]"></div>
                            <div class="h-7 w-7 rounded-full bg-[#C8CD06]"></div>
                        </div>
                        <div class="text-xs">
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

                <div class="hero-right-col pr-10 md:pr-16 lg:pr-24 xl:pr-32">
                    <div id="hero-3d-container" class="relative h-[440px] md:h-[540px] w-full max-w-[620px] lg:-ml-12 mx-auto lg:mx-0 overflow-visible border-4 border-[#C8CD06] rounded-[48px] bg-[#072923]/5 shadow-2xl">
                        <!-- 3D Model will be rendered here -->
                    </div>
                </div>
            </div>

            <div class="mt-16 bg-[#BDEA42] rounded-[28px] px-8 py-10 md:px-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                    <div class="flex items-center justify-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-[#FAFFE0] flex items-center justify-center text-xl">
                            <x-iconsax-lin-briefcase class="w-6 h-6 text-[#072923]"/>
                        </div>
                        <div>
                            <div class="text-3xl font-semibold">257</div>
                            <div class="text-xs text-[#072923]/70">Skillful Instructor</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-[#FAFFE0] flex items-center justify-center text-xl">
                            <x-iconsax-lin-book class="w-6 h-6 text-[#072923]"/>
                        </div>
                        <div>
                            <div class="text-3xl font-semibold">29</div>
                            <div class="text-xs text-[#072923]/70">Professional Courses</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-[#FAFFE0] flex items-center justify-center text-xl">
                            <x-iconsax-lin-building-3 class="w-6 h-6 text-[#072923]"/>
                        </div>
                        <div>
                            <div class="text-3xl font-semibold">6</div>
                            <div class="text-xs text-[#072923]/70">Official Organizations</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-24">
            <div class="grid lg:grid-cols-[1fr_1.1fr] gap-12 items-center">
                <div class="pl-2 md:pl-4">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-semibold">Using <span class="text-[#C8CD06]">Advanced</span><br>Learning Features</h2>
                    <p class="mt-4 text-base md:text-lg text-[#072923]/70 max-w-lg">Access modern tools, interactive content, and expert resources to master new skills and stay competitive.</p>
                    <ul class="mt-8 space-y-4 text-base md:text-lg">
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Flexible Learning Schedule</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Affordable Course Prices</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Expert Instructor Access</li>
                        <li class="flex items-center gap-3"><x-iconsax-lin-tick-circle class="w-6 h-6 text-[#C8CD06]"/> Self‑Paced Progression</li>
                    </ul>
                    <a href="/classes" class="mt-8 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-8 py-4 rounded-full text-lg hover:bg-[#BDEA42] hover:scale-105 transition-all duration-200">Learn More</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-[#072923] text-[#FAFFE0] rounded-[24px] p-6 h-[160px] flex flex-col justify-between">
                        <div class="h-10 w-10 rounded-full bg-[#FAFFE0] text-[#072923] flex items-center justify-center">
                             <x-iconsax-lin-tick-circle class="w-6 h-6"/>
                        </div>
                        <div>
                            <div class="font-semibold">Courses Club<br>To Support Student</div>
                            <p class="text-xs text-[#FAFFE0]/70">Support student in each courses to boost understanding and track progress effectively.</p>
                        </div>
                    </div>
                    <div class="bg-[#072923] text-[#FAFFE0] rounded-[24px] p-6 h-[160px] flex flex-col justify-between">
                        <div class="h-10 w-10 rounded-full bg-[#FAFFE0] text-[#072923] flex items-center justify-center">
                             <x-iconsax-lin-star class="w-6 h-6"/>
                        </div>
                        <div>
                            <div class="font-semibold">Explanation Practicing<br>Content</div>
                            <p class="text-xs text-[#FAFFE0]/70">Earn official certificates upon completion to showcase skills and add credibility.</p>
                        </div>
                    </div>
                    <div class="sm:col-span-2 bg-[#072923] text-[#FAFFE0] rounded-[24px] p-6 h-[170px] flex flex-col justify-between">
                        <div class="h-10 w-10 rounded-full bg-[#FAFFE0] text-[#072923] flex items-center justify-center">
                             <x-iconsax-lin-element-4 class="w-6 h-6"/>
                        </div>
                        <div>
                            <div class="font-semibold">Courses Content<br>Specific To Your Uni</div>
                            <p class="text-xs text-[#FAFFE0]/70">Apply what you learn with real assignments that reinforce skills and deepen practical knowledge.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="marquee-divider">
            <div class="max-w-[1700px] mx-auto px-6 md:px-12">
                <div class="marquee-divider__track text-sm md:text-base">
                    <span class="marquee-divider__text">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text">Learn. Grow. Lead.</span>
                    <span class="marquee-divider__text">Learn. Grow. Lead.</span>
                </div>
            </div>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-24">
            <h2 class="text-2xl md:text-3xl font-semibold">Explore <span class="text-[#C8CD06]">Upcoming</span><br>Courses</h2>
            <p class="mt-2 text-sm text-[#072923]/70">Stay ahead with fresh courses launching soon, designed to expand your skills and knowledge further.</p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(($upcomingCourses ?? collect()) as $upcomingCourse)
                    <a href="/upcoming_courses/{{ $upcomingCourse->slug }}" class="block rounded-[24px] bg-[#072923] overflow-hidden">
                        <div class="h-[220px] w-full">
                            <img loading="lazy" src="{{ $upcomingCourse->getImageCover() ?? $upcomingCourse->thumbnail ?? 'https://placehold.co/600x400/072923/FAFFE0' }}" alt="{{ $upcomingCourse->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 text-[#FAFFE0]">
                            <div class="font-semibold text-base">{{ $upcomingCourse->title }}</div>
                            <div class="text-xs text-[#FAFFE0]/70">{{ $upcomingCourse->teacher->full_name ?? 'Instructor' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <a href="/upcoming_courses" class="mt-6 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm">View More</a>
        </section>

        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-16">
            <div class="bg-[#072923] rounded-[32px] p-8 md:p-10 lg:p-12">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between text-[#FAFFE0] gap-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-semibold"><span class="text-[#C8CD06]">Discounted</span> Courses</h2>
                        <p class="text-sm text-[#FAFFE0]/70">Save more now with top courses at discounts</p>
                    </div>
                    <a href="/classes" class="bg-[#C8CD06] text-[#072923] font-semibold px-5 py-2 rounded-full text-sm">View More</a>
                </div>

                @php
                    $discountedCourses = $discountedCourses ?? collect();
                    $discountedCards = $discountedCourses->isNotEmpty() ? $discountedCourses : collect([1, 2, 3]);
                @endphp

                <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach(($discountedCards ?? collect()) as $course)
                        <a href="{{ is_object($course) ? $course->getUrl() : '/classes' }}" class="block rounded-[24px] bg-[#FAFFE0] overflow-hidden">
                            <div class="h-[220px] w-full">
                                <img loading="lazy" src="{{ is_object($course) ? ($course->thumbnail ?? 'https://placehold.co/600x400/FAFFE0/072923') : 'https://placehold.co/600x400/FAFFE0/072923' }}" alt="{{ is_object($course) ? $course->title : 'Discounted course' }}" class="w-full h-full object-cover">
                            </div>
                            <div class="p-4 text-[#072923]">
                                <div class="font-semibold text-base">{{ is_object($course) ? $course->title : 'Discounted course' }}</div>
                                <div class="text-xs text-[#072923]/70">{{ is_object($course) && $course->teacher ? $course->teacher->full_name : 'Instructor' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <p class="mt-4 text-xs text-[#FAFFE0]/70">Over $240K Saved With Exclusive Course Discounts</p>
            </div>
        </section>

        @if($freeCourses->isNotEmpty())
        <section class="max-w-[1600px] mx-auto px-8 md:px-12 lg:px-16 py-24">
            <h2 class="text-2xl md:text-3xl font-semibold">Free <span class="text-[#C8CD06]">Courses</span></h2>
            <p class="mt-2 text-sm text-[#072923]/70">Access top‑quality free courses anytime, expand your skills, and learn without spending a single dollar.</p>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(($freeCourses ?? collect()) as $freeCourse)
                    <a href="{{ $freeCourse->getUrl() }}" class="block rounded-[24px] bg-[#072923] overflow-hidden">
                        <div class="h-[220px] w-full">
                            <img loading="lazy" src="{{ $freeCourse->thumbnail ?? 'https://placehold.co/600x400/072923/FAFFE0' }}" alt="{{ $freeCourse->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 text-[#FAFFE0]">
                            <div class="font-semibold text-base">{{ $freeCourse->title }}</div>
                            <div class="text-xs text-[#FAFFE0]/70">{{ $freeCourse->teacher->full_name ?? 'Instructor' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-20">
            <div class="grid lg:grid-cols-[1fr_1fr] gap-10 items-center">
                <div>
                    <h2 class="text-2xl md:text-3xl font-semibold">Start Sharing Skills,<br><span class="text-[#C8CD06]">Build Courses</span></h2>
                    <p class="mt-3 text-sm text-[#072923]/70">Join our platform, share your expertise, reach thousands of learners, and earn income effortlessly online today.</p>
                    <ul class="mt-6 space-y-2 text-sm">
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Flexible Teaching Schedule</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Global Student Reach</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Earn Extra Income</li>
                        <li class="flex items-center gap-2"><x-iconsax-lin-tick-circle class="w-5 h-5 text-[#C8CD06]"/> Build Personal Brand</li>
                    </ul>
                    <a href="/panel" class="mt-6 inline-flex bg-[#C8CD06] text-[#072923] font-semibold px-6 py-3 rounded-full text-sm">Become instructor</a>
                </div>
                <div class="h-[260px] md:h-[320px] lg:h-[360px] rounded-[28px] bg-[#072923] flex items-center justify-center">
                    <img src="/assets/design_1/img/no-result/profile_instructors.svg" alt="Instructors" class="w-full max-w-[520px] md:max-w-[620px] lg:max-w-[700px] h-auto" />
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
            <h2 class="text-2xl md:text-3xl font-semibold">Expert Instructors</h2>
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach(($instructors ?? collect()) as $instructor)
                    <a href="/users/{{ $instructor->username ?? $instructor->id }}/profile" class="flex items-center gap-4 rounded-[20px] bg-[#072923] p-4">
                        <img loading="lazy" src="{{ $instructor->avatar ?? $instructor->getAvatar(64) ?? 'https://placehold.co/80x80/FAFFE0/072923' }}" alt="{{ $instructor->full_name }}" class="h-16 w-16 rounded-full object-cover bg-[#FAFFE0]" />
                        <div class="text-[#FAFFE0]">
                            <div class="font-semibold text-base">{{ $instructor->full_name }}</div>
                            <div class="text-xs text-[#FAFFE0]/70">{{ $instructor->headline ?? 'Instructor' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
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

    // Three.js 3D Model
    const container = document.getElementById('hero-3d-container');
    if (container && typeof THREE !== 'undefined') {
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
        
        const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setClearColor(0x000000, 0);
        container.appendChild(renderer.domElement);

        // Lighting
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);
        
        const directionalLight = new THREE.DirectionalLight(0xC8CD06, 1);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);
        
        const directionalLight2 = new THREE.DirectionalLight(0x072923, 0.5);
        directionalLight2.position.set(-5, -5, -5);
        scene.add(directionalLight2);

        camera.position.z = 5;
        camera.position.y = 0.3;

        let model = null;
        let floatTime = 0;

        // Load the GLB model
        const loader = new THREE.GLTFLoader();
        loader.load('/3dmodels/graduation_hat.glb', function(gltf) {
            model = gltf.scene;
            model.scale.set(2, 2, 2);
            model.position.set(0, -0.5, 0);
            // Tilt the model to look at viewer
            model.rotation.x = 0.3;
            model.rotation.z = -0.1;
            scene.add(model);
            
            // Initial animation with GSAP
            if (typeof gsap !== 'undefined') {
                gsap.from(model.rotation, { duration: 2, y: Math.PI * 2, ease: 'power2.out' });
                gsap.from(model.scale, { duration: 1.5, x: 0, y: 0, z: 0, ease: 'elastic.out(1, 0.5)' });
            }
        }, undefined, function(error) {
            console.log('Error loading 3D model:', error);
            // Fallback: show a placeholder
            container.innerHTML = '<div class="w-full h-full flex items-center justify-center"><div class="h-48 w-48 bg-[#072923] rounded-full flex items-center justify-center text-6xl">🎓</div></div>';
        });

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            
            if (model) {
                // Rotate
                model.rotation.y += 0.005;
                
                // Float up and down
                floatTime += 0.02;
                model.position.y = -0.8 + Math.sin(floatTime) * 0.15;
            }
            
            renderer.render(scene, camera);
        }
        animate();

        // Handle resize
        window.addEventListener('resize', () => {
            if (container) {
                camera.aspect = container.clientWidth / container.clientHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(container.clientWidth, container.clientHeight);
            }
        });
    }
});
</script>
@endpush
@endsection

