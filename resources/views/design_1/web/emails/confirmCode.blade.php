@extends('design_1.web.emails.layout')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div style="text-align: center; padding: 20px 0;">
            <h1 class="h1" style="color: #333333; font-size: 24px; font-weight: 600; margin-bottom: 20px;">
                {{ $confirm['title'] }}
            </h1>
        </div>

        <div style="background: #f8f9fa; border-radius: 8px; padding: 25px; margin: 20px 0;">
            <p style="color: #505050; font-size: 15px; line-height: 1.6; margin-bottom: 20px; text-align: center;">
                {!! nl2br($confirm['message']) !!}
            </p>

            <div style="background: #ffffff; border: 2px solid #43d477; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                <p style="color: #666; font-size: 13px; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 1px; font-weight: 500;">
                    {{ trans('auth.verification_code') }}
                </p>
                <p class="code" style="color: #43d477; font-size: 36px; font-weight: bold; margin: 0; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                    {{ $confirm['code'] }}
                </p>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <p style="color: #999; font-size: 13px; line-height: 1.5; margin: 0;">
                    <strong>{{ trans('auth.code_expires_in') }}:</strong> {{ trans('auth.one_hour') }}
                </p>
            </div>
        </div>

        <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="color: #856404; font-size: 14px; margin: 0; line-height: 1.5;">
                <strong>⚠️ {{ trans('auth.security_notice') }}:</strong> {{ trans('notification.email_ignore_msg') }}
            </p>
        </div>

        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <p style="color: #999; font-size: 12px; margin: 0;">
                {{ trans('auth.having_trouble') }} 
                <a href="mailto:{{ $generalSettings['site_email'] ?? env('MAIL_FROM_ADDRESS') }}" style="color: #43d477; text-decoration: none;">
                    {{ trans('auth.contact_support') }}
                </a>
            </p>
        </div>
    </td>
@endsection
