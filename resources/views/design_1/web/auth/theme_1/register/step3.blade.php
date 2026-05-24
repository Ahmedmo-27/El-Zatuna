@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="POST" action="/register/step/3" id="step3Form">
        @csrf
        <input type="hidden" name="verification_token" value="{{ $verificationToken ?? '' }}">

        <div class="claude-strip">
            <span>-- Access / Final details</span>
            <span>- Step 3 of 3 -</span>
            <span>File n° 09 - 03</span>
        </div>

        <div class="claude-layout-grid">
            <div>
                <div class="claude-form-label">
                    @if(!empty($isTeacher))
                        -- Form I - New instructor
                    @else
                        -- Form S - New student
                    @endif
                </div>

                <h1 class="claude-title small">
                    @if(!empty($isTeacher))
                        Tell us how<br>you <em>teach.</em>
                    @else
                        A few things,<br>then you're <em>in.</em>
                    @endif
                </h1>

                @if(!empty($verified))
                    <div class="claude-note mb-24">
                        <span class="claude-note-icon">OK</span>
                        <p class="mb-0">{{ trans('auth.email_verified_successfully') }}</p>
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

                @if(!empty($isTeacher))
                    <div class="claude-note dark">
                        <span class="claude-note-icon">I</span>
                        <p class="mb-0">
                            Instructors go through a 20-minute interview after this step.
                            We'll email you within 48h to schedule.
                        </p>
                    </div>
                @else
                    <div class="claude-note">
                        <span class="claude-note-icon">S</span>
                        <p class="mb-0">
                            Your first week is free -- no card, no commitment.
                            Cancel from the dashboard in two clicks.
                        </p>
                    </div>
                @endif

                <div class="claude-part-label">Part 1 - Account details</div>

                <div class="claude-form-grid">
                    <div class="form-group claude-span-2">
                        <label class="form-group-label" for="username">
                            <span><span class="claude-num">P1</span>{{ trans('auth.username') }}</span>
                        </label>
                        <input name="username" id="username" type="text" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-group-label" for="password">
                            <span><span class="claude-num">P2</span>{{ trans('auth.password') }}</span>
                            <span class="hint">Strong password</span>
                        </label>
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
                        <label class="form-group-label" for="confirm_password">
                            <span><span class="claude-num">P3</span>{{ trans('auth.retype_password') }}</span>
                        </label>
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

                    <div class="claude-span-2">
                        <div class="password-requirements mt-0">
                            <div class="requirements-header mb-12">
                                <svg class="mr-8" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Password Requirements</span>
                            </div>
                            <div class="requirements-list">
                                <div class="requirement-item" data-requirement="length">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">At least 8 characters</span>
                                </div>
                                <div class="requirement-item" data-requirement="uppercase">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">One uppercase letter (A-Z)</span>
                                </div>
                                <div class="requirement-item" data-requirement="lowercase">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">One lowercase letter (a-z)</span>
                                </div>
                                <div class="requirement-item" data-requirement="number">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">One number (0-9)</span>
                                </div>
                                <div class="requirement-item" data-requirement="special">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">One special character (!@#$%...)</span>
                                </div>
                                <div class="requirement-item" data-requirement="username">
                                    <span class="requirement-icon">o</span>
                                    <span class="requirement-text">Not same as username</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!empty($isTeacher))
                    <div class="claude-part-label">Part 2 - What you teach</div>

                    @php
                        $occupationsInitial = [];
                        $occupationsOld = is_array(old('occupations')) ? old('occupations') : [];
                        $instructorCategoriesList = $instructorCategories ?? [];
                        foreach ($instructorCategoriesList as $cat) {
                            if (!empty($cat->subCategories) && count($cat->subCategories)) {
                                foreach ($cat->subCategories as $sub) {
                                    if (in_array($sub->id, $occupationsOld)) {
                                        $occupationsInitial[] = ['id' => $sub->id, 'text' => $sub->title];
                                    }
                                }
                            } else {
                                if (in_array($cat->id, $occupationsOld)) {
                                    $occupationsInitial[] = ['id' => $cat->id, 'text' => $cat->title];
                                }
                            }
                        }
                    @endphp

                    <div class="claude-form-grid">
                        <div class="form-group claude-span-2">
                            <div class="js-occupations-wrapper" data-initial="{{ e(json_encode($occupationsInitial)) }}">
                                <div class="form-group-label">
                                    <span><span class="claude-num">I1</span>Subjects you can teach</span>
                                    <span class="hint">Pick all that apply</span>
                                </div>
                                <p class="text-gray-500 mb-12">Select the subjects or topics you want to teach. Type to search existing ones.</p>

                                <div class="position-relative">
                                    <input type="text" id="occupationsInput" class="form-control js-occupations-input" placeholder="Type a subject name..." autocomplete="off">

                                    <div class="js-occupations-dropdown position-absolute bg-white border rounded-12 shadow-sm mt-1 d-none" style="top: 100%; left: 0; right: 0; max-height: 220px; overflow-y: auto; z-index: 1050;">
                                        <div class="js-occupations-results p-2"></div>
                                        <div class="js-occupations-add-new border-top p-2 cursor-pointer" style="font-size: 13px;">
                                            <span class="js-add-new-text">Add different subject</span> - <span class="js-add-new-term font-weight-medium"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="js-occupations-tags mt-12" style="min-height: 24px;"></div>
                                <div class="js-occupations-hidden-container"></div>

                                @error('occupations')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('occupations.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group claude-span-2">
                            <label class="form-group-label" for="description">
                                <span><span class="claude-num">I2</span>{{ trans('public.description') }}</span>
                                <span class="hint">2-3 sentences</span>
                            </label>
                            <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror" placeholder="Tell us about your teaching experience and expertise" required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="claude-part-label">
                    @if(!empty($isTeacher))
                        Part 3 - Where you study
                    @else
                        Part 2 - Where you study
                    @endif
                    @if(!empty($isTeacher))
                        <span class="hint">{{ trans('public.optional') }}</span>
                    @endif
                </div>

                <div class="claude-form-grid">
                    <div class="form-group">
                        <label class="form-group-label" for="university_id">
                            <span><span class="claude-num">S1</span>{{ trans('update.university') }}</span>
                            @if(!empty($isTeacher))
                                <span class="hint">{{ trans('public.optional') }}</span>
                            @endif
                        </label>
                        <select name="university_id" id="university_id" class="js-university-select form-control @error('university_id') is-invalid @enderror" @if(empty($isTeacher)) required @endif>
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
                        <label class="form-group-label" for="faculty_id">
                            <span><span class="claude-num">S2</span>{{ trans('update.faculty') }}</span>
                            @if(!empty($isTeacher))
                                <span class="hint">{{ trans('public.optional') }}</span>
                            @endif
                        </label>
                        <select name="faculty_id" id="faculty_id" class="js-faculty-select form-control @error('faculty_id') is-invalid @enderror" data-selected-faculty="{{ old('faculty_id') }}" @if(empty($isTeacher)) required @endif>
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
                </div>

                @if(!empty($referralSettings) and $referralSettings['status'])
                    <div class="claude-part-label">
                        @if(!empty($isTeacher))
                            Part 4 - Optional
                        @else
                            Part 3 - Optional
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-group-label" for="referral_code">
                            <span><span class="claude-num">R1</span>{{ trans('financial.referral_code') }} ({{ trans('public.optional') }})</span>
                        </label>
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

                <div class="claude-action-row">
                    <div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="term" value="1" id="termCheckbox" class="custom-control-input" {{ (old('term') == '1') ? 'checked' : '' }} required>
                            <label class="custom-control__label cursor-pointer" for="termCheckbox">
                                {{ trans('auth.i_agree_with') }}
                                <a href="/terms" target="_blank" class="claude-link ml-4">{{ trans('auth.terms_and_rules') }}</a>
                            </label>
                        </div>

                        @error('term')
                        <div class="invalid-feedback d-block mt-8">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="button" class="js-submit-form-btn btn btn-primary">
                        {{ trans('auth.complete_registration') }}
                    </button>
                </div>

                <div class="d-flex-center flex-column text-center mt-32">
                    <span class="text-gray-500">{{ trans('auth.already_have_an_account') }}</span>
                    <a href="/login" class="claude-link mt-8">{{ trans('auth.login') }}</a>
                </div>
            </div>

            <aside class="claude-side-card">
                @if(!empty($isTeacher))
                    <div class="claude-side-kicker">-- How it works</div>
                    <h2 class="claude-side-title">Teach what<br>you know best.</h2>

                    <div class="claude-side-list">
                        <div class="claude-side-item">
                            <div class="claude-side-num">01</div>
                            <div>
                                <div class="claude-side-item-title">You set your hours</div>
                                <div class="claude-side-item-text">Block your week -- we route students to your slots.</div>
                            </div>
                        </div>
                        <div class="claude-side-item">
                            <div class="claude-side-num">02</div>
                            <div>
                                <div class="claude-side-item-title">Course toolkit included</div>
                                <div class="claude-side-item-text">Slides, quizzes, attendance, recordings -- built in.</div>
                            </div>
                        </div>
                        <div class="claude-side-item">
                            <div class="claude-side-num">03</div>
                            <div>
                                <div class="claude-side-item-title">Real onboarding</div>
                                <div class="claude-side-item-text">A founder walks you through your first session.</div>
                            </div>
                        </div>
                    </div>

                    <div class="claude-side-badge">Avg payout - EGP 12,400 / month</div>
                @else
                    <div class="claude-side-kicker">-- What you get</div>
                    <h2 class="claude-side-title">The whole<br>library, day one.</h2>

                    <div class="claude-side-list">
                        <div class="claude-side-item">
                            <div class="claude-side-num">01</div>
                            <div>
                                <div class="claude-side-item-title">48 university courses</div>
                                <div class="claude-side-item-text">Built around real syllabi from 12 universities.</div>
                            </div>
                        </div>
                        <div class="claude-side-item">
                            <div class="claude-side-num">02</div>
                            <div>
                                <div class="claude-side-item-title">Live study rooms</div>
                                <div class="claude-side-item-text">Drop into focused sessions with peers all week.</div>
                            </div>
                        </div>
                        <div class="claude-side-item">
                            <div class="claude-side-num">03</div>
                            <div>
                                <div class="claude-side-item-title">Mock exams & past papers</div>
                                <div class="claude-side-item-text">Auto-graded, with explanations from your tutors.</div>
                            </div>
                        </div>
                        <div class="claude-side-item">
                            <div class="claude-side-num">04</div>
                            <div>
                                <div class="claude-side-item-title">Cancel any time</div>
                                <div class="claude-side-item-text">Two clicks from your dashboard. No phone calls.</div>
                            </div>
                        </div>
                    </div>

                    <div class="claude-side-badge">Free trial - 7 days</div>
                @endif
            </aside>
        </div>

        <section class="claude-closing">
            <div>
                <div class="claude-form-label">-- P.S.</div>
                <h2>
                    @if(!empty($isTeacher))
                        We'll <em>email</em> within 48 hours.
                    @else
                        No card, <em>really</em>.
                    @endif
                </h2>
                <p>
                    @if(!empty($isTeacher))
                        A founder reviews every application personally -- no auto-reject. We'll set up a short call to walk through your first course together.
                    @else
                        Your free week starts the moment you sign up. Add a card only if you decide to stay -- no auto-conversion, no dark patterns.
                    @endif
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--ez-ink); border: 3px solid var(--ez-citron);"></div>
                <div>
                    <div style="font-family: 'Instrument Serif', 'Times New Roman', serif; font-size: 20px;">Ziad Yasser</div>
                    <div style="font-size: 13px; color: rgba(7, 41, 35, .65);">Founder</div>
                </div>
            </div>
        </section>
    </form>
