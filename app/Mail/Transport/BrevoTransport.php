<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class BrevoTransport extends AbstractTransport
{
    public function __construct(
        private string $key,
        ?\Psr\EventDispatcher\EventDispatcherInterface $dispatcher = null,
        ?\Psr\Log\LoggerInterface $logger = null
    ) {
        parent::__construct($dispatcher, $logger);
    }

    protected function doSend(SentMessage $message): void
    {
        $raw = $message->getOriginalMessage();
        if (! $raw instanceof Email) {
            throw new TransportException('Brevo transport only supports Symfony\Component\Mime\Email instances.');
        }
        $email = $raw;
        $envelope = $message->getEnvelope();

        $sender = $envelope->getSender();
        $recipients = $envelope->getRecipients();

        $to = [];
        foreach ($recipients as $addr) {
            $to[] = ['email' => $addr->getAddress(), 'name' => $addr->getName() ?: $addr->getAddress()];
        }

        $payload = [
            'sender' => [
                'email' => $sender->getAddress(),
                'name' => $sender->getName() ?: $sender->getAddress(),
            ],
            'to' => $to,
            'subject' => $email->getSubject() ?? '(No subject)',
        ];

        $html = $email->getHtmlBody();
        $text = $email->getTextBody();

        if (is_resource($html)) {
            $html = stream_get_contents($html);
        }
        if (is_resource($text)) {
            $text = stream_get_contents($text);
        }

        if ($html !== null && $html !== '') {
            $payload['htmlContent'] = $html;
        }
        if ($text !== null && $text !== '') {
            $payload['textContent'] = $text;
        }
        if (empty($payload['htmlContent']) && empty($payload['textContent'])) {
            $payload['textContent'] = '(No content)';
        }

        $replyTos = $email->getReplyTo();
        if (count($replyTos) > 0) {
            $r = $replyTos[0];
            $payload['replyTo'] = ['email' => $r->getAddress(), 'name' => $r->getName()];
        }

        $http = Http::withHeaders([
            'api-key' => $this->key,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Allow overriding SSL verify when php.ini curl.cainfo points to a missing/wrong path (e.g. another project's cacert.pem)
        $verify = config('services.brevo.verify', true);
        if ($verify === false || $verify === 'false') {
            $http = $http->withOptions(['verify' => false]);
        } elseif (is_string($verify) && $verify !== '') {
            $http = $http->withOptions(['verify' => $verify]);
        }

        $response = $http->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (! $response->successful()) {
            throw new TransportException(
                sprintf('Brevo API error %d: %s', $response->status(), $response->body()),
                $response->status()
            );
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
