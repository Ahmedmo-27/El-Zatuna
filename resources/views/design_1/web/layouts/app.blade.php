<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];
    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
    $themeCustomCssAndJs = getThemeCustomCssAndJs();
@endphp

<head>
    @include('design_1.web.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/design_1/css/app.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/loaders/GLTFLoader.js"></script>

    @if($isRtl)
        <link rel="stylesheet" href="/assets/design_1/css/rtl-app.min.css">
    @endif

    @if(!empty($themeHeaderData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/headers/{$themeHeaderData['component_name']}") }}">
    @endif

    @if(!empty($themeFooterData['component_name']))
        <link rel="stylesheet" href="{{ getDesign1StylePath("theme/footers/{$themeFooterData['component_name']}") }}">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty($themeCustomCssAndJs['css']) ? $themeCustomCssAndJs['css'] : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}

        :root {
            --font-sans: 'Poppins', 'Inter', 'Segoe UI', 'Arial', sans-serif;
            --font-display: 'Playfair Display', 'Georgia', serif;
        }

        body, button, input, textarea, select {
            font-family: var(--font-sans);
        }

        h1, h2, h3, h4, h5, h6, .font-display {
            font-family: var(--font-display);
        }

        .home-page {
            font-size: 20px;
        }

        @media (min-width: 1024px) {
            .home-page {
                font-size: 21px;
            }
        }

        .home-page .text-xs {
            font-size: 1.05rem;
        }

        .home-page .text-sm {
            font-size: 1.2rem;
        }

        .home-page .text-base {
            font-size: 1.35rem;
        }

        .home-page .text-lg {
            font-size: 1.6rem;
        }

        .marquee-divider {
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(7, 41, 35, 0.15);
            border-bottom: 1px solid rgba(7, 41, 35, 0.15);
            background: rgba(200, 205, 6, 0.12);
        }

        .marquee-divider__track {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            gap: 2rem;
            padding: 1rem 0;
            animation: marquee-scroll 18s linear infinite;
        }

        .marquee-divider__text {
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(7, 41, 35, 0.75);
        }

        @keyframes marquee-scroll {
            0% {
                transform: translateX(100%);
            }
            100% {
                transform: translateX(-100%);
            }
        }
    </style>

</head>

<body class="bg-[#FAFFE0] text-[#072923] {{ $isRtl ? 'rtl' : '' }} {{ "{$userThemeColorMode}-mode" }}">

<div id="app">

    @if(!empty($floatingBar) and $floatingBar->position == 'top')
        @include('design_1.web.includes.floating_bar')
    @endif

    @include('design_1.web.partials.navbar')

    {{-- Content --}}
    @yield('content')

    @include('design_1.web.partials.footer')

    @include('design_1.web.includes.advertise_modal.index')

    @if(!empty($floatingBar) and $floatingBar->position == 'bottom')
        @include('design_1.web.includes.floating_bar')
    @endif

    {{-- Cart Drawer --}}
    @include('design_1.web.cart.drawer.index')

</div>

<!-- Template JS File -->
<script>
    var siteDomain = '{{ url('') }}';
    var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
    var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
    var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
    var deleteAlertCancel = '{{ trans('public.cancel') }}';
    var deleteAlertSuccess = '{{ trans('public.success') }}';
    var deleteAlertFail = '{{ trans('public.fail') }}';
    var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
    var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
    var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
    var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
    var priceInvalidHintLang = '{{ trans('update.price_invalid_hint') }}';
    var clearLang = '{{ trans('clear') }}';
    var requestSuccessLang = '{{ trans('public.request_success') }}';
    var saveSuccessLang = '{{ trans('webinars.success_store') }}';
    var requestFailedLang = '{{ trans('public.request_failed') }}';
    var oopsLang = '{{ trans('update.oops') }}';
    var somethingWentWrongLang = '{{ trans('update.something_went_wrong') }}';
    var loadingDataPleaseWaitLang = '{{ trans('update.loading_data,_please_wait') }}';
    var deleteRequestLang = '{{ trans('update.delete_request') }}';
    var deleteRequestTitleLang = '{{ trans('update.delete_request_title') }}';
    var deleteRequestDescriptionLang = '{{ trans('update.delete_request_description') }}';
    var requestDetailsLang = '{{ trans('update.request_details') }}';
    var sendRequestLang = '{{ trans('update.send_request') }}';
    var closeLang = '{{ trans('public.close') }}';
    var generatedContentLang = '{{ trans('update.generated_content') }}';
    var copyLang = '{{ trans('public.copy') }}';
    var doneLang = '{{ trans('public.done') }}';
    var jsCurrentCurrency = '{{ $currency }}';
    var defaultLocale = '{{ getUserLocale() }}';
    var appLocale = '{{ app()->getLocale() }}';
    var dangerCloseIcon = `<x-iconsax-lin-add class="icons text-danger" width="24" height="24"/>`;
    var directSendIcon = `<x-iconsax-lin-direct-send class="icons text-primary" width="24" height="24"/>`;
    var closeIcon = `<x-iconsax-lin-add class="close-icon" width="25px" height="25px"/>`;
    var bulDangerIcon = `<x-iconsax-bul-danger class="icons text-white" width="32px" height="32px"/>`;
    var defaultAvatarPath = "{{ getDefaultAvatarPath() }}";
    var themeColorsMode = @json(getThemeColorsMode());
</script>


<script type="text/javascript" src="/assets/design_1/js/app.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>
<script defer src="/assets/design_1/js/parts/content_delete.min.js"></script>

@if(empty($justMobileApp) and checkShowCookieSecurityDialog() and empty($dontShowCookieSecurity))
    @include('design_1.web.includes.cookie_security.cookie-security')
@endif

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            showToast('{{ session()->get('toast')['status'] }}', '{{ session()->get('toast')['title'] ?? '' }}', '{{ session()->get('toast')['msg'] ?? '' }}')
        })(jQuery)
    </script>
@endif

@include('design_1.web.includes.purchase_notifications')


@stack('styles_bottom')
@stack('scripts_bottom')

@stack('scripts_bottom2')

<script>

    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleFireSwalModal('{!! session()->get('registration_package_limited') !!}', 32)
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty($themeCustomCssAndJs['js']) ? $themeCustomCssAndJs['js'] : '' !!}
</script>

<script src="/assets/design_1/js/parts/general.min.js"></script>
</body>
</html>
