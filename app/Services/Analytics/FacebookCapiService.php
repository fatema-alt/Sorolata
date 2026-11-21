<?php

declare(strict_types=1);

namespace App\Services\Analytics;

use App\Services\Admin\AdminSettingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookCapiService
{
    public function __construct(
        protected AdminSettingService $settingService
    ) {}

    public function isEnabled(): bool
    {
        $trackingEnabled = (bool) $this->settingService->get('tracking_enabled', '1');
        $capiEnabled = (bool) $this->settingService->get('fb_capi_enabled', '0');
        $pixelId = (string) $this->settingService->get('fb_pixel_id', '');
        $token = (string) $this->settingService->get('fb_capi_token', '');

        return $trackingEnabled && $capiEnabled && !empty($pixelId) && !empty($token);
    }

    /**
     * Send server-side event to Meta Conversions API (Graph API)
     *
     * @param string $eventName e.g. 'Purchase', 'InitiateCheckout', 'AddToCart', 'ViewContent', 'PageView'
     * @param array<string, mixed> $customData
     * @param array<string, mixed> $userData
     * @param string|null $eventId
     * @return bool
     */
    public function sendEvent(
        string $eventName,
        array $customData = [],
        array $userData = [],
        ?string $eventId = null
    ): bool {
        if (! $this->isEnabled()) {
            return false;
        }

        $pixelId = trim((string) $this->settingService->get('fb_pixel_id', ''));
        $token = trim((string) $this->settingService->get('fb_capi_token', ''));
        $testCode = trim((string) $this->settingService->get('fb_capi_test_code', ''));

        $request = request();

        // 1. Build User Data with First-Party Cookies & Normalization
        $fbp = $request?->cookie('_fbp') ?? ($userData['fbp'] ?? null);
        $fbc = $request?->cookie('_fbc') ?? ($userData['fbc'] ?? null);

        $normalizedUserData = [
            'client_ip_address' => $request?->ip() ?? '127.0.0.1',
            'client_user_agent' => $request?->userAgent() ?? 'Sorolata Storefront Client',
        ];

        if (!empty($fbp)) {
            $normalizedUserData['fbp'] = $fbp;
        }
        if (!empty($fbc)) {
            $normalizedUserData['fbc'] = $fbc;
        }

        // Hash PII using SHA256 as required by Meta CAPI specification
        if (!empty($userData['email'])) {
            $normalizedUserData['em'] = hash('sha256', strtolower(trim((string) $userData['email'])));
        }
        if (!empty($userData['phone'])) {
            $phoneClean = preg_replace('/[^0-9]/', '', (string) $userData['phone']);
            if (!empty($phoneClean)) {
                $normalizedUserData['ph'] = hash('sha256', $phoneClean);
            }
        }
        if (!empty($userData['first_name'])) {
            $normalizedUserData['fn'] = hash('sha256', strtolower(trim((string) $userData['first_name'])));
        }
        if (!empty($userData['last_name'])) {
            $normalizedUserData['ln'] = hash('sha256', strtolower(trim((string) $userData['last_name'])));
        }
        if (!empty($userData['city'])) {
            $normalizedUserData['ct'] = hash('sha256', strtolower(trim((string) $userData['city'])));
        }
        if (!empty($userData['country'])) {
            $normalizedUserData['country'] = hash('sha256', strtolower(trim((string) $userData['country'])));
        } else {
            $normalizedUserData['country'] = hash('sha256', 'bd');
        }

        // 2. Build Event Payload
        $eventPayload = [
            'event_name' => $eventName,
            'event_time' => time(),
            'event_id' => $eventId ?? ('evt_' . time() . '_' . bin2hex(random_bytes(4))),
            'event_source_url' => $request?->fullUrl() ?? url('/'),
            'action_source' => 'website',
            'user_data' => $normalizedUserData,
            'custom_data' => $customData,
        ];

        $postData = [
            'data' => [$eventPayload],
            'access_token' => $token,
        ];

        if (!empty($testCode)) {
            $postData['test_event_code'] = $testCode;
        }

        try {
            $url = "https://graph.facebook.com/v19.0/{$pixelId}/events";
            $response = Http::timeout(5)->asJson()->post($url, $postData);

            if ($response->successful()) {
                Log::info("Meta CAPI [{$eventName}] dispatched successfully. EventID: {$eventPayload['event_id']}");
                return true;
            } else {
                Log::warning("Meta CAPI [{$eventName}] failed with status: {$response->status()}", [
                    'response' => $response->json(),
                ]);
                return false;
            }
        } catch (\Throwable $e) {
            Log::error("Meta CAPI exception for [{$eventName}]: " . $e->getMessage());
            return false;
        }
    }
}
