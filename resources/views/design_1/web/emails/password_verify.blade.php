@extends('design_1.web.emails.layout')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div style="text-align: center; padding: 20px 0;">
            <h1 class="h1" style="color: #333333; font-size: 24px; font-weight: 600; margin-bottom: 20px;">
                {{ trans('auth.reset_your_password') }}
            </h1>
        </div>

        <div style="background: #f8f9fa; border-radius: 8px; padding: 25px; margin: 20px 0;">
            <p style="color: #505050; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
                You are receiving this email because we received a password reset request for your account <strong>({{ $email }})</strong>.
            </p>

            <p style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 25px;">
                Click the button below to reset your password. This link will expire in 60 minutes.
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/reset-password/'.$token.'?email='.$email) }}" 
                   style="display: inline-block; padding: 14px 40px; background: #43d477; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; transition: background 200ms ease 0s;">
                    {{ trans('auth.reset_password') }}
                </a>
            </div>

            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                <p style="color: #999; font-size: 13px; line-height: 1.5; margin: 0;">
                    Or copy and paste this URL into your browser:
                </p>
                <p style="color: #43d477; font-size: 12px; word-break: break-all; margin: 10px 0 0 0; padding: 10px; background: #fff; border-radius: 4px; border: 1px solid #e0e0e0;">
                    {{ url('/reset-password/'.$token.'?email='.$email) }}
                </p>
            </div>
        </div>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="color: #856404; font-size: 14px; margin: 0; line-height: 1.5;">
                <strong>⚠️ Security Notice:</strong> {{ trans('notification.email_ignore_msg') }}
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <p style="color: #999; font-size: 13px; line-height: 1.5; margin: 0;">
                This password reset link will expire in <strong>60 {{ trans('public.minutes') }}</strong>
            </p>
            @if(!empty($generalSettings['site_email']))
            <p style="color: #999; font-size: 12px; margin: 10px 0 0 0;">
                Having trouble? Contact us at 
                <a href="mailto:{{ $generalSettings['site_email'] }}" style="color: #43d477; text-decoration: none;">
                    {{ $generalSettings['site_email'] }}
                </a>
            </p>
            @endif
        </div>
    </td>
@endsection
