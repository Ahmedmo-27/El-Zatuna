@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("auth/theme_1") }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=JetBrains+Mono:wght@400;600;700&display=swap');

        :root {
            --ez-cream: #FAFFE0;
            --ez-cream-2: #F4F8D2;
            --ez-ink: #072923;
            --ez-muted: rgba(7, 41, 35, .58);
            --ez-line: rgba(7, 41, 35, .18);
            --ez-citron: #C8CD06;
            --ez-leaf: #486B5F;
            --primary: var(--ez-citron);
            --secondary: var(--ez-ink);
        }

        body {
            background-color: var(--ez-cream) !important;
            color: var(--ez-ink) !important;
        }

        #app > div:first-child,
        #app > div:nth-child(2):not(.cart-drawer):not(.cart-drawer-mask),
        #app > nav,
        #mobileMenuOverlay,
        #mobileMenuPanel,
        #app > footer {
            display: none !important;
        }

        .auth-page-card__mask {
            display: none !important;
        }

        .claude-auth-page {
            padding: 80px 0 104px;
        }

        .claude-auth-container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 32px;
        }

        .claude-strip {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            padding-bottom: 32px;
            border-bottom: 1px solid var(--ez-line);
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-layout-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.6fr) minmax(320px, 1fr);
            gap: 80px;
            align-items: start;
            padding-top: 64px;
        }

        .claude-form-label {
            margin-bottom: 16px;
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-title {
            margin: 0 0 28px;
            color: var(--ez-ink) !important;
            font-family: "Instrument Serif", "Times New Roman", serif;
            font-size: clamp(56px, 8vw, 120px);
            line-height: .96;
            letter-spacing: 0;
            font-weight: 400;
        }

        .claude-title.register {
            font-size: clamp(64px, 9.5vw, 148px);
            line-height: .94;
        }

        .claude-title.small {
            font-size: clamp(40px, 5vw, 64px);
            line-height: 1.02;
        }

        .claude-title em {
            font-style: italic;
        }

        .claude-mark {
            position: relative;
            display: inline-block;
        }

        .claude-mark:after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: .04em;
            height: .32em;
            border-radius: 4px;
            background: var(--ez-citron);
            z-index: -1;
        }

        .claude-copy {
            max-width: 520px;
            margin: 0 0 56px;
            color: var(--ez-muted) !important;
            font-size: 17px;
            line-height: 1.5;
        }

        .claude-form-stack {
            display: grid;
            gap: 36px;
        }

        .claude-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            column-gap: 40px;
            row-gap: 36px;
        }

        .claude-span-2 {
            grid-column: 1 / -1;
        }

        .claude-field,
        .claude-form-stack .form-group,
        .claude-form-grid .form-group {
            position: relative;
            margin: 0;
        }

        .claude-field-head,
        .claude-form-stack .form-group-label,
        .claude-form-grid .form-group-label,
        .claude-register-method-title {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 8px;
            color: var(--ez-muted) !important;
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .claude-field-head .hint {
            font-family: inherit;
            font-size: 10px;
            letter-spacing: .08em;
            color: var(--ez-muted);
            white-space: normal;
        }

        .claude-num {
            margin-right: 8px;
        }

        .claude-auth-page .form-control,
        .claude-auth-page .register-mobile-form-group {
            width: 100%;
            min-height: 46px;
            padding: 0 0 12px !important;
            border: 0 !important;
            border-bottom: 1px solid var(--ez-line) !important;
            border-radius: 0 !important;
            background: transparent !important;
            color: var(--ez-ink) !important;
            box-shadow: none !important;
            font-size: 14px;
        }

        .claude-auth-page textarea.form-control {
            min-height: 132px;
            padding-top: 10px !important;
            resize: vertical;
        }

        .claude-auth-page .form-control:focus,
        .claude-auth-page .register-mobile-form-group:focus-within {
            border-color: var(--ez-ink) !important;
            box-shadow: none !important;
        }

        .claude-auth-page .form-control::placeholder {
            color: rgba(7, 41, 35, .42);
        }

        .claude-auth-page .invalid-feedback {
            margin-top: 7px;
        }

        .password-input-visibility {
            position: absolute;
            right: 0;
            top: 20px;
            z-index: 10;
        }

        .claude-auth-page .custom-control__label,
        .claude-auth-page .text-gray-500 {
            color: var(--ez-muted) !important;
        }

        .claude-action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 24px;
            padding-top: 40px;
            border-top: 1px solid var(--ez-line);
        }

        .claude-link,
        .claude-auth-page a,
        .claude-auth-page a.text-dark {
            color: var(--ez-ink) !important;
        }

        .claude-link {
            font-weight: 600;
            text-decoration: none;
            border-bottom: 1px solid rgba(7, 41, 35, .36);
        }

        .claude-link:hover {
            border-color: var(--ez-ink);
        }

        .claude-auth-page .btn-primary {
            min-height: 54px;
            padding: 0 28px;
            border: 1px solid var(--ez-citron) !important;
            border-radius: 999px !important;
            background: var(--ez-citron) !important;
            color: var(--ez-ink) !important;
            font-size: 15px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .claude-auth-page .btn-primary:after {
            content: "↗";
            font-size: 13px;
            line-height: 1;
        }

        .claude-auth-page .btn-outline-primary {
            min-height: 42px;
            border-radius: 999px !important;
            border-color: var(--ez-ink) !important;
            color: var(--ez-ink) !important;
            background: transparent !important;
            font-weight: 700;
        }

        .claude-auth-page .btn-outline-primary:hover {
            background: var(--ez-ink) !important;
            color: var(--ez-cream) !important;
        }

        .claude-auth-page .auth-register-method-item label {
            height: 44px;
            border-radius: 0;
            background: transparent;
            color: var(--ez-ink);
            font-size: 14px;
            font-weight: 700;
        }

        .claude-auth-page .auth-register-method-item input:checked ~ label {
            background: var(--ez-ink) !important;
            color: var(--ez-cream) !important;
        }

        .claude-segmented {
            border: 1px solid var(--ez-line);
            border-radius: 0;
            padding: 4px;
        }

        .claude-role-switch {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-top: 64px;
            border-top: 1px solid var(--ez-line);
            border-bottom: 1px solid var(--ez-line);
        }

        .claude-role-option {
            position: relative;
            min-height: 104px;
        }

        .claude-role-option + .claude-role-option {
            border-left: 1px solid var(--ez-line);
        }

        .claude-role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .claude-role-option label {
            height: 100%;
            margin: 0;
            padding: 28px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 24px;
            color: var(--ez-ink);
            cursor: pointer;
            transition: background .25s ease, color .25s ease;
        }

        .claude-role-option input:checked + label {
            background: var(--ez-ink);
            color: var(--ez-cream);
        }

        .claude-role-index {
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 13px;
            letter-spacing: .14em;
        }

        .claude-role-option input:checked + label .claude-role-index {
            color: var(--ez-citron);
        }

        .claude-role-title {
            display: block;
            font-family: "Instrument Serif", "Times New Roman", serif;
            font-size: 30px;
            line-height: 1.1;
            letter-spacing: 0;
            font-weight: 400;
        }

        .claude-role-copy {
            display: block;
            margin-top: 4px;
            color: inherit;
            opacity: .65;
            font-size: 14px;
        }

        .claude-role-dot {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid currentColor;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .claude-role-option input:checked + label .claude-role-dot {
            background: var(--ez-citron);
            border-color: var(--ez-citron);
            color: var(--ez-ink);
        }

        .claude-note {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px;
            border: 1px solid var(--ez-line);
            border-radius: 16px;
            background: var(--ez-cream-2);
            color: var(--ez-ink);
            font-size: 15px;
            line-height: 1.5;
        }

        .claude-note.dark {
            background: var(--ez-ink);
            color: var(--ez-cream);
            border-color: var(--ez-ink);
        }

        .claude-note-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--ez-citron);
            color: var(--ez-ink);
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            font-weight: 800;
        }

        .claude-part-label {
            margin: 56px 0 32px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--ez-line);
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-pill-list,
        .js-occupations-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .claude-auth-page .js-occupations-dropdown {
            border-color: var(--ez-line) !important;
            border-radius: 12px !important;
        }

        .claude-auth-page .js-occupations-wrapper {
            margin-top: 8px;
        }

        .claude-auth-page .js-occupations-wrapper .form-group-label {
            margin-bottom: 10px;
        }

        .claude-auth-page .js-occupations-wrapper p {
            margin-bottom: 18px !important;
        }

        .claude-auth-page .js-occupations-input {
            margin-top: 6px;
            padding-top: 6px !important;
        }

        .claude-auth-page .js-occupations-tags {
            margin-top: 16px !important;
            gap: 10px;
        }

        .claude-auth-page .js-occupations-hidden-container {
            margin-top: 8px;
        }

        .claude-auth-page .password-requirements {
            border: 1px solid var(--ez-line);
            border-radius: 12px;
            background: rgba(255, 255, 255, .35);
            box-shadow: none;
        }

        .claude-side-card {
            position: sticky;
            top: 100px;
            min-height: 600px;
            padding: 36px;
            overflow: hidden;
            border-radius: 24px;
            background: var(--ez-ink);
            color: var(--ez-cream);
        }

        .claude-side-card:before {
            content: "";
            position: absolute;
            right: -68px;
            top: -32px;
            width: 330px;
            height: 120px;
            opacity: .18;
            background:
                radial-gradient(ellipse at center, var(--ez-citron) 0 42%, transparent 45%) 0 62px / 42px 22px repeat-x;
            transform: rotate(-26deg);
        }

        .claude-side-kicker {
            position: relative;
            z-index: 1;
            color: var(--ez-citron);
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-side-title {
            position: relative;
            z-index: 1;
            margin: 20px 0 0;
            color: var(--ez-cream) !important;
            font-family: "Instrument Serif", "Times New Roman", serif;
            font-size: 44px;
            line-height: 1.02;
            letter-spacing: 0;
            font-weight: 400;
        }

        .claude-side-list {
            position: relative;
            z-index: 1;
            margin-top: 40px;
        }

        .claude-side-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 0;
            border-top: 1px solid rgba(250, 255, 224, .12);
        }

        .claude-side-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--ez-citron);
            color: var(--ez-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            font-weight: 700;
        }

        .claude-side-item-title {
            color: var(--ez-cream);
            font-family: "Instrument Serif", "Times New Roman", serif;
            font-size: 20px;
            line-height: 1.25;
        }

        .claude-side-item-text {
            margin-top: 4px;
            color: rgba(250, 255, 224, .62);
            font-size: 13px;
            line-height: 1.45;
        }

        .claude-side-badge {
            position: relative;
            z-index: 1;
            margin-top: 28px;
            padding: 14px 16px;
            border: 1px solid rgba(200, 205, 6, .25);
            border-radius: 14px;
            background: rgba(200, 205, 6, .1);
            color: rgba(250, 255, 224, .85);
            font-family: "JetBrains Mono", monospace;
            font-size: 10px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-sso {
            margin-top: 40px;
        }

        .claude-sso-head {
            display: flex;
            align-items: center;
            gap: 16px;
            margin: 8px 0 18px;
        }

        .claude-sso-head:before,
        .claude-sso-head:after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--ez-line);
        }

        .claude-sso-head span {
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 10px;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .claude-sso-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .claude-sso-grid.two {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .claude-sso-grid a,
        .claude-sso-grid button {
            min-height: 46px;
            padding: 0 14px;
            border: 1px solid var(--ez-line);
            border-radius: 14px;
            background: transparent;
            color: var(--ez-ink);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .claude-sso-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--ez-ink);
            color: var(--ez-cream);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: "JetBrains Mono", monospace;
            font-size: 10px;
            font-weight: 700;
        }

        .claude-trust-strip {
            margin-top: 72px;
            padding-top: 40px;
            border-top: 1px solid var(--ez-line);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
            color: var(--ez-muted);
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .claude-closing {
            margin-top: 64px;
            padding: 56px;
            border-radius: 28px;
            background: var(--ez-citron);
            color: var(--ez-ink);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            overflow: hidden;
            position: relative;
        }

        .claude-closing:after {
            content: "";
            position: absolute;
            right: -42px;
            top: -22px;
            width: 340px;
            height: 120px;
            opacity: .35;
            background:
                radial-gradient(ellipse at center, var(--ez-ink) 0 42%, transparent 45%) 0 62px / 42px 22px repeat-x;
            transform: rotate(-26deg);
        }

        .claude-closing > * {
            position: relative;
            z-index: 1;
        }

        .claude-closing h2 {
            margin: 0;
            color: var(--ez-ink) !important;
            font-family: "Instrument Serif", "Times New Roman", serif;
            font-size: clamp(36px, 4.5vw, 56px);
            line-height: 1.02;
            letter-spacing: 0;
            font-weight: 400;
        }

        .claude-closing p {
            max-width: 520px;
            margin: 16px 0 0;
            color: rgba(7, 41, 35, .75);
            font-size: 16px;
            line-height: 1.5;
        }

        .auth-page-form-container {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
            padding: 0 !important;
        }

        @media (max-width: 1100px) {
            .claude-layout-grid {
                grid-template-columns: 1fr;
            }

            .claude-side-card {
                position: relative;
                top: auto;
            }
        }

        @media (max-width: 768px) {
            .claude-auth-page {
                padding: 36px 0 64px;
            }

            .claude-auth-container {
                padding: 0 18px;
            }

            .claude-strip {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .claude-field-head,
            .claude-form-stack .form-group-label,
            .claude-form-grid .form-group-label,
            .claude-register-method-title {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }

            .claude-layout-grid,
            .claude-form-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .claude-title,
            .claude-title.register {
                font-size: 56px;
            }

            .claude-role-switch,
            .claude-sso-grid,
            .claude-sso-grid.two {
                grid-template-columns: 1fr;
            }

            .claude-role-option + .claude-role-option {
                border-left: 0;
                border-top: 1px solid var(--ez-line);
            }

            .claude-closing {
                padding: 32px 24px;
                border-radius: 20px;
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
@endpush

@section("content")
    <section class="claude-auth-page">
        <div class="claude-auth-container">
            @yield("page_content")
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="{{ getDesign1ScriptPath("auth_theme_1") }}"></script>
@endpush
