<nav class="bg-[#F5F9E8] border-b border-[#ECF4B8] sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex items-center justify-between gap-6">
            <a href="/" class="flex items-center gap-3 -ml-2 md:-ml-4">
                <img src="/assets/design_1/img/logozatuna.png" alt="Elzatuna" class="h-12 md:h-14 lg:h-16 w-auto object-contain" />
            </a>

            <div class="hidden lg:flex items-center gap-24 text-lg font-semibold text-[#072923]">
                <a href="/" class="hover:text-[#C8CD06] transition-colors duration-200 flex items-center gap-3">
                    <x-iconsax-lin-home-2 class="w-5 h-5"/> Home
                </a>
                <a href="/classes" class="hover:text-[#C8CD06] transition-colors duration-200 flex items-center gap-3">
                    <x-iconsax-lin-book class="w-5 h-5"/> Subjects/Courses
                </a>
                <a href="/instructors" class="hover:text-[#C8CD06] transition-colors duration-200 flex items-center gap-3">
                    <x-iconsax-lin-briefcase class="w-5 h-5"/> Instructors
                </a>
                <a href="/about" class="hover:text-[#C8CD06] transition-colors duration-200 flex items-center gap-3">
                    <x-iconsax-lin-info-circle class="w-5 h-5"/> About
                </a>
                <a href="/contact" class="hover:text-[#C8CD06] transition-colors duration-200 flex items-center gap-3">
                    <x-iconsax-lin-sms class="w-5 h-5"/> Contact
                </a>
            </div>

            <div class="hidden lg:flex items-center gap-6">
                <div class="js-view-cart-drawer relative flex items-center justify-center h-10 w-10 rounded-full border border-[#ECF4B8] text-[#072923] hover:text-[#C8CD06] transition-colors">
                    <x-iconsax-lin-bag class="w-5 h-5"/>
                    <span class="js-cart-counter absolute -top-1 -right-1 h-5 min-w-[20px] px-1 rounded-full bg-[#C8CD06] text-[#072923] text-xs font-semibold flex items-center justify-center {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
                </div>
                @if(auth()->check())
                    <a href="/panel" class="text-lg font-semibold flex items-center gap-3">
                        <x-iconsax-lin-element-3 class="w-5 h-5"/> Dashboard
                    </a>
                    <a href="/logout" class="text-lg font-semibold bg-[#C8CD06] text-[#072923] px-5 py-2 rounded-full hover:bg-[#BDEA42] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-logout class="w-5 h-5"/> Logout
                    </a>
                @else
                    <a href="/login" class="text-lg font-semibold flex items-center gap-3">
                        <x-iconsax-lin-login class="w-5 h-5"/> Login
                    </a>
                    <a href="/register" class="text-lg font-semibold bg-[#C8CD06] text-[#072923] px-5 py-2 rounded-full hover:bg-[#BDEA42] transition-colors flex items-center gap-3">
                        <x-iconsax-lin-user-add class="w-5 h-5"/> Register
                    </a>
                @endif
            </div>

            <button id="mobileMenuButton" class="lg:hidden h-10 w-10 rounded-full border border-[#ECF4B8] text-[#072923] flex items-center justify-center p-2" aria-label="Open menu">
                <x-iconsax-lin-menu-1 class="w-6 h-6"/>
            </button>
        </div>
    </div>
</nav>

<div id="mobileMenuOverlay" class="fixed inset-0 bg-black/40 hidden opacity-0 transition-opacity duration-300 z-50"></div>
<aside id="mobileMenuPanel" class="fixed top-0 right-0 h-full w-[85vw] max-w-[320px] bg-[#F5F9E8] translate-x-full transition-transform duration-300 z-50">
    <div class="p-6">
        <div class="flex items-center justify-between mb-5">
            <span class="text-lg font-semibold text-[#072923]">Menu</span>
            <button id="mobileMenuClose" class="h-9 w-9 rounded-full border border-[#ECF4B8] text-[#072923] flex items-center justify-center" aria-label="Close menu">
                <x-iconsax-lin-close-circle class="w-5 h-5"/>
            </button>
        </div>
        <nav class="flex flex-col gap-2 text-base font-semibold text-[#072923]">
            <a href="/" class="hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-home-2 class="w-5 h-5"/> Home
            </a>
            <a href="/classes" class="hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-book class="w-5 h-5"/> Subjects/Courses
            </a>
            <a href="/instructors" class="hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-briefcase class="w-5 h-5"/> Instructors
            </a>
            <a href="/about" class="hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-info-circle class="w-5 h-5"/> About
            </a>
            <a href="/contact" class="hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-sms class="w-5 h-5"/> Contact
            </a>
            <button type="button" class="js-view-cart-drawer hover:text-[#C8CD06] hover:bg-[#ECF4B8]/60 transition-colors duration-200 flex items-center gap-3 px-4 py-2.5 rounded-xl">
                <x-iconsax-lin-bag class="w-5 h-5"/> Cart
                <span class="js-cart-counter ml-auto h-5 min-w-[20px] px-1 rounded-full bg-[#C8CD06] text-[#072923] text-xs font-semibold flex items-center justify-center {{ ($userCartCount < 1) ? 'd-none' : '' }}">{{ $userCartCount }}</span>
            </button>
        </nav>

        <div class="mt-5 border-t border-[#ECF4B8] pt-4">
            @if(auth()->check())
                <a href="/panel" class="block text-base font-semibold mb-3 flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-[#ECF4B8]/60 transition-colors duration-200">
                    <x-iconsax-lin-element-3 class="w-5 h-5"/> Dashboard
                </a>
                <a href="/logout" class="inline-flex text-base font-semibold bg-[#C8CD06] text-[#072923] px-4 py-2 rounded-full flex items-center gap-3">
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

<script>
    (function () {
        const openBtn = document.getElementById('mobileMenuButton');
        const closeBtn = document.getElementById('mobileMenuClose');
        const overlay = document.getElementById('mobileMenuOverlay');
        const panel = document.getElementById('mobileMenuPanel');

        if (!openBtn || !closeBtn || !overlay || !panel) return;

        const openMenu = () => {
            overlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
                panel.classList.remove('translate-x-full');
            });
        };

        const closeMenu = () => {
            overlay.classList.add('opacity-0');
            overlay.classList.remove('opacity-100');
            panel.classList.add('translate-x-full');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        };

        openBtn.addEventListener('click', openMenu);
        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    })();
</script>
