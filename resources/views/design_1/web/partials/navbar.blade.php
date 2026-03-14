@php
    $isHomePage = request()->getPathInfo() === '/';
    $isClassesPage = request()->is('classes') || request()->is('classes/*');
    $isInstructorsPage = request()->is('instructors') || request()->is('instructors/*');
    $isAboutPage = request()->is('about');
    $isContactPage = request()->is('contact');

    $navLinkClass = 'transition-colors duration-200 flex items-center gap-3';
    $navActiveClass = 'text-[#C8CD06]';
    $navInactiveClass = 'text-[#072923] hover:text-[#C8CD06]';
@endphp

<nav class="bg-[#F5F9E8] border-b border-[#ECF4B8] sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex items-center justify-between gap-6">
            <a href="/" class="flex items-center gap-3 -ml-2 md:-ml-4">
                <img src="/assets/design_1/img/logozatuna.png" alt="Elzatuna" class="h-12 md:h-14 lg:h-16 w-auto object-contain" />
            </a>

            <div class="hidden lg:flex items-center gap-24 text-lg font-semibold">
                <a href="/" class="{{ $navLinkClass }} {{ $isHomePage ? $navActiveClass : $navInactiveClass }}" aria-current="{{ $isHomePage ? 'page' : 'false' }}">
                    <x-iconsax-lin-home-2 class="w-5 h-5"/> Home
                </a>
                @auth
                    <a href="/classes" class="{{ $navLinkClass }} {{ $isClassesPage ? $navActiveClass : $navInactiveClass }}" aria-current="{{ $isClassesPage ? 'page' : 'false' }}">
                        <x-iconsax-lin-book class="w-5 h-5"/> Subjects/Courses
                    </a>
                @endauth
                <a href="/instructors" class="{{ $navLinkClass }} {{ $isInstructorsPage ? $navActiveClass : $navInactiveClass }}" aria-current="{{ $isInstructorsPage ? 'page' : 'false' }}">
                    <x-iconsax-bol-teacher class="w-5 h-5"/> Instructors
                </a>
                <a href="/about" class="{{ $navLinkClass }} {{ $isAboutPage ? $navActiveClass : $navInactiveClass }}" aria-current="{{ $isAboutPage ? 'page' : 'false' }}">
                    <x-iconsax-lin-info-circle class="w-5 h-5"/> About
                </a>
                <a href="/contact" class="{{ $navLinkClass }} {{ $isContactPage ? $navActiveClass : $navInactiveClass }}" aria-current="{{ $isContactPage ? 'page' : 'false' }}">
                    <x-iconsax-lin-sms class="w-5 h-5"/> Contact
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-6">
                <div class="js-view-cart-drawer relative flex items-center justify-center h-10 w-10 rounded-full border border-[#ECF4B8] text-[#072923] hover:text-[#C8CD06] transition-colors">
                    <x-iconsax-lin-bag class="w-5 h-5"/>
                    <span class="js-cart-counter absolute -top-1 -right-1 h-5 min-w-[20px] px-1 rounded-full bg-[#C8CD06] text-[#072923] text-xs font-semibold flex items-center justify-center {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
                </div>
                @if(auth()->check())
                    <a href="/panel" class="text-lg font-semibold bg-[#C8CD06] text-[#072923] px-5 py-2 rounded-full hover:bg-[#BDEA42] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-element-3 class="w-5 h-5"/> Dashboard
                    </a>
                    <a href="/logout" class="text-lg font-semibold text-[#072923] border border-[#ECF4B8] px-5 py-2 rounded-full hover:text-[#C8CD06] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-logout class="w-5 h-5"/> Logout
                    </a>
                @else
                    <a href="/login" class="text-lg font-semibold text-[#072923] border border-[#ECF4B8] px-5 py-2 rounded-full hover:text-[#C8CD06] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-login class="w-5 h-5"/> Login
                    </a>
                    <a href="/register" class="text-lg font-semibold bg-[#C8CD06] text-[#072923] px-5 py-2 rounded-full hover:bg-[#BDEA42] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-user-add class="w-5 h-5"/> Register
                    </a>
                @endif
            </div>

            <button id="mobileMenuButton" class="lg:hidden h-10 w-10 rounded-full border border-[#ECF4B8] text-[#072923] flex items-center justify-center p-2" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenuPanel" aria-haspopup="dialog">
                <x-iconsax-lin-menu-1 class="w-6 h-6"/>
            </button>
        </div>
    </div>
