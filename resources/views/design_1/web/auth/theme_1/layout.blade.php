@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("auth/theme_1") }}">

    <style>
        /* El Zatuna Theme Overrides for Auth Pages */
        :root {
            --primary: #C8CD06;
            --secondary: #072923;
        }

        body {
            background-color: #FAFFE0 !important;
            color: #072923 !important;
        }

        /* Headings and Text */
        h1, h2, h3, h4, h5, h6,
        .font-weight-bold,
        .text-dark,
        .text-gray-500,
        label,
        .form-group-label {
            color: #072923 !important;
        }

        /* Helpers */
        .text-gray-500 {
            opacity: 0.8;
        }

        /* Buttons */
        .btn-primary {
            background-color: #C8CD06 !important;
            border-color: #C8CD06 !important;
            color: #072923 !important;
            font-weight: bold;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #BDEA42 !important;
            border-color: #BDEA42 !important;
            color: #072923 !important;
        }

        /* Forms */
        .form-control {
            background-color: #FFFFFF !important;
            border-color: #ECF4B8 !important;
            color: #072923 !important;
        }

        .form-control:focus {
            border-color: #C8CD06 !important;
            box-shadow: 0 0 0 0.2rem rgba(200, 205, 6, 0.25) !important;
        }

        /* Cards and Containers */
        .bg-white {
            background-color: #FFFFFF !important;
            border: 1px solid #ECF4B8 !important;
        }

        .auth-page-card__mask {
            background-color: rgba(200, 205, 6, 0.1) !important;
        }

        /* Links */
        a {
            color: #072923;
        }

        a:hover {
            color: #C8CD06;
        }
        
        /* Mobile Responsiveness Fixes */
        @media (max-width: 991px) {
            .auth-page-card .bg-white {
                border-radius: 24px !important;
                padding: 24px !important;
            }
            
            .auth-page-form-container {
                padding-right: 0 !important;
            }
            
            .pl-16 {
                padding-left: 0 !important;
            }
        }

        /* Fix Password Eye Icon Position */
        .password-input-visibility {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            z-index: 10;
        }
        
        /* Ensure form group is relative for positioning */
        .form-group {
            position: relative;
        }
    </style>
@endpush

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="auth-page-card position-relative">
                    <div class="auth-page-card__mask"></div>

                    <div class="position-relative bg-white rounded-32 p-16 z-index-2">
                        <div class="row">
                            <div class="col-12 col-lg-6">

                                @yield("page_content")

                            </div>

                            <div class="col-12 col-lg-6 d-none d-lg-block">
                                @include('design_1.web.auth.theme_1.includes.slider')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="{{ getDesign1ScriptPath("auth_theme_1") }}"></script>
@endpush
