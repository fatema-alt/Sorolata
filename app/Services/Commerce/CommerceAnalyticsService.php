<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class CommerceAnalyticsService
{
    protected const CACHE_PREFIX = 'sorolata_analytics_';

    public function recordProductView(int $productId, ?int $userId = null): void
    {
        Cache::increment(self::CACHE_PREFIX . 'views_total', 1);
        Cache::increment(self::CACHE_PREFIX . "views_prod_{$productId}", 1);
    }

    public function recordAddToCart(int $productId, int $qty = 1): void
    {
        Cache::increment(self::CACHE_PREFIX . 'add_to_cart_total', $qty);
    }

    public function recordCheckoutStarted(float $subtotal): void
    {
        Cache::increment(self::CACHE_PREFIX . 'checkout_started_total', 1);
    }

    public function recordPurchase(Order $order): void
    {
        Cache::increment(self::CACHE_PREFIX . 'purchases_total', 1);
        $currRevenue = (float) Cache::get(self::CACHE_PREFIX . 'revenue_total', 0.0);
        Cache::put(self::CACHE_PREFIX . 'revenue_total', $currRevenue + $order->total_amount, now()->addDays(30));
    }

    /**
     * @return array{views: int, add_to_cart: int, checkouts: int, orders: int, conversion_rate: float, revenue: float}
     */
    public function getMetrics(): array
    {
        $views = (int) Cache::get(self::CACHE_PREFIX . 'views_total', 0);
        $atc = (int) Cache::get(self::CACHE_PREFIX . 'add_to_cart_total', 0);
        $checkouts = (int) Cache::get(self::CACHE_PREFIX . 'checkout_started_total', 0);
        $orders = (int) Cache::get(self::CACHE_PREFIX . 'purchases_total', 0);
        $revenue = (float) Cache::get(self::CACHE_PREFIX . 'revenue_total', 0.0);

        $conversionRate = $views > 0 ? round(($orders / $views) * 100, 2) : 0.0;

        return [
            'views' => $views,
            'add_to_cart' => $atc,
            'checkouts' => $checkouts,
            'orders' => $orders,
            'conversion_rate' => $conversionRate,
            'revenue' => $revenue,
        ];
    }
}
