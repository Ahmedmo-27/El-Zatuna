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

    public function __construct($verificationCode, $expiresAt)
    {
        $this->verificationCode = $verificationCode;
        $this->expiresAt = $expiresAt;
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

        return (new MailMessage)
            ->subject('🎓 ' . trans('auth.email_verification') . ' - ' . $fromName)
            ->from($fromAddress, $fromName)
            ->greeting('Hello ' . $fullName . '! 👋')
            ->line('**Thank you for registering with ' . $fromName . '!**')
            ->line('We\'re excited to have you join our learning community. To complete your registration and start your educational journey, please verify your email address.')
            ->line('**Your 6-digit verification code is:**')
            ->line('# **' . $this->verificationCode . '**')
            ->line('Enter this code on the registration page to verify your email address.')
            ->line('This verification code will expire in **' . $this->expiresAt->diffInMinutes(now()) . ' minutes**.')
            ->line('If you close the registration page, you can return later to complete Step 3 (profile details) using the button below (as long as your verification session is still active).')
            ->action('Continue registration (Step 3)', url('/register/step/3'))
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
