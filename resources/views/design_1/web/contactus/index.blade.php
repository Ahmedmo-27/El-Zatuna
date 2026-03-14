@extends('design_1.web.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="{{ getDesign1StylePath("contactus") }}">

    <style>
        .contact-page-shell {
            border: 1px solid rgba(250, 255, 224, 0.14);
            background: linear-gradient(145deg, #072923 0%, #0d3a31 100%);
            border-radius: 32px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.28);
        }

        .contact-page-title,
        .contact-page-subtitle,
        .contact-page-help {
            color: #FAFFE0 !important;
        }

        .contact-page-help {
            opacity: 0.85;
        }

        .contact-page-info {
            border-right: 1px solid rgba(250, 255, 224, 0.14);
            padding-right: 8px;
        }

        .contact-page-info-inner {
            background: rgba(250, 255, 224, 0.05);
            border: 1px solid rgba(250, 255, 224, 0.14);
            border-radius: 18px;
            padding: 20px;
        }

        .contact-info-kicker {
            color: rgba(250, 255, 224, 0.75);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .contact-info-heading {
            color: #FAFFE0;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .contact-info-item + .contact-info-item {
            margin-top: 20px;
        }

        .contact-info-icon-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #C8CD06;
            flex-shrink: 0;
        }

        .contact-info-icon {
            color: #072923 !important;
        }

        .contact-info-label,
        .contact-info-value,
        .contact-info-value a {
            color: #FAFFE0 !important;
        }

        .contact-page-form .form-group {
            margin-bottom: 16px;
        }

        .contact-page-form .form-group-label {
            position: static !important;
            display: block;
            width: auto;
            padding: 0;
            margin-bottom: 8px;
            border: 0;
            background: transparent !important;
            color: #FAFFE0 !important;
            font-weight: 600;
            line-height: 1.4;
            box-shadow: none;
        }

        .contact-page-form .contact-page-control {
            min-height: 50px;
            background: rgba(7, 41, 35, 0.55) !important;
            color: #FAFFE0 !important;
            border: 1px solid rgba(250, 255, 224, 0.28) !important;
            border-radius: 10px !important;
            padding: 12px 14px !important;
        }

        .contact-page-form .contact-page-control::placeholder {
            color: rgba(250, 255, 224, 0.65);
        }

        .contact-page-form .contact-page-control:focus {
            border-color: #C8CD06 !important;
            box-shadow: 0 0 0 0.15rem rgba(200, 205, 6, 0.25) !important;
            background: rgba(7, 41, 35, 0.8) !important;
        }

        .contact-page-form .js-ajax-captcha {
            background: rgba(7, 41, 35, 0.55) !important;
            border: 1px solid rgba(250, 255, 224, 0.28) !important;
            color: #FAFFE0 !important;
            border-radius: 10px !important;
        }

        .contact-page-form #refreshCaptcha {
            background: rgba(250, 255, 224, 0.08) !important;
        }

        .contact-page-form .captcha-image-card {
            background: rgba(250, 255, 224, 0.12) !important;
        }

        .contact-page-form .invalid-feedback {
            color: #ffd0d0;
        }

        .contact-page-form .captcha-image {
            max-height: 34px;
        }

        .contact-page-form .text-gray-500 {
            color: #FAFFE0 !important;
            opacity: 0.85;
        }

        .contact-page-send-btn {
            background-color: #C8CD06 !important;
            color: #072923 !important;
            font-weight: 700;
            border-radius: 999px;
            padding: 14px 28px;
        }

        @media (max-width: 991px) {
            .contact-page-shell {
                border-radius: 22px;
                padding: 22px !important;
            }

            .contact-page-info {
                border-right: 0;
                border-bottom: 1px solid rgba(250, 255, 224, 0.14);
                padding-right: 0;
                padding-bottom: 18px;
                margin-bottom: 10px;
            }

            .contact-page-info-inner {
                padding: 16px;
            }
        }
    </style>
@endpush


@section('content')
    <div class="w-full max-w-[1320px] mx-auto px-12 md:px-24 lg:px-32 xl:px-44 mt-48 pb-28">
        <div class="contact-page-shell p-10 md:p-16 lg:p-20">
            <div class="row">
                <div class="col-12 col-lg-4 contact-page-info mb-20 mb-lg-0">
                    @include('design_1.web.contactus.our_info')
                </div>

                <div class="col-12 col-lg-8">
                    @include('design_1.web.contactus.form')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof gsap !== 'undefined') {
            gsap.from('.contact-page-shell', {
                y: 40,
                opacity: 0,
                duration: 0.8,
                ease: 'power3.out'
            });

            gsap.from('.contact-page-info', {
                x: -50,
                opacity: 0,
                duration: 0.8,
                delay: 0.2,
                ease: 'power3.out'
            });

            gsap.from('.contact-page-form', {
                y: 40,
                opacity: 0,
                duration: 0.8,
                delay: 0.3,
                ease: 'power3.out'
            });

            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach((input, i) => {
                gsap.from(input, {
                    y: 20,
                    opacity: 0,
                    duration: 0.5,
                    delay: 0.45 + (i * 0.07),
                    ease: 'power3.out'
                });
            });

            gsap.from('button[type="submit"]', {
                scale: 0.9,
                opacity: 0,
                duration: 0.6,
                delay: 0.8,
                ease: 'power3.out'
            });
        }
    });
    </script>
@endpush