</nav>

<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 hidden opacity-0 transition-opacity duration-300 z-50" aria-hidden="true"></div>
<aside id="mobileMenuPanel" class="fixed top-0 right-0 h-full w-[85vw] max-w-[320px] bg-[#F5F9E8] translate-x-full transition-transform duration-300 z-50" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Mobile navigation" tabindex="-1">
    <div class="p-6">
        <div class="flex items-center justify-between mb-5">
            <span class="text-lg font-semibold text-[#072923]">Menu</span>
            <button id="mobileMenuClose" class="h-9 w-9 rounded-full border border-[#ECF4B8] text-[#072923] flex items-center justify-center" aria-label="Close menu">
                <x-iconsax-lin-close-circle class="w-5 h-5"/>
            </button>
        </div>
        <nav class="flex flex-col gap-2 text-base font-semibold text-[#072923]">
            <a href="/" class="{{ $isHomePage ? 'text-[#C8CD06] bg-[#ECF4B8]/60' : 'hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60' }} transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl" aria-current="{{ $isHomePage ? 'page' : 'false' }}">
                <x-iconsax-lin-home-2 class="w-5 h-5"/> Home
            </a>
            @auth
                <a href="/classes" class="{{ $isClassesPage ? 'text-[#C8CD06] bg-[#ECF4B8]/60' : 'hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60' }} transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl" aria-current="{{ $isClassesPage ? 'page' : 'false' }}">
                    <x-iconsax-lin-book class="w-5 h-5"/> Subjects/Courses
                </a>
            @endauth
            <a href="/instructors" class="{{ $isInstructorsPage ? 'text-[#C8CD06] bg-[#ECF4B8]/60' : 'hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60' }} transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl" aria-current="{{ $isInstructorsPage ? 'page' : 'false' }}">
                <x-iconsax-bol-teacher class="w-5 h-5"/> Instructors
            </a>
            <a href="/about" class="{{ $isAboutPage ? 'text-[#C8CD06] bg-[#ECF4B8]/60' : 'hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60' }} transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl" aria-current="{{ $isAboutPage ? 'page' : 'false' }}">
                <x-iconsax-lin-info-circle class="w-5 h-5"/> About
            </a>
            <a href="/contact" class="{{ $isContactPage ? 'text-[#C8CD06] bg-[#ECF4B8]/60' : 'hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60' }} transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl" aria-current="{{ $isContactPage ? 'page' : 'false' }}">
                <x-iconsax-lin-sms class="w-5 h-5"/> Contact
            </a>
            <button type="button" class="js-view-cart-drawer hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-bag class="w-5 h-5"/> Cart
                <span class="js-cart-counter ml-auto h-5 min-w-[20px] px-1 rounded-full bg-[#C8CD06] text-[#072923] text-xs font-semibold flex items-center justify-center {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
            </button>
        </nav>

        <div class="mt-5 border-t border-[#ECF4B8] pt-4">
            @if(auth()->check())
                <a href="/panel" class="inline-flex text-base font-semibold bg-[#C8CD06] text-[#072923] px-4 py-2 rounded-full mb-3 flex items-center gap-3">
                    <x-iconsax-lin-element-3 class="w-5 h-5"/> Dashboard
                </a>
                <a href="/logout" class="block text-base font-semibold flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-[#ECF4B8]/60 transition-colors duration-200">
                    <x-iconsax-lin-logout class="w-5 h-5"/> Logout
                </a>
            @else
                <a href="/login" class="block text-base font-semibold mb-3 flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-[#ECF4B8]/60 transition-colors duration-200">
                    <x-iconsax-lin-login class="w-5 h-5"/> Login
                </a>
                <a href="/register" class="inline-flex text-base font-semibold bg-[#C8CD06] text-[#072923] px-4 py-2 rounded-full flex items-center gap-3">
                    <x-iconsax-lin-user-add class="w-5 h-5"/> Register
                </a>
            @endif
        </div>
    </div>
</aside>
