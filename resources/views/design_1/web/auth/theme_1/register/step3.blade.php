@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="POST" action="/register/step/3" class="mt-16" id="step3Form">
        @csrf
        <input type="hidden" name="verification_token" value="{{ $verificationToken ?? '' }}">

        <div class="pl-16">
            <div class="font-16 font-weight-bold">{{ trans('auth.almost_there') }} 🎉</div>
            <h1 class="font-24 mt-4">{{ trans('auth.complete_your_profile') }}</h1>
            <p class="text-gray-500 mt-8">{{ trans('auth.step_3_of_3') }} - {{ trans('auth.final_details') }}</p>
        </div>

        <div class="auth-page-form-container pr-16 mt-16 pt-16" data-simplebar @if((!empty($isRtl) and $isRtl)) data-simplebar-direction="rtl" @endif>
            
            @if(!empty($verified))
                <div class="alert alert-success mb-24">
                    <svg class="size-20 mr-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ trans('auth.email_verified_successfully') }}
                </div>
            @endif

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
                <label class="form-group-label" for="username">{{ trans('auth.username') }}:</label>
                <input name="username" type="text" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-group-label" for="password">{{ trans('auth.password') }}:</label>
                <div class="position-relative">
                    <input name="password" type="password"
                           class="form-control @error('password') is-invalid @enderror" id="password" required>

                    <div class="password-input-visibility cursor-pointer">
                        <x-iconsax-lin-eye-slash class="icons-eye-slash text-gray-400 d-none" width="20px" height="20px"/>
                        <x-iconsax-lin-eye class="icons-eye text-gray-400" width="20px" height="20px"/>
                    </div>
                </div>

                @error('password')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror

                <!-- Password Requirements -->
                <div class="password-requirements mt-16">
                    <div class="requirements-header mb-12">
                        <svg class="mr-8" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>Password Requirements</span>
                    </div>
                    <div class="requirements-list">
                        <div class="requirement-item" data-requirement="length">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">At least 8 characters</span>
                        </div>
                        <div class="requirement-item" data-requirement="uppercase">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">One uppercase letter (A-Z)</span>
                        </div>
                        <div class="requirement-item" data-requirement="lowercase">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">One lowercase letter (a-z)</span>
                        </div>
                        <div class="requirement-item" data-requirement="number">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">One number (0-9)</span>
                        </div>
                        <div class="requirement-item" data-requirement="special">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">One special character (!@#$%...)</span>
                        </div>
                        <div class="requirement-item" data-requirement="username">
                            <span class="requirement-icon">○</span>
                            <span class="requirement-text">Not same as username</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-group-label" for="confirm_password">{{ trans('auth.retype_password') }}:</label>
                <div class="position-relative">
                    <input name="password_confirmation" type="password"
                           class="form-control @error('password_confirmation') is-invalid @enderror" id="confirm_password" required>

                    <div class="password-input-visibility cursor-pointer">
                        <x-iconsax-lin-eye-slash class="icons-eye-slash text-gray-400 d-none" width="20px" height="20px"/>
                        <x-iconsax-lin-eye class="icons-eye text-gray-400" width="20px" height="20px"/>
                    </div>
                </div>

                @error('password_confirmation')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-group-label" for="university_id">{{ trans('update.university') }}:</label>
                <select name="university_id" id="university_id" class="js-university-select form-control @error('university_id') is-invalid @enderror" required>
                    <option value="" disabled {{ empty(old('university_id')) ? 'selected' : '' }}>{{ trans('public.select') }}</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}" {{ (old('university_id') == $university->id) ? 'selected' : '' }}>{{ $university->name }}</option>
                    @endforeach
                </select>
                @error('university_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-group-label" for="faculty_id">{{ trans('update.faculty') }}:</label>
                <select name="faculty_id" id="faculty_id" class="js-faculty-select form-control @error('faculty_id') is-invalid @enderror" required>
                    <option value="" disabled {{ empty(old('faculty_id')) ? 'selected' : '' }}>{{ trans('public.select') }}</option>
                    @if(!empty($faculties))
                        @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ (old('faculty_id') == $faculty->id) ? 'selected' : '' }}>{{ $faculty->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('faculty_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if(!empty($referralSettings) and $referralSettings['status'])
                <div class="form-group">
                    <label class="form-group-label" for="referral_code">{{ trans('financial.referral_code') }} ({{ trans('public.optional') }}):</label>
                    <input name="referral_code" type="text"
                           class="form-control @error('referral_code') is-invalid @enderror" id="referral_code"
                           value="{{ !empty($referralCode) ? $referralCode : old('referral_code') }}"
                           autocomplete="off">
                    @error('referral_code')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            @endif

            <div class="mr-24">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="term" value="1" id="termCheckbox" class="custom-control-input" {{ (old('term') == '1') ? 'checked' : '' }} required>
                    <label class="custom-control__label cursor-pointer" for="termCheckbox">
                        {{ trans('auth.i_agree_with') }}
                        <a href="/pages/terms" target="_blank" class="font-weight-bold text-dark ml-4">{{ trans('auth.terms_and_rules') }}</a>
                    </label>
                </div>

                @error('term')
                <div class="invalid-feedback d-block mt-8">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>

        <div class="pl-16">
            <button type="button" class="js-submit-form-btn btn btn-primary btn-lg btn-block mt-24">
                {{ trans('auth.complete_registration') }} ✓
            </button>

            <div class="d-flex-center flex-column text-center mt-24">
                <span class="text-gray-500">{{ trans('auth.already_have_an_account') }}</span>
                <a href="/login" class="font-weight-bold text-dark mt-8">{{ trans('auth.login') }}</a>
            </div>
        </div>
    </form>

@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("forms") }}"></script>
    <script>
        // Preloaded faculties data to avoid AJAX calls
        window.facultiesByUniversity = @json($facultiesByUniversity ?? []);
    </script>
    <script>
        // Real-time password validation
        (function() {
            const passwordInput = document.getElementById('password');
            const usernameInput = document.getElementById('username');
            const requirements = document.querySelectorAll('.requirement-item');

            if (!passwordInput) return;

            function validatePassword() {
                const password = passwordInput.value;
                const username = usernameInput ? usernameInput.value : '';

                // Check each requirement
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
                    username: !username || password.toLowerCase() !== username.toLowerCase()
                };

                // Update UI for each requirement
                requirements.forEach(function(item) {
                    const requirement = item.getAttribute('data-requirement');
                    const icon = item.querySelector('.requirement-icon');
                    const text = item.querySelector('.requirement-text');
                    
                    if (checks[requirement]) {
                        // Requirement met
                        icon.innerHTML = '✓';
                        icon.style.color = '#28a745';
                        icon.style.fontWeight = 'bold';
                        text.style.color = '#28a745';
                        item.style.opacity = '1';
                    } else if (password.length > 0) {
                        // Requirement not met but user is typing
                        icon.innerHTML = '○';
                        icon.style.color = '#dc3545';
                        icon.style.fontWeight = 'normal';
                        text.style.color = '#6c757d';
                        item.style.opacity = '1';
                    } else {
                        // No input yet
                        icon.innerHTML = '○';
                        icon.style.color = '#6c757d';
                        icon.style.fontWeight = 'normal';
                        text.style.color = '#6c757d';
                        item.style.opacity = '0.7';
                    }
                });
            }

            // Validate on input
            passwordInput.addEventListener('input', validatePassword);
            
            // Also validate when username changes (for username check)
            if (usernameInput) {
                usernameInput.addEventListener('input', validatePassword);
            }

            // Initial validation (in case of back button or autofill)
            validatePassword();
        })();
    </script>
@endpush
