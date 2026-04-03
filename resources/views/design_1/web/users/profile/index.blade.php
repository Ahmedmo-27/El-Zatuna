@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("profile") }}">
    <style>
        /* El Zatuna Theme Overrides for Profile Page */
        :root {
            --primary: #FAFFE0;
            --secondary: #072923;
        }
        
        body {
            background-color: #FAFFE0 !important;
            color: #072923 !important;
        }
        
        .profile-container h1, 
        .profile-container h2, 
        .profile-container h3, 
        .profile-container h4, 
        .profile-container h5, 
        .profile-container h6,
        .profile-container .font-weight-bold,
        .profile-container .text-dark {
            color: #072923 !important;
        }

        .profile-card-has-mask {
            border: 1px solid #ECF4B8;
        }

        .navbar-item.active {
            color: #C8CD06 !important;
            border-bottom-color: #C8CD06 !important;
        }

        .navbar-item.active .icons {
            color: #C8CD06 !important;
        }

        .btn-primary {
            background-color: #C8CD06 !important;
            border-color: #C8CD06 !important;
            color: #072923 !important;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #BDEA42 !important;
            border-color: #BDEA42 !important;
        }

        .profile-overview-metrics {
            border: 1px solid #ecf4b8;
            background: linear-gradient(135deg, #ffffff 0%, #f8fde5 100%);
        }

        .profile-overview-metric {
            min-height: 132px;
        }

        .profile-overview-metric__icon {
            width: 62px;
            height: 62px;
            border-radius: 18px;
            border: 1px solid #d9e7a3;
            background: #f0f7ca;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-overview-metric__icon .icons {
            color: #072923 !important;
            width: 34px !important;
            height: 34px !important;
        }

        .profile-overview-metric__label {
            color: #4b655f !important;
            font-weight: 700;
        }

        .profile-overview-metric__value {
            color: #072923 !important;
            font-size: 24px;
            line-height: 1.1;
        }

        .profile-about-me-description,
        .profile-about-me-description p,
        .profile-about-me-description li {
            color: #3b5b54 !important;
        }

        .profile-education-card {
            background: linear-gradient(180deg, #ffffff 0%, #f9fdeb 100%);
            border: 1px solid #e2edb6;
            border-radius: 16px;
            padding: 16px;
        }

        .profile-education-card:after {
            display: none;
        }

        .profile-education-card__icon {
            background: #f0f7ca;
            border: 1px solid #dae8a0;
            flex-shrink: 0;
            width: 74px;
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-education-card__icon .icons {
            color: #072923 !important;
            width: 36px !important;
            height: 36px !important;
        }

        .profile-education-card__value {
            color: #294943;
            font-size: 17px;
            font-weight: 600;
        }

        .profile-external-link-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .profile-external-link-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(7, 41, 35, .08);
        }

        .profile-external-link-card__title {
            color: #072923;
        }

        .profile-external-link-card__url {
            color: #49635e !important;
        }
    </style>
@endpush

@php
    $isInstructorProfile = $user->isTeacher();
@endphp


@section('content')
    <div class="profile-cover-card">
        <img src="{{ $user->getCover() }}" class="img-cover" alt=""/>
    </div>

    <div class="profile-container">
        <div class="container mb-104">
            <div class="row">

                <div class="col-12 col-md-4 col-lg-3">
                    @include('design_1.web.users.profile.includes.left_side')
                </div>

                <div class="col-12 col-md-8 col-lg-9 mt-32 mt-md-0">
                    <div class="profile-card-has-mask position-relative bg-white pt-24 pb-20 rounded-24">
                        <div class="custom-tabs">

                            <div class="profile-tabs-items d-flex align-items-center gap-16 gap-lg-32 border-bottom-gray-200 px-24">
                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#aboutTab">
                                    <x-iconsax-lin-profile class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('public.about') }}</span>
                                </div>

                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'webinars') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#coursesTab">
                                    <x-iconsax-lin-video-play class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('update.courses') }}</span>
                                </div>

                                @if(!$isInstructorProfile and $user->isOrganization())
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'instructors') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#instructorsTab">
                                        <x-iconsax-lin-teacher class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('home.instructors') }}</span>
                                    </div>
                                @endif

                                @if(!$isInstructorProfile and !empty(getStoreSettings('status')) and getStoreSettings('status'))
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'products') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#productsTab">
                                        <x-iconsax-lin-box class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('update.products') }}</span>
                                    </div>
                                @endif

                                {{-- Temporarily hidden until Articles is used again.
                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'posts') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#articlesTab">
                                    <x-iconsax-lin-note-2 class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('update.articles') }}</span>
                                </div>
                                --}}

                                @if(!$isInstructorProfile)
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'forum') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#forumTab">
                                        <x-iconsax-lin-messages class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('update.forum') }}</span>
                                    </div>
                                @endif

                                {{-- Temporarily hidden until Badges is used again.
                                <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'badges') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#badgesTab">
                                    <x-iconsax-lin-medal class="icons" width="16px" height="16px"/>
                                    <span class="ml-4">{{ trans('site.badges') }}</span>
                                </div>
                                --}}

                                @if(!$isInstructorProfile)
                                    <div class="navbar-item d-flex-center pb-12 cursor-pointer font-12 font-weight-bold {{ (request()->get('tab') == 'appointments') ? 'active' : ''  }}" data-tab-toggle data-tab-href="#reserveMeetingTab">
                                        <x-iconsax-lin-calendar-2 class="icons" width="16px" height="16px"/>
                                        <span class="ml-4">{{ trans('public.reserve_a_meeting') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="custom-tabs-body">

                                <div class="custom-tabs-content px-16  {{ (empty(request()->get('tab')) or request()->get('tab') == 'about') ? 'active' : ''  }}" id="aboutTab">
                                    @include('design_1.web.users.profile.tabs.about')
                                </div>

                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'webinars') ? 'active' : ''  }}" id="coursesTab">
                                    @include('design_1.web.users.profile.tabs.courses')
                                </div>

                                @if(!$isInstructorProfile and $user->isOrganization())
                                    <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'instructors') ? 'active' : ''  }}" id="instructorsTab">
                                        @include('design_1.web.users.profile.tabs.instructors')
                                    </div>
                                @endif

                                @if(!$isInstructorProfile)
                                    <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'products') ? 'active' : ''  }}" id="productsTab">
                                        @include('design_1.web.users.profile.tabs.products')
                                    </div>
                                @endif

                                {{-- Temporarily hidden until Articles is used again.
                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'posts') ? 'active' : ''  }}" id="articlesTab">
                                    @include('design_1.web.users.profile.tabs.articles')
                                </div>
                                --}}

                                @if(!$isInstructorProfile)
                                    <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'forum') ? 'active' : ''  }}" id="forumTab">
                                        @include('design_1.web.users.profile.tabs.forum')
                                    </div>
                                @endif

                                {{-- Temporarily hidden until Badges is used again.
                                <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'badges') ? 'active' : ''  }}" id="badgesTab">
                                    @include('design_1.web.users.profile.tabs.badges')
                                </div>
                                --}}

                                @if(!$isInstructorProfile)
                                    <div class="custom-tabs-content px-16 {{ (request()->get('tab') == 'appointments') ? 'active' : ''  }}" id="reserveMeetingTab">
                                        @include('design_1.web.users.profile.tabs.reserveMeeting.index')
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script>
        var unFollowLang = '{{ trans('panel.unfollow') }}';
        var followLang = '{{ trans('panel.follow') }}';
        var sendMessageLang = '{{ trans('site.send_message') }}';
        var reservedLang = '{{ trans('meeting.reserved') }}';
        var messageSuccessSentLang = '{{ trans('site.message_success_sent') }}';
    </script>


    <script src="{{ getDesign1ScriptPath("profile") }}"></script>

    @if(!empty($user->live_chat_js_code) and !empty(getFeaturesSettings('show_live_chat_widget')))
        <script>
            (function () {
                "use strict"

                {!! $user->live_chat_js_code !!}
            })(jQuery)
        </script>
    @endif
@endpush