@endsection

@push('scripts_bottom')
    <script src="{{ getDesign1ScriptPath("forms") }}"></script>
    @if(!empty($isTeacher))
    <script src="{{ getDesign1ScriptPath("become_instructor_wizard") }}"></script>
    @endif
    <script>
        window.facultiesByUniversity = @json($facultiesByUniversity ?? []);
    </script>
    <script>
        (function() {
            const passwordInput = document.getElementById('password');
            const usernameInput = document.getElementById('username');
            const requirements = document.querySelectorAll('.requirement-item');

            if (!passwordInput) return;

            function validatePassword() {
                const password = passwordInput.value;
                const username = usernameInput ? usernameInput.value : '';

                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password),
                    username: !username || password.toLowerCase() !== username.toLowerCase()
                };

                requirements.forEach(function(item) {
                    const requirement = item.getAttribute('data-requirement');
                    const icon = item.querySelector('.requirement-icon');
                    const text = item.querySelector('.requirement-text');

                    if (checks[requirement]) {
                        icon.innerHTML = 'ok';
                        icon.style.color = '#28a745';
                        icon.style.fontWeight = 'bold';
                        text.style.color = '#28a745';
                        item.style.opacity = '1';
                    } else if (password.length > 0) {
                        icon.innerHTML = 'o';
                        icon.style.color = '#dc3545';
                        icon.style.fontWeight = 'normal';
                        text.style.color = '#6c757d';
                        item.style.opacity = '1';
                    } else {
                        icon.innerHTML = 'o';
                        icon.style.color = '#6c757d';
                        icon.style.fontWeight = 'normal';
                        text.style.color = '#6c757d';
                        item.style.opacity = '0.7';
                    }
                });
            }

            passwordInput.addEventListener('input', validatePassword);

            if (usernameInput) {
                usernameInput.addEventListener('input', validatePassword);
            }

            validatePassword();
        })();
    </script>
@endpush
