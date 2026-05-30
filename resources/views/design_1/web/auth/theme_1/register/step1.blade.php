@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <form method="POST" action="/register/step/1" id="step1Form">
        @csrf

        <div class="claude-strip">
            <span>-- Access / Register</span>
            <span>- Takes 90 seconds -</span>
            <span>File n° 09 - 02</span>
        </div>

        <section style="padding-top: 64px; padding-bottom: 48px;">
            <div class="claude-layout-grid" style="padding-top: 0; align-items: end; grid-template-columns: 1.4fr 1fr;">
                <div>
                    <h1 class="claude-title register">
                        Start the<br>
                        <em>good</em> kind<br>
                        of <span class="claude-mark">semester</span>.
                    </h1>
                </div>

                <div style="padding-bottom: 8px;">
                    <div class="claude-form-label">-- Are you here to...</div>
                    <p style="margin: 0; font-family: 'Instrument Serif', 'Times New Roman', serif; font-size: 22px; line-height: 1.4;">
                        Pick one of the two below -- the form changes to ask the
                        right questions, and your account is set up accordingly.
                    </p>
                </div>
            </div>

            <div class="claude-role-switch">
                <div class="claude-role-option">
                    <input type="radio" name="account_type" value="user" id="userRole" {{ (old('account_type', 'user') == 'user') ? 'checked' : '' }}>
                    <label for="userRole">
                        <span class="claude-role-index">01</span>
                        <span>
                            <span class="claude-role-title">I'm a student</span>
                            <span class="claude-role-copy">Take courses, join study rooms, get exam-ready.</span>
                        </span>
                        <span class="claude-role-dot">✓</span>
                    </label>
                </div>

                <div class="claude-role-option">
                    <input type="radio" name="account_type" value="teacher" id="teacherRole" {{ (old('account_type') == 'teacher') ? 'checked' : '' }}>
                    <label for="teacherRole">
                        <span class="claude-role-index">02</span>
                        <span>
                            <span class="claude-role-title">I teach</span>
                            <span class="claude-role-copy">Build courses, run sessions, get paid every month.</span>
                        </span>
                        <span class="claude-role-dot">→</span>
                    </label>
                </div>
            </div>

            @error('account_type')
            <div class="invalid-feedback d-block mt-12">
                {{ $message }}
            </div>
            @enderror
        </section>

        <section style="padding-top: 24px; padding-bottom: 80px;">
            <div class="claude-layout-grid">
                <div>
                    <div class="claude-form-label">-- Form S - New account</div>
                    <h2 class="claude-title small">
                        A few things,<br>then you're <em>in.</em>
                    </h2>

                    <div class="claude-note">
                        <span class="claude-note-icon">1</span>
                        <p class="mb-0">
                            Your account starts here -- this step keeps the existing backend fields:
                            role, full name, email, and captcha when enabled.
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger mt-24">
                            <strong>{{ trans('public.error') }}:</strong>
                            <ul class="mb-0 mt-8">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="claude-part-label">Part 1 - Who you are</div>

                    <div class="claude-form-grid">
                        <div class="form-group claude-span-2">
                            <label class="form-group-label" for="full_name"><span><span class="claude-num">S1</span>{{ trans('auth.full_name') }}</span></label>
                            <input name="full_name" id="full_name" type="text" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" required>
                            @error('full_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="form-group claude-span-2">
                            <label class="form-group-label" for="email"><span><span class="claude-num">S2</span>{{ trans('auth.email') }}</span></label>
                            <input name="email" id="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        @if(!empty(getGeneralSecuritySettings('captcha_for_register')))
                            <div class="claude-span-2">
                                @include('design_1.web.includes.captcha_input')
                            </div>
                        @endif
                    </div>

                    <div class="claude-action-row">
                        <a href="/login" class="claude-link font-14">{{ trans('auth.already_have_an_account') }} {{ trans('auth.login') }}</a>

                        <button type="button" class="js-submit-form-btn btn btn-primary">
                            {{ trans('auth.continue') }}
                        </button>
                    </div>

                    @if(session('email'))
                        <div class="claude-note mt-32">
                            <span class="claude-note-icon">@</span>
                            <div class="flex-1">
                                <p class="font-14 mb-0">
                                    {{ trans('auth.verification_email_sent_to') }} <strong>{{ session('email') }}</strong>
                                </p>
                                <p class="font-12 text-gray-500 mb-0 mt-4">
                                    {{ trans('auth.didnt_receive_email') }}
                                </p>
                            </div>
                            <button type="button" class="btn btn-outline-primary js-resend-email" data-email="{{ session('email') }}">
                                {{ trans('auth.resend_email') }}
                            </button>
                        </div>
                    @endif
                </div>

                <aside class="claude-side-card">
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
                </aside>
            </div>
        </section>

        <section class="claude-closing">
            <div>
                <div class="claude-form-label">-- P.S.</div>
                <h2>No card, <em>really</em>.</h2>
                <p>Your free week starts the moment you sign up. Add a card only if you decide to stay -- no auto-conversion, no dark patterns.</p>
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

    <script>
        $(document).ready(function() {
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
                        btn.prop('disabled', false).html('{{ trans('auth.email_sent') }}');

                        setTimeout(function() {
                            btn.html('{{ trans('auth.resend_email') }}');
                        }, 3000);
                    },
                    error: function() {
                        btn.prop('disabled', false).html('{{ trans('auth.resend_email') }}');
                        alert('{{ trans('auth.failed_to_send_email') }}');
                    }
                });
            });
        });
    </script>
@endpush
