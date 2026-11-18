<?php

declare(strict_types=1);

namespace App\Services\Webhook;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookDispatcherService
{
    /**
     * Dispatch an outbound webhook event with HMAC signature
     */
    public function dispatch(string $event, array $payload, ?string $webhookUrl = null, ?string $secret = null): bool
    {
        $targetUrl = $webhookUrl ?? config('services.webhooks.default_url');
        if (empty($targetUrl)) {
            return false;
        }

        $secret = $secret ?? config('services.webhooks.secret', 'sorolata_webhook_secret');
        $jsonPayload = json_encode([
            'event' => $event,
            'timestamp' => now()->toIso8601String(),
            'payload' => $payload,
        ]);

        $signature = hash_hmac('sha256', (string) $jsonPayload, (string) $secret);

        try {
            $response = Http::timeout(5)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Sorolata-Event' => $event,
                    'X-Sorolata-Signature' => $signature,
                ])
                ->post($targetUrl, json_decode((string) $jsonPayload, true));

            return $response->successful();
        } catch (\Throwable $e) {
            Log::warning("Webhook dispatch failed for event [{$event}]: " . $e->getMessage());
            return false;
        }
    }
}
