<?php

declare(strict_types=1);

namespace App\Services\Notification;

use App\Models\Order;
use App\Models\OrderTimeline;
use App\Services\Admin\AdminSettingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function __construct(
        protected AdminSettingService $settingService
    ) {}

    /**
     * Send arbitrary SMS message to recipient phone number using configured gateway.
     */
    public function send(string $phone, string $message): bool
    {
        $enabled = (bool) ($this->settingService->get('sms_enabled', '1') === '1');
        if (!$enabled) {
            Log::info("SMS sending disabled in settings. Skipping message to {$phone}: {$message}");
            return false;
        }

        // Clean BD phone number (e.g. 017XXXXXXXX -> 88017XXXXXXXX)
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '88' . $cleanPhone;
        } elseif (!str_starts_with($cleanPhone, '880') && strlen($cleanPhone) === 10) {
            $cleanPhone = '880' . $cleanPhone;
        }

        $provider = (string) $this->settingService->get('sms_provider', 'log');
        $apiKey = (string) $this->settingService->get('sms_api_key', '');
        $senderId = (string) $this->settingService->get('sms_sender_id', 'Sorolata');

        try {
            switch (strtolower($provider)) {
                case 'greenweb':
                    if (empty($apiKey)) {
                        Log::info("[Greenweb SMS Mock] To: {$cleanPhone} | Msg: {$message}");
                        return true;
                    }
                    $response = Http::timeout(10)->post('http://api.greenweb.com.bd/api.php', [
                        'token' => $apiKey,
                        'to' => $cleanPhone,
                        'message' => $message,
                    ]);
                    return $response->successful();

                case 'elitbuzz':
                    if (empty($apiKey)) {
                        Log::info("[Elitbuzz SMS Mock] To: {$cleanPhone} | Msg: {$message}");
                        return true;
                    }
                    $response = Http::timeout(10)->post('https://msg.elitbuzz-bd.com/smsapi', [
                        'api_key' => $apiKey,
                        'type' => 'text',
                        'contacts' => $cleanPhone,
                        'senderid' => $senderId,
                        'msg' => $message,
                    ]);
                    return $response->successful();

                case 'mim_sms':
                    if (empty($apiKey)) {
                        Log::info("[MimSMS Mock] To: {$cleanPhone} | Msg: {$message}");
                        return true;
                    }
                    $response = Http::timeout(10)->post('https://api.mimsms.com/api/SmsSending/SendSingleSms', [
                        'ApiKey' => $apiKey,
                        'SenderId' => $senderId,
                        'Message' => $message,
                        'MobileNumber' => $cleanPhone,
                    ]);
                    return $response->successful();

                case 'log':
                default:
                    Log::info("[SMS Log Dispatch] Provider: {$provider} | To: {$cleanPhone} | Msg: {$message}");
                    return true;
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send SMS to {$phone}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send Order Placed notification SMS.
     */
    public function sendOrderPlacedSms(Order $order): bool
    {
        $defaultTemplate = "Dear {name}, your Sorolata order #{order_number} (৳{total}) is placed successfully! Track your heirloom: {tracking_url}";
        $template = (string) $this->settingService->get('sms_order_placed_template', $defaultTemplate);

        $msg = $this->interpolateTemplate($template, $order);
        $sent = $this->send($order->customer_phone, $msg);

        if ($sent) {
            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => $order->status->value,
                'actor_type' => 'system',
                'note' => "Order placement SMS notification dispatched to {$order->customer_phone}.",
                'customer_notified' => true,
            ]);
        }

        return $sent;
    }

    /**
     * Send Order Confirmed & Payment Verified SMS.
     */
    public function sendOrderConfirmedSms(Order $order): bool
    {
        $defaultTemplate = "Dear {name}, payment for your Sorolata order #{order_number} is verified! Atelier curation started. Track: {tracking_url}";
        $template = (string) $this->settingService->get('sms_order_confirmed_template', $defaultTemplate);

        $msg = $this->interpolateTemplate($template, $order);
        $sent = $this->send($order->customer_phone, $msg);

        if ($sent) {
            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => $order->status->value,
                'actor_type' => 'system',
                'note' => "Payment confirmation SMS dispatched to {$order->customer_phone}.",
                'customer_notified' => true,
            ]);
        }

        return $sent;
    }

    /**
     * Send Order Dispatched / Shipped SMS with Courier details.
     */
    public function sendOrderShippedSms(Order $order): bool
    {
        $defaultTemplate = "Dear {name}, order #{order_number} has been dispatched via {courier}. Tracking: {tracking_number}. Track live: {tracking_url}";
        $template = (string) $this->settingService->get('sms_order_shipped_template', $defaultTemplate);

        $msg = $this->interpolateTemplate($template, $order);
        $sent = $this->send($order->customer_phone, $msg);

        if ($sent) {
            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => $order->status->value,
                'actor_type' => 'system',
                'note' => "Courier dispatch SMS dispatched to {$order->customer_phone}.",
                'customer_notified' => true,
            ]);
        }

        return $sent;
    }

    protected function interpolateTemplate(string $template, Order $order): string
    {
        $replacements = [
            '{name}' => explode(' ', trim($order->customer_name))[0] ?? $order->customer_name,
            '{full_name}' => $order->customer_name,
            '{order_number}' => $order->order_number,
            '{total}' => number_format((float) $order->total_amount, 0),
            '{courier}' => $order->courier_name ?? 'Courier Partner',
            '{tracking_number}' => $order->tracking_number ?? 'In Transit',
            '{tracking_url}' => url('/track-order?search=' . urlencode($order->order_number)),
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
