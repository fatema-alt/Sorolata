<?php

declare(strict_types=1);

namespace App\Services\Logistics;

use App\Models\Order;
use App\Services\Admin\AdminSettingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SteadfastCourierService
{
    public const BASE_URL = 'https://portal.steadfast.com.bd/api/v1';

    public function __construct(
        protected AdminSettingService $settingService
    ) {}

    /**
     * Send parcel dispatch order to Steadfast Courier portal.
     *
     * @return array{success: bool, tracking_code: string, consignment_id?: string|int, message?: string}
     */
    public function createOrder(Order $order): array
    {
        $apiKey = (string) $this->settingService->get('steadfast_api_key', '');
        $secretKey = (string) $this->settingService->get('steadfast_secret_key', '');

        // If credentials are blank, run in sandbox/mock mode safely
        if (empty($apiKey) || empty($secretKey)) {
            $mockTracking = 'STDF' . mt_rand(10000000, 99999999);
            Log::info("[Steadfast Courier Sandbox Mock] Order #{$order->order_number} booked with tracking {$mockTracking}");

            return [
                'success' => true,
                'tracking_code' => $mockTracking,
                'consignment_id' => mt_rand(100000, 999999),
                'message' => 'Consignment created successfully (Steadfast Sandbox Mock Mode)',
            ];
        }

        $addressData = $order->shipping_address ?? [];
        $recipientAddress = is_array($addressData)
            ? ($addressData['address'] ?? ($addressData['line_1'] ?? 'Dhaka, Bangladesh'))
            : (string) $addressData;

        $codAmount = (float) ($order->due_amount ?? 0.0);

        try {
            $response = Http::withHeaders([
                'Api-Key' => $apiKey,
                'Secret-Key' => $secretKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post(self::BASE_URL . '/create_order', [
                'invoice' => $order->order_number,
                'recipient_name' => $order->customer_name,
                'recipient_phone' => $order->customer_phone,
                'recipient_address' => $recipientAddress,
                'cod_amount' => $codAmount,
                'note' => 'Sorolata Artisan Piece - Handle with Care (' . $order->order_number . ')',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $consignment = $data['consignment'] ?? [];
                $trackingCode = $consignment['tracking_code'] ?? ($consignment['consignment_id'] ?? 'STDF' . Str::random(8));

                return [
                    'success' => true,
                    'tracking_code' => (string) $trackingCode,
                    'consignment_id' => $consignment['consignment_id'] ?? null,
                    'message' => $data['message'] ?? 'Consignment placed successfully with Steadfast.',
                ];
            }

            Log::error("Steadfast API returned error: " . $response->body());
            return [
                'success' => false,
                'tracking_code' => '',
                'message' => 'Steadfast error: ' . ($response->json('message') ?? $response->body()),
            ];
        } catch (\Throwable $e) {
            Log::error("Steadfast API exception: " . $e->getMessage());
            return [
                'success' => false,
                'tracking_code' => '',
                'message' => 'Failed to reach Steadfast Courier API: ' . $e->getMessage(),
            ];
        }
    }
}
