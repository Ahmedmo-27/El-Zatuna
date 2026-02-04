@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="Post" action="/register/step/3" class="mt-16" id="step3Form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
        $(document).ready(function() {
            // Handle university change to load faculties
            $('#university_id').on('change', function() {
                const universityId = $(this).val();
                const facultySelect = $('#faculty_id');
                
                facultySelect.html('<option value="" disabled selected>{{ trans('public.loading') }}...</option>');
                
                if (universityId) {
                    $.ajax({
                        url: '/universities/' + universityId + '/faculties',
                        method: 'GET',
                        success: function(response) {
                            facultySelect.html('<option value="" disabled selected>{{ trans('public.select') }}</option>');
                            
                            // Check if response has faculties array
                            const faculties = response.faculties || response;
                            
                            if (faculties && faculties.length > 0) {
                                faculties.forEach(function(faculty) {
                                    facultySelect.append('<option value="' + faculty.id + '">' + faculty.name + '</option>');
                                });
                            } else {
                                facultySelect.append('<option value="" disabled>{{ trans('public.no_result') }}</option>');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error loading faculties:', xhr);
                            facultySelect.html('<option value="" disabled selected>{{ trans('public.error') }}</option>');
                        }
                    });
                }
            });
        });
    </script>
@endpush
