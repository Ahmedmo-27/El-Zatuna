<div class="admin-header d-flex align-items-start justify-content-between bg-primary pt-16 px-24 px-lg-28">
    <div class="d-flex cursor-pointer" data-toggle="sidebar">
        <x-iconsax-lin-menu class="icons text-dark" width="24px" height="24px"/>
    </div>

    <div class="d-flex align-items-center justify-content-end flex-1 gap-16">


                    <style>
            .about-system-select:hover .about-system-dropdown {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }

            .about-system-dropdown:hover {
                opacity: 1 !important;
                visibility: visible !important;
                transform: translateY(0) !important;
            }

            .about-system-dropdown {
                direction: ltr !important;
                transform-origin: top right;
            }

            .about-system-dropdown .badge:hover {
                transform: translateY(-1px);
                transition: all 0.3s ease;
            }

            .about-system-dropdown .badge {
                transition: all 0.3s ease;
            }

            @media (max-width: 768px) {
                .about-system-dropdown {
                    min-width: 280px !important;
                    max-width: 320px !important;
                    left: auto !important;
                    right: 0 !important;
                    transform: translateY(15px) !important;
                }

                .about-system-select:hover .about-system-dropdown {
                    transform: translateY(0) !important;
                }
            }

            @media (max-width: 480px) {
                .about-system-dropdown {
                    min-width: 260px !important;
                    max-width: 280px !important;
                    right: -20px !important;
                }
            }
        </style>




        {{-- Ai --}}
        @if(!empty(getAiContentsSettingsName('status')) && !empty(getAiContentsSettingsName('active_for_admin_panel')))
            <div class="js-show-ai-content-drawer d-flex-center size-32 rounded-8 cursor-pointer" style="background-color: rgba(255, 255, 255, 0.2);">
                <x-iconsax-lin-cpu-charge class="icons text-white" width="20px" height="20px"/>
            </div>
        @endif

        {{-- Curreny --}}
        @include('admin.includes.header.currency')

        {{-- language --}}
        @include('admin.includes.header.language')

        {{-- Notification --}}
        @include('admin.includes.header.notification')

        <div class="admin-header__item-divider"></div>

        {{-- User --}}
        @include('admin.includes.header.auth_user_info')
    </div>
</div>
