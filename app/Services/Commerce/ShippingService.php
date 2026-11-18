<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Models\ShippingRate;
use App\Models\ShippingZone;
use Illuminate\Support\Collection;

class ShippingService
{
    /**
     * Resolve eligible shipping rates based on geographical location and order attributes.
     *
     * @return Collection<int, ShippingRate>
     */
    public function getAvailableRates(string $location = 'Dhaka', float $weight = 0.5, float $subtotal = 0.0): Collection
    {
        $zones = ShippingZone::with('rates')
            ->where('is_active', true)
            ->get();

        $matchedZone = null;
        $fallbackZone = null;

        foreach ($zones as $zone) {
            if (!empty($zone->regions)) {
                foreach ($zone->regions as $reg) {
                    if (str_contains(strtolower($location), strtolower($reg))) {
                        $matchedZone = $zone;
                        break 2;
                    }
                }
            } else {
                $fallbackZone = $zone;
            }
        }

        $activeZone = $matchedZone ?? $fallbackZone ?? $zones->first();

        if (!$activeZone) {
            return collect();
        }

        return $activeZone->rates()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Calculate final shipping fee for a chosen rate, weight, and subtotal.
     */
    public function calculateShippingFee(?int $rateId, float $weight = 0.5, float $subtotal = 0.0): float
    {
        if (!$rateId) {
            return 80.0; // Default domestic courier baseline
        }

        $rate = ShippingRate::find($rateId);
        if (!$rate) {
            return 80.0;
        }

        return $rate->calculateFee($weight, $subtotal);
    }
}
