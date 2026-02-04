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

                <h3 class="font-20 font-weight-bold mb-12">{{ trans('auth.verification_email_sent') }}</h3>
                
                <p class="text-gray-500 mb-8">
                    {{ trans('auth.we_sent_verification_email_to') }}
                </p>
                <p class="font-weight-bold mb-24">{{ $email ?? 'your email address' }}</p>

                <div class="bg-warning-light p-16 rounded-12 text-left mb-24">
                    <div class="d-flex align-items-start gap-12">
                        <svg class="size-20 text-warning mt-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-14 mb-8">{{ trans('auth.please_check_your_inbox') }}</p>
                            <p class="font-12 text-gray-500 mb-0">{{ trans('auth.click_verification_link_instruction') }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-24">
                    <p class="font-14 text-gray-500 mb-12">{{ trans('auth.didnt_receive_email') }}</p>
                    
                    <button type="button" class="btn btn-outline-primary js-resend-email" data-email="{{ $email }}">
                        <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ trans('auth.resend_verification_email') }}
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
            // Handle resend email button
            $('.js-resend-email').on('click', function() {
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
                            {{ trans('auth.email_sent_successfully') }}
                        `);
                        
                        setTimeout(function() {
                            btn.prop('disabled', false);
                            btn.removeClass('btn-success').addClass('btn-outline-primary');
                            btn.html(`
                                <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                {{ trans('auth.resend_verification_email') }}
                            `);
                        }, 3000);
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || '{{ trans('auth.failed_to_send_email') }}';
                        btn.prop('disabled', false).html(`
                            <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            {{ trans('auth.resend_verification_email') }}
                        `);
                        alert(message);
                    }
                });
            });
        });
    </script>
@endpush
