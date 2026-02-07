@extends('design_1.web.layouts.app')

@section('content')
    <main class="bg-[#FAFFE0] text-[#072923] min-h-screen">
        
        <!-- Hero Section -->
        <section class="max-w-[1700px] mx-auto px-12 md:px-24 lg:px-32 xl:px-44 pt-32 pb-24">
            <div class="text-center animate-[fadeIn_0.8s_ease-in-out] pl-4 md:pl-10">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">About <span class="text-[#C8CD06]">El Zatuna</span></h1>
                <p class="text-lg md:text-xl text-[#072923]/70 max-w-3xl mx-auto">
                    Your trusted platform for quality education and skill development
                </p>
                <div class="mt-10 flex justify-center">
                    <img src="/assets/design_1/img/no-result/instructors.svg" alt="Instructors" class="w-72 md:w-96 lg:w-[520px] opacity-90" />
                </div>
            </div>
        </section>

        <!-- Mission & Vision -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <div class="grid md:grid-cols-2 gap-10">
                <div class="bg-[#072923] rounded-[32px] p-8 md:p-12 text-[#FAFFE0] hover:scale-105 transition-transform duration-300">
                    <div class="text-5xl mb-6"><x-iconsax-lin-clipboard-tick class="w-12 h-12 text-[#C8CD06]"/></div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Our Mission</h2>
                    <p class="text-[#FAFFE0]/80">
                        To provide accessible, high-quality education that empowers learners worldwide to achieve their goals 
                        and unlock their full potential through innovative online learning experiences.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[32px] p-8 md:p-12 text-[#FAFFE0] hover:scale-105 transition-transform duration-300">
                    <div class="text-5xl mb-6"><x-iconsax-lin-eye class="w-12 h-12 text-[#C8CD06]"/></div>
                    <h2 class="text-2xl md:text-3xl font-bold mb-4">Our Vision</h2>
                    <p class="text-[#FAFFE0]/80">
                        To become the leading educational platform that bridges the gap between knowledge and opportunity, 
                        creating a global community of lifelong learners and expert instructors.
                    </p>
                </div>
            </div>
        </section>

        <!-- Platform Stats -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Growing <span class="text-[#C8CD06]">Together</span></h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-[#BDEA42] rounded-[24px] p-6 text-center hover:scale-110 transition-transform duration-300">
                    <div class="text-4xl mb-3"><x-iconsax-lin-profile-2user class="w-10 h-10 text-[#072923] mx-auto"/></div>
                    <div class="text-3xl font-bold text-[#072923]">2,500+</div>
                    <div class="text-sm text-[#072923]/70">Active Students</div>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 text-center hover:scale-110 transition-transform duration-300">
                    <div class="text-4xl mb-3"><x-iconsax-lin-teacher class="w-10 h-10 text-[#072923] mx-auto"/></div>
                    <div class="text-3xl font-bold text-[#072923]">257</div>
                    <div class="text-sm text-[#072923]/70">Expert Instructors</div>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 text-center hover:scale-110 transition-transform duration-300">
                    <div class="text-4xl mb-3"><x-iconsax-lin-book class="w-10 h-10 text-[#072923] mx-auto"/></div>
                    <div class="text-3xl font-bold text-[#072923]">29</div>
                    <div class="text-sm text-[#072923]/70">Professional Courses</div>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 text-center hover:scale-110 transition-transform duration-300">
                    <div class="text-4xl mb-3"><x-iconsax-lin-building-3 class="w-10 h-10 text-[#072923] mx-auto"/></div>
                    <div class="text-3xl font-bold text-[#072923]">6</div>
                    <div class="text-sm text-[#072923]/70">Partner Organizations</div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Why Choose <span class="text-[#C8CD06]">El Zatuna</span>?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-star class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Quality Content</h3>
                    <p class="text-[#FAFFE0]/70">
                        Expert-curated courses designed to deliver practical knowledge and real-world skills.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-clock-1 class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Flexible Learning</h3>
                    <p class="text-[#FAFFE0]/70">
                        Learn at your own pace, on your own schedule, from anywhere in the world.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-medal class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Certified Success</h3>
                    <p class="text-[#FAFFE0]/70">
                        Earn recognized certificates that showcase your achievements and expertise.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-note-2 class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Interactive Learning</h3>
                    <p class="text-[#FAFFE0]/70">
                        Engage with course materials through quizzes, assignments, and hands-on projects.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-messages class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Community Support</h3>
                    <p class="text-[#FAFFE0]/70">
                        Join a thriving community of learners and connect with peers and mentors.
                    </p>
                </div>
                <div class="bg-[#072923] rounded-[24px] p-8 text-[#FAFFE0] hover:translate-y-[-8px] transition-transform duration-300">
                    <div class="h-14 w-14 bg-[#C8CD06] rounded-full flex items-center justify-center mb-4"><x-iconsax-lin-moneys class="w-7 h-7 text-[#072923]"/></div>
                    <h3 class="text-xl font-bold mb-3">Affordable Pricing</h3>
                    <p class="text-[#FAFFE0]/70">
                        Access world-class education at prices that won't break the bank.
                    </p>
                </div>
            </div>
        </section>

        <!-- Our Story -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <div class="bg-[#072923] rounded-[32px] p-8 md:p-16">
                <h2 class="text-3xl md:text-4xl font-bold text-[#FAFFE0] mb-8 text-center">Our <span class="text-[#C8CD06]">Story</span></h2>
                <div class="text-[#FAFFE0]/80 space-y-4 max-w-4xl mx-auto">
                    <p>
                        El Zatuna was founded with a simple yet powerful vision: to make quality education accessible to everyone, 
                        everywhere. We believe that knowledge should not be limited by geographical boundaries, financial constraints, 
                        or traditional educational barriers.
                    </p>
                    <p>
                        Our platform brings together expert instructors from around the world with ambitious learners eager to 
                        develop new skills and advance their careers. Through innovative technology and a commitment to educational 
                        excellence, we've created a learning ecosystem that adapts to each student's unique needs.
                    </p>
                    <p>
                        Today, we're proud to serve thousands of students across multiple countries, offering courses in diverse 
                        subjects ranging from technology and business to arts and personal development. Every course on our platform 
                        is carefully designed to deliver practical, actionable knowledge that our students can immediately apply to 
                        their professional and personal lives.
                    </p>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Our Core <span class="text-[#C8CD06]">Values</span></h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-[#BDEA42] rounded-[24px] p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl mb-3"><x-iconsax-lin-search-normal class="w-8 h-8 text-[#072923]"/></div>
                    <h3 class="text-lg font-bold text-[#072923] mb-2">Excellence</h3>
                    <p class="text-sm text-[#072923]/70">Striving for the highest quality in everything we do</p>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl mb-3"><x-iconsax-lin-status-up class="w-8 h-8 text-[#072923]"/></div>
                    <h3 class="text-lg font-bold text-[#072923] mb-2">Innovation</h3>
                    <p class="text-sm text-[#072923]/70">Continuously improving and embracing new technologies</p>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl mb-3"><x-iconsax-lin-global class="w-8 h-8 text-[#072923]"/></div>
                    <h3 class="text-lg font-bold text-[#072923] mb-2">Accessibility</h3>
                    <p class="text-sm text-[#072923]/70">Making education available to learners everywhere</p>
                </div>
                <div class="bg-[#BDEA42] rounded-[24px] p-6 hover:scale-105 transition-transform duration-300">
                    <div class="text-3xl mb-3"><x-iconsax-lin-heart class="w-8 h-8 text-[#072923]"/></div>
                    <h3 class="text-lg font-bold text-[#072923] mb-2">Integrity</h3>
                    <p class="text-sm text-[#072923]/70">Building trust through transparency and honesty</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="max-w-[1600px] mx-auto px-6 md:px-8 py-16">
            <div class="bg-[#C8CD06] rounded-[32px] p-12 text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-[#072923] mb-4">Ready to Start Learning?</h2>
                <p class="text-lg text-[#072923]/70 mb-8 max-w-2xl mx-auto">
                    Join thousands of students already learning on El Zatuna. Browse our courses and start your journey today!
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="/classes" class="bg-[#072923] text-[#FAFFE0] font-semibold px-8 py-4 rounded-full text-lg hover:scale-105 transition-transform duration-200 inline-flex items-center gap-3">
                        <x-iconsax-lin-book class="w-5 h-5"/> Browse Courses
                    </a>
                    <a href="/register" class="bg-[#FAFFE0] text-[#072923] font-semibold px-8 py-4 rounded-full text-lg hover:scale-105 transition-transform duration-200 border-2 border-[#072923] inline-flex items-center gap-3">
                        <x-iconsax-lin-user-add class="w-5 h-5"/> Sign Up Free
                    </a>
                </div>
            </div>
        </section>

    </main>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
