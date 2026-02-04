@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="POST" action="/register/step/1" class="mt-16" id="step1Form">
        @csrf

        <div class="pl-16">
            <div class="font-16 font-weight-bold">{{ trans('update.join_us_now!') }} 😊</div>
            <h1 class="font-24 mt-4">{{ trans('update.create_an_account') }}</h1>
            <p class="text-gray-500 mt-8">{{ trans('auth.step_1_of_3') }} - {{ trans('auth.basic_information') }}</p>
        </div>

        <div class="auth-page-form-container pr-16 mt-16 pt-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
            
            @if($errors->any())
                <div class="alert alert-danger mb-24">
                    <strong>{{ trans('public.error') }}:</strong>
                    <ul class="mb-0 mt-8">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="form-group">
                <label class="form-group-label" for="full_name">{{ trans('auth.full_name') }}:</label>
                <input name="full_name" type="text" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" required>
                @error('full_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-group-label" for="email">{{ trans('auth.email') }}:</label>
                <input name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if(!empty(getGeneralSecuritySettings('captcha_for_register')))
                <div class="mt-28">
                    @include('design_1.web.includes.captcha_input')
                </div>
            @endif

        </div>

        <div class="pl-16">
            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-24">
                {{ trans('auth.continue') }} →
            </button>

            <div class="d-flex-center flex-column text-center mt-24">
                <span class="text-gray-500">{{ trans('auth.already_have_an_account') }}</span>
                <a href="/login" class="font-weight-bold text-dark mt-8">{{ trans('auth.login') }}</a>
            </div>
        </div>
    </form>

    @if(session('email'))
    <!-- Resend Email Section (shown after first submission) -->
    <div class="mt-32 p-16 bg-white rounded-12 border-gray-300">
        <div class="d-flex align-items-center gap-12">
            <div class="flex-1">
                <p class="font-14 text-gray-500 mb-0">
                    {{ trans('auth.verification_email_sent_to') }} <strong>{{ session('email') }}</strong>
                </p>
                <p class="font-12 text-gray-400 mb-0 mt-4">
                    {{ trans('auth.didnt_receive_email') }}
                </p>
            </div>
            <div>
                <button type="button" class="btn btn-outline-primary js-resend-email" data-email="{{ session('email') }}">
                    <svg class="size-20 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ trans('auth.resend_email') }}
                </button>
            </div>
        </div>
    </div>
    @endif

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("forms") }}"></script>

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
                        btn.prop('disabled', false).html(`
                            <svg class="size-20 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ trans('auth.email_sent') }}
                        `);
                        
                        setTimeout(function() {
                            btn.html(`
                                <svg class="size-20 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ trans('auth.resend_email') }}
                            `);
                        }, 3000);
                    },
                    error: function() {
                        btn.prop('disabled', false).html(`
                            <svg class="size-20 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ trans('auth.resend_email') }}
                        `);
                        alert('{{ trans('auth.failed_to_send_email') }}');
                    }
                });
            });
        });
    </script>
@endpush
