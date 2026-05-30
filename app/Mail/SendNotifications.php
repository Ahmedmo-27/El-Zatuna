<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifications extends Mailable
{
    use Queueable, SerializesModels;

    public $notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $notification = $this->notification;

        if (!empty($notification)) {
            $generalSettings = getGeneralSettings();

            return $this->subject($notification['title'])
                ->from(getMailSenderAddress(), getMailSenderName())
                ->cc(!empty($notification['cc']) ? $notification['cc'] : [])
                ->view('design_1.web.emails.notification', [
                    'notification' => $notification,
                    'generalSettings' => $generalSettings
                ]);
        }
    }
}
