<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\CartItemResource;
use App\Http\Resources\Api\V1\CartResource;
use App\Services\Api\CartApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends BaseApiController
{
    public function __construct(
        protected CartApiService $cartService
    ) {}

    /**
     * Get current user or guest cart
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');

        $cart = $this->cartService->resolveCart($user, $sessionId);

        return $this->respondWithSuccess(
            new CartResource($cart),
            'Cart retrieved successfully.'
        );
    }

    /**
     * Add item to cart
     */
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:50'],
            'custom_options' => ['nullable', 'array'],
        ]);

        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');
        $cart = $this->cartService->resolveCart($user, $sessionId);

        $item = $this->cartService->addItem(
            $cart,
            (int) $validated['product_id'],
            isset($validated['variant_id']) ? (int) $validated['variant_id'] : null,
            (int) ($validated['quantity'] ?? 1),
            $validated['custom_options'] ?? []
        );

        $itemResource = new CartItemResource($item);
        $cartResource = new CartResource($cart->fresh(['items.product.media', 'items.variant']));

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully.',
            'data' => [
                'item' => $itemResource,
                'cart' => $cartResource,
            ],
            'item' => $itemResource,
            'cart' => $cartResource,
            'meta' => [],
            'errors' => [],
        ], 200);
    }

    /**
     * Update item quantity in cart
     */
    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:50'],
        ]);

        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');
        $cart = $this->cartService->resolveCart($user, $sessionId);

        $item = $this->cartService->updateItem($cart, $itemId, (int) $validated['quantity']);

        return $this->respondWithSuccess(
            new CartResource($cart->fresh(['items.product.media', 'items.variant'])),
            'Cart updated successfully.'
        );
    }

    /**
     * Remove item from cart
     */
    public function removeItem(Request $request, int $itemId): JsonResponse
    {
        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');
        $cart = $this->cartService->resolveCart($user, $sessionId);

        $this->cartService->removeItem($cart, $itemId);

        return $this->respondWithSuccess(
            new CartResource($cart->fresh(['items.product.media', 'items.variant'])),
            'Item removed from cart successfully.'
        );
    }

    /**
     * Apply promotional coupon to cart
     */
    public function applyCoupon(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');
        $cart = $this->cartService->resolveCart($user, $sessionId);

        $success = $this->cartService->applyCoupon($cart, $validated['code']);

        if (!$success) {
            return $this->respondWithError('Invalid or expired coupon code.', 422, [
                'code' => ['The provided coupon code is not valid.'],
            ]);
        }

        return $this->respondWithSuccess(
            new CartResource($cart->fresh(['items.product.media', 'items.variant'])),
            'Coupon applied successfully.'
        );
    }

    /**
     * Remove coupon from cart
     */
    public function removeCoupon(Request $request): JsonResponse
    {
        $user = $request->user('sanctum');
        $sessionId = $request->header('X-Session-ID');
        $cart = $this->cartService->resolveCart($user, $sessionId);

        $this->cartService->removeCoupon($cart);

        return $this->respondWithSuccess(
            new CartResource($cart->fresh(['items.product.media', 'items.variant'])),
            'Coupon removed successfully.'
        );
    }
}
