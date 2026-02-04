@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <div class="mt-16">
        <div class="auth-page-form-container pr-16 mt-16 pt-16 text-center" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
            
            <!-- Success Animation -->
            <div class="py-48">
                <div class="size-120 mx-auto bg-success-light rounded-circle d-flex-center mb-32 animate-pulse">
                    <svg class="size-60 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                <h1 class="font-36 font-weight-bold mb-16" style="color: #C8CD06 !important;">
                    {{ trans('auth.welcome_to_el_zatuna') }} 🎉
                </h1>

                <div class="mb-32">
                    <h3 class="font-20 font-weight-bold mb-8">{{ trans('auth.congratulations') }}, {{ $user->full_name }}!</h3>
                    <p class="text-gray-500 font-16">{{ trans('auth.account_created_successfully') }}</p>
                </div>

                <div class="bg-primary-light p-24 rounded-16 mb-32 mx-auto" style="max-width: 500px;">
                    <div class="d-flex align-items-start gap-16 text-left">
                        <svg class="size-24 text-primary flex-shrink-0 mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-14 mb-4 font-weight-bold">{{ trans('auth.youre_all_set') }}</p>
                            <p class="font-12 text-gray-500 mb-0">{{ trans('auth.redirecting_to_dashboard') }}</p>
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="border rounded-16 p-24 mb-32 mx-auto text-left" style="max-width: 500px;">
                    <h4 class="font-16 font-weight-bold mb-16">{{ trans('auth.your_account_details') }}</h4>
                    <div class="d-flex justify-content-between align-items-center mb-12">
                        <span class="text-gray-500">{{ trans('auth.full_name') }}:</span>
                        <span class="font-weight-bold">{{ $user->full_name }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-12">
                        <span class="text-gray-500">{{ trans('auth.username') }}:</span>
                        <span class="font-weight-bold">{{ $user->username }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-12">
                        <span class="text-gray-500">{{ trans('auth.email') }}:</span>
                        <span class="font-weight-bold">{{ $user->email }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-gray-500">{{ trans('update.university') }}:</span>
                        <span class="font-weight-bold">{{ $user->university->name ?? '-' }}</span>
                    </div>
                </div>

                <!-- Auto-redirect message -->
                <p class="font-14 text-gray-500 mb-24">
                    {{ trans('auth.redirecting_in') }} <span id="countdown" class="font-weight-bold text-primary">3</span> {{ trans('auth.seconds') }}...
                </p>

                <!-- Manual redirect button -->
                <a href="/panel" class="btn btn-primary btn-lg px-48">
                    {{ trans('auth.go_to_dashboard') }} →
                </a>
            </div>

        </div>
    </div>

@endsection

@push('styles_top')
<style>
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.9;
        }
    }

    .animate-pulse {
        animation: pulse 2s ease-in-out infinite;
    }

    .bg-success-light {
        background-color: rgba(40, 199, 111, 0.1);
    }

    .text-success {
        color: #28c76f !important;
    }

    .bg-primary-light {
        background-color: rgba(200, 205, 6, 0.1);
    }
</style>
@endpush

@push('scripts_bottom')
<script>
    $(document).ready(function() {
        let countdown = 3;
        const countdownElement = $('#countdown');
        
        const timer = setInterval(function() {
            countdown--;
            countdownElement.text(countdown);
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = '/panel';
            }
        }, 1000);
    });
</script>
@endpush
