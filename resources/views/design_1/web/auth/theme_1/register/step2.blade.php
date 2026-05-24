@extends('design_1.web.auth.theme_1.layout')

@section('page_content')
    <div class="claude-strip">
        <span>-- Access / Verify</span>
        <span>- Code expires in 60 minutes -</span>
        <span>File n° 09 - 02B</span>
    </div>

    <div class="claude-layout-grid">
        <div>
            <div class="claude-form-label">-- Form V - Email code</div>

            <h1 class="claude-title">
                Check your<br>
                <em>email.</em> Then<br>
                prove it's yours.
            </h1>

            <p class="claude-copy">
                We sent a six-digit code to <strong>{{ $email ?? 'your email address' }}</strong>.
                Enter it below and the existing registration flow will move you to the final profile step.
            </p>

            <div class="claude-note">
                <span class="claude-note-icon">@</span>
                <p class="mb-0">
                    {{ trans('auth.step_2_of_3') }} - {{ trans('auth.email_verification') }}.
                    {{ trans('auth.code_expires_in_60_minutes') }}
                </p>
            </div>

            <form id="verificationCodeForm" class="claude-form-stack mt-32">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label class="form-group-label">
                        <span><span class="claude-num">V1</span>{{ trans('auth.enter_verification_code') }}</span>
                    </label>

                    <div class="ez-auth-code-fields" style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 8px;">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                        <input type="text" maxlength="1" class="form-control auth-verification-code-field text-center font-24 font-weight-bold" style="height: 72px; padding: 8px !important; border: 1px solid var(--ez-line) !important; border-radius: 12px !important;" pattern="[0-9]" inputmode="numeric" autocomplete="off">
                    </div>
                </div>

                <div id="verificationError" class="alert alert-danger d-none mb-0"></div>

                <div class="claude-action-row">
                    <button type="button" class="btn btn-outline-primary js-resend-code" data-email="{{ $email }}">
                        {{ trans('auth.resend_verification_code') }}
                    </button>

                    <button type="submit" class="btn btn-primary js-submit-verification-form-btn">
                        {{ trans('auth.verify_email') }}
                    </button>
                </div>
            </form>

            <div class="d-flex-center flex-column text-center mt-32">
                <span class="text-gray-500">{{ trans('auth.wrong_email_address') }}</span>
                <a href="/register" class="claude-link mt-8">{{ trans('auth.start_over') }}</a>
            </div>
        </div>

        <aside class="claude-side-card">
            <div class="claude-side-kicker">-- Why this step</div>
            <h2 class="claude-side-title">One code,<br>then you're close.</h2>

            <div class="claude-side-list">
                <div class="claude-side-item">
                    <div class="claude-side-num">01</div>
                    <div>
                        <div class="claude-side-item-title">Pending account protected</div>
                        <div class="claude-side-item-text">The code maps to the pending user created in step one.</div>
                    </div>
                </div>

                <div class="claude-side-item">
                    <div class="claude-side-num">02</div>
                    <div>
                        <div class="claude-side-item-title">No route changes</div>
                        <div class="claude-side-item-text">Verification still posts to /register/step/2 through the existing AJAX handler.</div>
                    </div>
                </div>

                <div class="claude-side-item">
                    <div class="claude-side-num">03</div>
                    <div>
                        <div class="claude-side-item-title">Token for final details</div>
                        <div class="claude-side-item-text">Success redirects with the same verification token for step three.</div>
                    </div>
                </div>
            </div>

            <div class="claude-side-badge">Email verification - 60 min</div>
        </aside>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        $(document).ready(function() {
            $('.auth-verification-code-field').first().focus();

            $('#verificationCodeForm').on('submit', function(e) {
                e.preventDefault();
                var code = '';
                $('.auth-verification-code-field').each(function() {
                    code += $(this).val();
                });
                if (code.length !== 6) {
                    $('#verificationError').removeClass('d-none').text('{{ trans('auth.please_enter_all_6_digits') }}');
                    return;
                }
                $('#verificationError').addClass('d-none');
                var $btn = $('.js-submit-verification-form-btn');
                $btn.addClass('loadingbar').prop('disabled', true);

                $.ajax({
                    url: '/register/step/2',
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        email: $('input[name="email"]').val(),
                        verification_code: code
                    },
                    success: function(res) {
                        if (res.redirect) {
                            window.location.href = res.redirect;
                        } else {
                            window.location.href = '/register/step/3?token=' + (res.verification_token || '') + '&verified=true';
                        }
                    },
                    error: function(xhr) {
                        $btn.removeClass('loadingbar').prop('disabled', false);
                        var msg = '{{ trans('auth.invalid_verification_code') }}';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errs = xhr.responseJSON.errors.verification_code;
                            if (errs && errs[0]) msg = errs[0];
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        $('#verificationError').removeClass('d-none').text(msg);
                        $('.auth-verification-code-field').val('').first().focus();
                    }
                });
            });

            $('.auth-verification-code-field').on('input', function() {
                var allFilled = true;
                $('.auth-verification-code-field').each(function() {
                    if ($(this).val() === '') allFilled = false;
                });
                if (allFilled) {
                    setTimeout(function() { $('#verificationCodeForm').submit(); }, 300);
                }
            });

            $('.auth-verification-code-field').first().on('paste', function(e) {
                e.preventDefault();
                var pasted = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
                var digits = pasted.replace(/\D/g, '').substring(0, 6);
                $('.auth-verification-code-field').each(function(i) {
                    $(this).val(digits[i] || '');
                });
                if (digits.length === 6) {
                    setTimeout(function() { $('#verificationCodeForm').submit(); }, 200);
                }
            });

            $('.js-resend-code').on('click', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var email = $btn.data('email');
                $btn.prop('disabled', true);
                $.ajax({
                    url: '/api/v1/auth/resend-verification',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ email: email }),
                    headers: {
                        'X-CSRF-TOKEN': ($('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    success: function() {
                        if (typeof showToast === 'function') {
                            showToast('success', '{{ trans('public.request_success') }}', '{{ trans('auth.code_sent_successfully') }}');
                        }
                        $('.auth-verification-code-field').val('').first().focus();
                    },
                    error: function() {
                        if (typeof showToast === 'function') {
                            showToast('error', '{{ trans('public.error') }}', '{{ trans('auth.failed_to_send_code') }}');
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
