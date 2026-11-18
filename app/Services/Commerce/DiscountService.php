<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Models\Discount;

class DiscountService
{
    /**
     * Look up and validate a promotional coupon code.
     */
    public function validateCoupon(string $code, float $subtotal): ?Discount
    {
        $sanitized = strtoupper(trim($code));
        if (empty($sanitized)) {
            return null;
        }

        $discount = Discount::where('code', $sanitized)
            ->where('is_active', true)
            ->first();

        if (!$discount || !$discount->isValidForSubtotal($subtotal)) {
            return null;
        }

        return $discount;
    }

    /**
     * Compute discount amount against a given subtotal.
     *
     * @return array{discount: ?Discount, amount: float, error: ?string}
     */
    public function applyDiscount(string $code, float $subtotal): array
    {
        $discount = $this->validateCoupon($code, $subtotal);

        if (!$discount) {
            return [
                'discount' => null,
                'amount' => 0.0,
                'error' => 'Coupon code is invalid or does not meet the minimum order requirement.',
            ];
        }

        $amount = $discount->calculateDiscount($subtotal);

        return [
            'discount' => $discount,
            'amount' => $amount,
            'error' => null,
        ];
    }
}
