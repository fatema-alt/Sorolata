<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Models\TaxClass;

class TaxService
{
    /**
     * Compute applicable VAT / Tax for an order subtotal.
     */
    public function calculateTax(float $subtotal, string $taxClassName = 'standard'): float
    {
        $taxClass = TaxClass::where('name', 'like', "%{$taxClassName}%")
            ->where('is_active', true)
            ->first();

        if (!$taxClass || $taxClass->rate <= 0) {
            return 0.0;
        }

        return round($subtotal * ($taxClass->rate / 100), 2);
    }
}
