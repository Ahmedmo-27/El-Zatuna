@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    {{-- STEP 2 (email verification) temporarily disabled - SMTP issue on droplet. Uncomment the block below and re-enable in RegisterController when SMTP is fixed. --}}
    <div class="mt-16">
        <div class="pl-16">
            <p class="text-gray-500">{{ trans('auth.email_verification') }} (Step 2) is temporarily disabled.</p>
            <p class="mt-8">Please complete registration from the beginning.</p>
            <a href="/register" class="btn btn-primary mt-16">{{ trans('auth.start_over') }}</a>
        </div>
    </div>

    {{--
    <div class="mt-16">
        <div class="pl-16">
            <div class="font-16 font-weight-bold">{{ trans('auth.check_your_email') }} 📧</div>
            <h1 class="font-24 mt-4">{{ trans('auth.verify_your_email') }}</h1>
            <p class="text-gray-500 mt-8">{{ trans('auth.step_2_of_3') }} - {{ trans('auth.email_verification') }}</p>
        </div>

        <div class="auth-page-form-container pr-16 mt-16 pt-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
            
            <div class="text-center py-32">
                <div class="size-80 mx-auto bg-primary-light rounded-circle d-flex-center mb-24">
                    <svg class="size-40 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>

                <h3 class="font-20 font-weight-bold mb-12">{{ trans('auth.enter_verification_code') }}</h3>
                
                <p class="text-gray-500 mb-8">
                    {{ trans('auth.we_sent_6_digit_code_to') }}
                </p>
                <p class="font-weight-bold mb-24">{{ $email ?? 'your email address' }}</p>

                <form id="verificationCodeForm" class="mb-24">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="d-flex justify-content-center gap-8 mb-16">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="width: 50px; height: 56px;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    </div>

                    <div id="verificationError" class="alert alert-danger d-none mb-16"></div>

                    <button type="submit" class="btn btn-primary btn-lg px-32 js-submit-verification-form-btn">
                        {{ trans('auth.verify_email') }}
                    </button>
                </form>

                <div class="bg-info-light p-16 rounded-12 text-left mb-24">
                    <div class="d-flex align-items-start gap-12">
                        <svg class="size-20 text-info mt-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-14 mb-4">{{ trans('auth.code_expires_in_60_minutes') }}</p>
                            <p class="font-12 text-gray-500 mb-0">{{ trans('auth.enter_6_digit_code_from_email') }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-24">
                    <p class="font-14 text-gray-500 mb-12">{{ trans('auth.didnt_receive_code') }}</p>
                    
                    <button type="button" class="btn btn-outline-primary js-resend-code" data-email="{{ $email }}">
                        <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ trans('auth.resend_verification_code') }}
                    </button>
                </div>
            </div>

        </div>

        <div class="pl-16 mt-24">
            <div class="d-flex-center flex-column text-center">
                <span class="text-gray-500">{{ trans('auth.wrong_email_address') }}</span>
                <a href="/register" class="font-weight-bold text-dark mt-8">{{ trans('auth.start_over') }}</a>
            </div>
        </div>
    </div>
    --}}
@endsection

@push('scripts_bottom')
    {{-- Step 2 scripts commented out - restore when SMTP is fixed. See git history for full script. --}}
@endpush
