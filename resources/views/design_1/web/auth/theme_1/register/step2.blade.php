@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
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

                <!-- Verification Code Input -->
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

@endsection

@push('scripts_bottom')
    <script>
        $(document).ready(function() {
            // Auto-focus first input
            $('.auth-verification-code-field').first().focus();

            // Handle verification code input
            $('.auth-verification-code-field').on('input', function() {
                const $this = $(this);
                const value = $this.val();
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    $this.val('');
                    return;
                }
                
                // Move to next field if value entered
                if (value.length === 1) {
                    $this.next('.auth-verification-code-field').focus();
                }
                
                // Auto-submit if all fields filled
                checkAutoSubmit();
            });

            // Handle backspace
            $('.auth-verification-code-field').on('keydown', function(e) {
                const $this = $(this);
                
                if (e.key === 'Backspace' && $this.val() === '') {
                    $this.prev('.auth-verification-code-field').focus();
                }
            });

            // Handle paste
            $('.auth-verification-code-field').first().on('paste', function(e) {
                e.preventDefault();
                const pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
                const digits = pastedData.replace(/\D/g, '').substring(0, 6);
                
                $('.auth-verification-code-field').each(function(index) {
                    $(this).val(digits[index] || '');
                });
                
                if (digits.length === 6) {
                    checkAutoSubmit();
                }
            });

            // Check if all fields filled and auto-submit
            function checkAutoSubmit() {
                let allFilled = true;
                $('.auth-verification-code-field').each(function() {
                    if ($(this).val() === '') {
                        allFilled = false;
                        return false;
                    }
                });
                
                if (allFilled) {
                    // Small delay to show the last digit entered
                    setTimeout(function() {
                        $('#verificationCodeForm').submit();
                    }, 300);
                }
            }

            // Handle form submission
            $('#verificationCodeForm').on('submit', function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $btn = $('.js-submit-verification-form-btn');
                const $errorDiv = $('#verificationError');
                const email = $form.find('input[name="email"]').val();
                
                // Get code from inputs
                let code = '';
                $('.auth-verification-code-field').each(function() {
                    code += $(this).val();
                });
                
                if (code.length !== 6) {
                    $errorDiv.removeClass('d-none').text('{{ trans('auth.please_enter_all_6_digits') }}');
                    return;
                }
                
                $errorDiv.addClass('d-none');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-8"></span>{{ trans('auth.verifying') }}...');
                
                $.ajax({
                    url: '/register/step/2',
                    method: 'POST',
                    data: {
                        _token: $form.find('input[name="_token"]').val(),
                        email: email,
                        verification_code: code
                    },
                    success: function(response) {
                        // Check if response has redirect URL
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else if (response.success) {
                            // Fallback redirect to step 3
                            window.location.href = '/register/step/3';
                        } else {
                            // Unexpected response format
                            window.location.href = '/register/step/3';
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || xhr.responseJSON?.errors?.verification_code?.[0] || '{{ trans('auth.invalid_verification_code') }}';
                        $errorDiv.removeClass('d-none').text(message);
                        $btn.prop('disabled', false).html('{{ trans('auth.verify_email') }}');
                        
                        // Clear all fields
                        $('.auth-verification-code-field').val('').first().focus();
                    }
                });
            });

            // Handle resend code button
            $('.js-resend-code').on('click', function() {
                const btn = $(this);
                const email = btn.data('email');
                
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-8"></span>{{ trans('auth.sending') }}...');

                $.ajax({
                    url: '/api/v1/auth/resend-verification',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ email: email }),
                    success: function(response) {
                        btn.removeClass('btn-outline-primary').addClass('btn-success');
                        btn.html(`
                            <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ trans('auth.code_sent_successfully') }}
                        `);
                        
                        // Clear code fields
                        $('.auth-verification-code-field').val('').first().focus();
                        
                        setTimeout(function() {
                            btn.prop('disabled', false);
                            btn.removeClass('btn-success').addClass('btn-outline-primary');
                            btn.html(`
                                <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                {{ trans('auth.resend_verification_code') }}
                            `);
                        }, 3000);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || '{{ trans('auth.failed_to_send_code') }}';
                        btn.prop('disabled', false).html(`
                            <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ trans('auth.resend_verification_code') }}
                        `);
                        alert(message);
                    }
                });
            });
        });
    </script>
@endpush
