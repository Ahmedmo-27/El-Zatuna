<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle Brevo (Sendinblue) transactional webhooks.
     * Logs headers, raw payload and parsed JSON to a dedicated log channel.
     */
    public function brevo(Request $request)
    {
        $raw = $request->getContent();
        $payload = null;
        try {
            $payload = $request->json()->all();
        } catch (\Throwable $e) {
            $payload = $request->all();
        }

        Log::channel('brevo')->info('brevo.webhook.received', [
            'method' => $request->method(),
            'path' => $request->path(),
            'headers' => $request->headers->all(),
            'payload' => $payload,
            'raw' => $raw,
            'ip' => $request->ip(),
        ]);

        return response()->json(['success' => true]);
    }
}
