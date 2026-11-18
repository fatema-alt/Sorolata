<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'sorolata_cart_v2';

    public function __construct(
        protected DiscountService $discountService,
        protected ShippingService $shippingService,
        protected TaxService $taxService
    ) {}

    /**
     * Add a product or variant to the cart.
     */
    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1, array $options = []): array
    {
        $product = Product::with(['artisan', 'media'])->findOrFail($productId);
        $variant = $variantId ? ProductVariant::where('product_id', $productId)->findOrFail($variantId) : null;

        $cart = $this->getRawState();
        $itemKey = $this->generateItemKey($productId, $variantId, $options);

        $unitPrice = $variant ? $variant->price : $product->effective_price;
        $sku = $variant ? $variant->sku : $product->sku;
        $title = $product->title;
        $variantTitle = $variant ? $variant->title : null;
        $imageUrl = $variant?->image_url ?? $product->media->first()?->file_path ?? '/images/placeholders/jamdani-hero.jpg';
        $weight = $variant?->weight ?? $product->weight ?? 0.5;

        if (isset($cart['items'][$itemKey])) {
            $cart['items'][$itemKey]['quantity'] += $quantity;
            $cart['items'][$itemKey]['subtotal'] = $cart['items'][$itemKey]['quantity'] * $unitPrice;
        } else {
            $cart['items'][$itemKey] = [
                'key' => $itemKey,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'title' => $title,
                'variant_title' => $variantTitle,
                'sku' => $sku,
                'unit_price' => (float) $unitPrice,
                'quantity' => $quantity,
                'subtotal' => (float) ($quantity * $unitPrice),
                'image_url' => $imageUrl,
                'weight' => (float) $weight,
                'options' => $options,
            ];
        }

        $this->saveState($cart);

        return $cart['items'][$itemKey];
    }

    /**
     * Remove an item from the cart.
     */
    public function removeItem(string $itemKey): void
    {
        $cart = $this->getRawState();
        unset($cart['items'][$itemKey]);
        $this->saveState($cart);
    }

    /**
     * Update quantity of an item.
     */
    public function updateQuantity(string $itemKey, int $quantity): void
    {
        $cart = $this->getRawState();

        if ($quantity <= 0) {
            unset($cart['items'][$itemKey]);
        } elseif (isset($cart['items'][$itemKey])) {
            $cart['items'][$itemKey]['quantity'] = $quantity;
            $cart['items'][$itemKey]['subtotal'] = $quantity * $cart['items'][$itemKey]['unit_price'];
        }

        $this->saveState($cart);
    }

    /**
     * Move item to Save For Later.
     */
    public function saveForLater(string $itemKey): void
    {
        $cart = $this->getRawState();

        if (isset($cart['items'][$itemKey])) {
            $item = $cart['items'][$itemKey];
            unset($cart['items'][$itemKey]);
            $cart['saved_for_later'][$itemKey] = $item;
            $this->saveState($cart);
        }
    }

    /**
     * Move item from Save For Later back into cart.
     */
    public function moveToCart(string $itemKey): void
    {
        $cart = $this->getRawState();

        if (isset($cart['saved_for_later'][$itemKey])) {
            $item = $cart['saved_for_later'][$itemKey];
            unset($cart['saved_for_later'][$itemKey]);
            $cart['items'][$itemKey] = $item;
            $this->saveState($cart);
        }
    }

    /**
     * Apply coupon code.
     */
    public function applyCoupon(string $code): array
    {
        $cart = $this->getRawState();
        $subtotal = $this->calculateSubtotal($cart['items']);

        $res = $this->discountService->applyDiscount($code, $subtotal);

        if ($res['discount']) {
            $cart['coupon'] = [
                'code' => $res['discount']->code,
                'name' => $res['discount']->name,
                'type' => $res['discount']->type->value,
                'amount' => $res['amount'],
            ];
            $this->saveState($cart);
        }

        return $res;
    }

    /**
     * Remove applied coupon.
     */
    public function removeCoupon(): void
    {
        $cart = $this->getRawState();
        $cart['coupon'] = null;
        $this->saveState($cart);
    }

    /**
     * Configure Gift Wrapping & Archival Message.
     */
    public function setGiftWrap(
        bool $enabled,
        ?string $recipient = null,
        ?string $message = null,
        bool $receipt = false
    ): void {
        $cart = $this->getRawState();
        $cart['gift'] = [
            'enabled' => $enabled,
            'fee' => $enabled ? 250.0 : 0.0, // ৳250 Royal Silk Wrap & Calligraphy Card
            'recipient' => $recipient,
            'message' => $message,
            'receipt' => $receipt,
        ];
        $this->saveState($cart);
    }

    /**
     * Set selected shipping rate.
     */
    public function setShippingRate(?int $shippingRateId): void
    {
        $cart = $this->getRawState();
        $cart['shipping_rate_id'] = $shippingRateId;
        $this->saveState($cart);
    }

    /**
     * Set customer order note.
     */
    public function setOrderNote(?string $note): void
    {
        $cart = $this->getRawState();
        $cart['note'] = $note;
        $this->saveState($cart);
    }

    /**
     * Retrieve complete computed cart totals.
     */
    public function getTotals(): array
    {
        $cart = $this->getRawState();
        $items = $cart['items'];
        $subtotal = $this->calculateSubtotal($items);
        $totalWeight = $this->calculateTotalWeight($items);

        // Discount
        $discountAmount = 0.0;
        if (!empty($cart['coupon'])) {
            $res = $this->discountService->applyDiscount($cart['coupon']['code'], $subtotal);
            if ($res['discount']) {
                $discountAmount = $res['amount'];
            } else {
                $cart['coupon'] = null;
                $this->saveState($cart);
            }
        }

        // Gift Wrap
        $giftWrapFee = (!empty($cart['gift']['enabled']) && $cart['gift']['enabled']) ? ($cart['gift']['fee'] ?? 250.0) : 0.0;

        // Shipping
        $shippingRateId = $cart['shipping_rate_id'] ?? null;
        $shippingFee = $this->shippingService->calculateShippingFee($shippingRateId, $totalWeight, $subtotal);

        // Tax (VAT)
        $taxAmount = $this->taxService->calculateTax($subtotal, 'standard');

        $grandTotal = max(0, ($subtotal - $discountAmount) + $shippingFee + $taxAmount + $giftWrapFee);

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'shipping_fee' => round($shippingFee, 2),
            'tax_amount' => round($taxAmount, 2),
            'gift_wrap_fee' => round($giftWrapFee, 2),
            'grand_total' => round($grandTotal, 2),
            'items_count' => array_sum(array_column($items, 'quantity')),
            'total_weight' => round($totalWeight, 3),
        ];
    }

    /**
     * Retrieve full state for UI and APIs.
     */
    public function getContents(): array
    {
        $cart = $this->getRawState();
        $totals = $this->getTotals();

        return [
            'items' => array_values($cart['items']),
            'saved_for_later' => array_values($cart['saved_for_later']),
            'coupon' => $cart['coupon'],
            'gift' => $cart['gift'],
            'shipping_rate_id' => $cart['shipping_rate_id'],
            'note' => $cart['note'],
            'totals' => $totals,
        ];
    }

    /**
     * Reset/clear the cart after successful checkout.
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    protected function getRawState(): array
    {
        return Session::get(self::SESSION_KEY, [
            'items' => [],
            'saved_for_later' => [],
            'coupon' => null,
            'gift' => ['enabled' => false, 'fee' => 0.0, 'recipient' => null, 'message' => null, 'receipt' => false],
            'shipping_rate_id' => null,
            'note' => null,
        ]);
    }

    protected function saveState(array $state): void
    {
        Session::put(self::SESSION_KEY, $state);
    }

    protected function generateItemKey(int $productId, ?int $variantId, array $options): string
    {
        ksort($options);
        return md5($productId . '_' . ($variantId ?? '0') . '_' . json_encode($options));
    }

    protected function calculateSubtotal(array $items): float
    {
        return (float) array_sum(array_column($items, 'subtotal'));
    }

    protected function calculateTotalWeight(array $items): float
    {
        $totalWeight = 0.0;
        foreach ($items as $item) {
            $totalWeight += ($item['weight'] ?? 0.5) * ($item['quantity'] ?? 1);
        }
        return max(0.5, $totalWeight);
    }
}
