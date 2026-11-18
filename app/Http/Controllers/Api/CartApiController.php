<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Commerce\CartService;
use App\Services\Commerce\CommerceAnalyticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected CommerceAnalyticsService $analytics
    ) {}

    public function show(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->cart->getContents(),
        ]);
    }

    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
            'options' => 'nullable|array',
        ]);

        $item = $this->cart->addItem(
            productId: (int) $validated['product_id'],
            variantId: !empty($validated['variant_id']) ? (int) $validated['variant_id'] : null,
            quantity: (int) ($validated['quantity'] ?? 1),
            options: $validated['options'] ?? []
        );

        $this->analytics->recordAddToCart($validated['product_id'], (int) ($validated['quantity'] ?? 1));

        return response()->json([
            'success' => true,
            'message' => 'Item added to collector cart.',
            'item' => $item,
            'cart' => $this->cart->getContents(),
        ]);
    }

    public function updateItem(Request $request, string $itemKey): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cart->updateQuantity($itemKey, (int) $validated['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Cart quantity updated.',
            'cart' => $this->cart->getContents(),
        ]);
    }

    public function removeItem(string $itemKey): JsonResponse
    {
        $this->cart->removeItem($itemKey);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart' => $this->cart->getContents(),
        ]);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);

        $res = $this->cart->applyCoupon($validated['code']);

        return response()->json([
            'success' => $res['discount'] !== null,
            'message' => $res['discount'] ? "Coupon {$res['discount']->code} applied successfully." : ($res['error'] ?? 'Invalid coupon.'),
            'cart' => $this->cart->getContents(),
        ], $res['discount'] !== null ? 200 : 422);
    }

    public function removeCoupon(): JsonResponse
    {
        $this->cart->removeCoupon();

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'cart' => $this->cart->getContents(),
        ]);
    }

    public function setGiftOptions(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'recipient' => 'nullable|string',
            'message' => 'nullable|string',
            'receipt' => 'nullable|boolean',
        ]);

        $this->cart->setGiftWrap(
            enabled: (bool) $validated['enabled'],
            recipient: $validated['recipient'] ?? null,
            message: $validated['message'] ?? null,
            receipt: (bool) ($validated['receipt'] ?? false)
        );

        return response()->json([
            'success' => true,
            'message' => 'Gift presentation options updated.',
            'cart' => $this->cart->getContents(),
        ]);
    }
}
