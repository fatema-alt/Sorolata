<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\AdminNotification;
use App\Models\Announcement;
use App\Models\DesignToken;
use App\Services\Admin\AdminSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SystemApiController extends BaseApiController
{
    public function __construct(
        protected AdminSettingService $settingService
    ) {}

    /**
     * Get global platform configuration, currencies, and feature flags
     */
    public function config(): JsonResponse
    {
        $announcement = Announcement::where('is_active', true)->first();
        $designTokens = DesignToken::all()->pluck('token_value', 'token_key');

        return $this->respondWithSuccess([
            'store' => [
                'name' => $this->settingService->get('store_name', 'Sorolata Commerce Platform'),
                'contact_email' => $this->settingService->get('contact_email', 'curator@sorolata.com'),
                'contact_phone' => $this->settingService->get('contact_phone', '+880 1711 000000'),
                'store_address' => $this->settingService->get('store_address', 'Dhaka Central Heritage Atelier, Bangladesh'),
            ],
            'supported_currencies' => [
                ['code' => 'BDT', 'symbol' => '৳', 'name' => 'Bangladeshi Taka', 'exchange_rate' => 1.0],
                ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar', 'exchange_rate' => 0.0084],
                ['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound', 'exchange_rate' => 0.0066],
                ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro', 'exchange_rate' => 0.0078],
            ],
            'supported_languages' => [
                ['code' => 'en', 'name' => 'English'],
                ['code' => 'bn', 'name' => 'বাংলা'],
            ],
            'feature_flags' => [
                'wishlist' => true,
                'compare' => true,
                'visual_builder' => true,
                'reviews' => true,
                'coupons' => true,
                'gift_wrap' => true,
                'district_heritage_module' => true,
                'cross_border_shipping' => true,
            ],
            'announcement' => $announcement ? [
                'text' => $announcement->text,
                'bn_text' => $announcement->bn_text,
                'link_url' => $announcement->link_url,
                'bg_color' => $announcement->bg_color,
                'text_color' => $announcement->text_color,
            ] : null,
            'design_tokens' => $designTokens,
        ], 'System configuration retrieved successfully.');
    }

    /**
     * Get administrative or customer notifications
     */
    public function notifications(Request $request): JsonResponse
    {
        $notifications = AdminNotification::latest()
            ->limit(20)
            ->get();

        return $this->respondWithSuccess(
            $notifications,
            'Notifications retrieved successfully.'
        );
    }
}
