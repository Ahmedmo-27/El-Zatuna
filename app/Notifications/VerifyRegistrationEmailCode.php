<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyRegistrationEmailCode extends Notification implements ShouldQueue
{
    use Queueable;

    private $verificationCode;
    private $expiresAt;
    private $verificationToken;

    public function __construct($verificationCode, $expiresAt, $verificationToken = null)
    {
        $this->verificationCode = $verificationCode;
        $this->expiresAt = $expiresAt;
        $this->verificationToken = $verificationToken;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');
        $fullName = $notifiable->full_name ?? $notifiable->username ?? 'Student';

        $mailMessage = (new MailMessage)
            ->subject('🎓 ' . trans('auth.email_verification') . ' - ' . $fromName)
            ->from($fromAddress, $fromName)
            ->greeting('Hello ' . $fullName . '! 👋')
            ->line('**Thank you for registering with ' . $fromName . '!**')
            ->line('We\'re excited to have you join our learning community. To complete your registration and start your educational journey, please verify your email address.');

        if ($this->verificationToken) {
            $verifyUrl = url('/register/verify/' . $this->verificationToken);
            $mailMessage->line('**Option 1: Click the verification link**')
                ->action('Verify Email Address', $verifyUrl)
                ->line('**Option 2: Enter the 6-digit code**');
        } else {
            $mailMessage->line('**Your 6-digit verification code is:**');
        }

        return $mailMessage->line('# **' . $this->verificationCode . '**')
            ->line('Enter this code on the registration page to verify your email address.')
            ->line('This verification link and code will expire in **' . $this->expiresAt->diffInMinutes(now()) . ' minutes**.')
            ->line('After verification, you will complete Step 3 (profile details).')
            ->line('If you did not create an account, no further action is required.')
            ->salutation("Best regards,  \n**The " . $fromName . " Team** 🌟");
    }

    public function toArray($notifiable)
    {
        return [
            'verification_code' => $this->verificationCode,
            'expires_at' => $this->expiresAt->toIso8601String(),
        ];
    }
}
