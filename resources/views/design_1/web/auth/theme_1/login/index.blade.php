@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="POST" action="/login">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="claude-strip">
            <span>-- Access / Log in</span>
            <span>- Secure session - 256-bit -</span>
            <span>File n° 09 - 01</span>
        </div>

        <div class="claude-layout-grid">
            <div>
                <div class="claude-form-label">-- Form L - Sign in</div>

                <h1 class="claude-title">
                    Welcome<br>
                    <em>back.</em> <span class="claude-mark">Pick</span> up<br>
                    where you left off.
                </h1>

                <p class="claude-copy">
                    Your bookmarks, notes and progress are exactly where you left them
                    -- sign in to keep going.
                </p>

                <div class="claude-form-stack">
                    <div>
                        <div class="claude-field-head">
                            <span><span class="claude-num">L1</span> Email or mobile</span>
                        </div>
                        @include('design_1.web.auth.theme_1.includes.login_methods')
                    </div>

                    <div class="form-group">
                        <label class="form-group-label" for="password">
                            <span><span class="claude-num">L2</span> {{ trans('auth.password') }}</span>
                            <a href="/forget-password" target="_blank" class="claude-link hint">{{ trans('auth.forget_your_password') }}</a>
                        </label>

                        <div class="position-relative">
                            <input type="password" name="password" class="form-control @error('password')  is-invalid @enderror" id="password" aria-describedby="passwordHelp">

                            <div class="password-input-visibility cursor-pointer size-24">
                                <x-iconsax-lin-eye-slash class="icons-eye-slash text-gray-400 d-none" width="24px" height="24px"/>
                                <x-iconsax-lin-eye class="icons-eye text-gray-400 " width="24px" height="24px"/>
                            </div>
                        </div>

                        @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    @if(!empty(getGeneralSecuritySettings('captcha_for_login')))
                        <div>
                            @include('design_1.web.includes.captcha_input')
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center gap-16 flex-wrap">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" id="rememberSwitch" class="custom-control-input" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control__label cursor-pointer" for="rememberSwitch">{{ trans('auth.remember_me') }}</label>
                        </div>

                        <span class="claude-form-label mb-0">- 30-day session -</span>
                    </div>

                    <div class="claude-action-row">
                        <a href="/register" class="claude-link font-14">
                            {{ trans('auth.dont_have_account') }} <strong>{{ trans('auth.signup') }}</strong>
                        </a>

                        <button type="button" class="js-submit-form-btn btn btn-primary">
                            {{ trans('auth.login') }}
                        </button>
                    </div>

                    @if(!empty(getFeaturesSettings('show_google_login_button')) || !empty(getFeaturesSettings('show_facebook_login_button')))
                        <div class="claude-sso">
                            <div class="claude-sso-head"><span>{{ trans('update.or_continue_with') }}</span></div>

                            <div class="claude-sso-grid {{ (!empty(getFeaturesSettings('show_google_login_button')) && !empty(getFeaturesSettings('show_facebook_login_button'))) ? 'two' : '' }}">
                                @if(!empty(getFeaturesSettings('show_google_login_button')))
                                    <a href="/google" target="_blank">
                                        <span class="claude-sso-dot">G</span>
                                        Google
                                    </a>
                                @endif

                                @if(!empty(getFeaturesSettings('show_facebook_login_button')))
                                    <a href="{{url('/facebook/redirect')}}" target="_blank">
                                        <span class="claude-sso-dot">f</span>
                                        Facebook
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <aside class="claude-side-card">
                <div class="claude-side-kicker">-- What's waiting</div>
                <h2 class="claude-side-title">Pick up<br>where you left.</h2>

                <div class="claude-side-list">
                    <div class="claude-side-item">
                        <div class="claude-side-num">01</div>
                        <div>
                            <div class="claude-side-item-title">OS - Lecture 6 (paused)</div>
                            <div class="claude-side-item-text">Resume at 23:14 of 41:08 -- Mahmoud Salem.</div>
                        </div>
                    </div>

                    <div class="claude-side-item">
                        <div class="claude-side-num">02</div>
                        <div>
                            <div class="claude-side-item-title">3 new replies</div>
                            <div class="claude-side-item-text">On your "Memory paging" thread.</div>
                        </div>
                    </div>

                    <div class="claude-side-item">
                        <div class="claude-side-num">03</div>
                        <div>
                            <div class="claude-side-item-title">Mock exam ready</div>
                            <div class="claude-side-item-text">Fall '25 sample -- 40 questions, 90 min.</div>
                        </div>
                    </div>
                </div>

                <div class="claude-side-badge">Last session - 2 days ago</div>
            </aside>
        </div>

        @if(session()->has('login_failed_active_session'))
            <div class="d-flex align-items-center p-16 rounded-12 border-danger bg-danger-20 mt-32">
                <x-iconsax-bul-info-circle class="icons text-danger" width="32px" height="32px"/>
                <div class="ml-8">
                    <div class="font-14 font-weight-bold text-danger">{{ session()->get('login_failed_active_session')['title'] }}</div>
                    <div class="mt-4 font-12 text-danger">{{ session()->get('login_failed_active_session')['msg'] }}</div>
                </div>
            </div>
        @endif

        <div class="claude-trust-strip">
            <span>-- Trusted by students at</span>
            <span>- Cairo University -</span>
            <span>- AUC -</span>
            <span>- Ain Shams -</span>
            <span>- Alexandria U. -</span>
            <span>- GUC -</span>
        </div>
    </form>
@endsection
