<?php

declare(strict_types=1);

namespace App\Services\Api;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Str;

class CartApiService
{
    /**
     * Resolve or create active cart from HTTP request
     */
    public function getOrCreateCart(\Illuminate\Http\Request $request): Cart
    {
        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID') ?? ($request->hasSession() ? $request->session()->getId() : null);

        return $this->resolveCart($user, $sessionId);
    }

    /**
     * Resolve or create active cart for user or guest session
     */
    public function resolveCart(?User $user, ?string $sessionId): Cart
    {
        if ($user) {
            $cart = Cart::with(['items.product.media', 'items.variant'])
                ->where('user_id', $user->id)
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'uuid' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'currency' => 'BDT',
                    'total_amount' => 0.00,
                ]);
            }

            return $cart;
        }

        $sessionId = $sessionId ?: 'guest_' . Str::random(24);
        $cart = Cart::with(['items.product.media', 'items.variant'])
            ->where('session_id', $sessionId)
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'uuid' => (string) Str::uuid(),
                'user_id' => null,
                'session_id' => $sessionId,
                'currency' => 'BDT',
                'total_amount' => 0.00,
            ]);
        }

        return $cart;
    }

    /**
     * Add or increment item in cart
     */
    public function addItem(Cart $cart, int $productId, ?int $variantId = null, int $quantity = 1, array $options = []): CartItem
    {
        $product = Product::findOrFail($productId);
        $variant = $variantId ? ProductVariant::findOrFail($variantId) : null;

        $unitPrice = $variant ? (float) $variant->price : (float) $product->effective_price;

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'custom_options' => $options,
            ]);
        }

        $this->recalculateCart($cart);

        return $item->fresh(['product.media', 'variant']);
    }

    /**
     * Update quantity of item in cart
     */
    public function updateItem(Cart $cart, int $itemId, int $quantity): CartItem
    {
        $item = CartItem::where('cart_id', $cart->id)->where('id', $itemId)->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->quantity = $quantity;
            $item->save();
        }

        $this->recalculateCart($cart);

        return $item;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Cart $cart, int $itemId): void
    {
        CartItem::where('cart_id', $cart->id)->where('id', $itemId)->delete();
        $this->recalculateCart($cart);
    }

    /**
     * Apply coupon discount to cart
     */
    public function applyCoupon(Cart $cart, string $code): bool
    {
        $discount = Discount::where('code', strtoupper($code))->active()->first();
        if (!$discount) {
            return false;
        }

        $cart->coupon_code = $discount->code;
        $cart->save();
        $this->recalculateCart($cart);

        return true;
    }

    /**
     * Remove coupon
     */
    public function removeCoupon(Cart $cart): void
    {
        $cart->coupon_code = null;
        $cart->save();
        $this->recalculateCart($cart);
    }

    /**
     * Recalculate cart total amount
     */
    public function recalculateCart(Cart $cart): void
    {
        $subtotal = 0.0;
        foreach ($cart->items as $item) {
            $subtotal += ($item->unit_price * $item->quantity);
        }

        $cart->total_amount = $subtotal;
        $cart->save();
    }
}
