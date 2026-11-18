<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\OrderStatus;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\OrderItemResource;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Resources\Api\V1\OrderTimelineResource;
use App\Models\Cart;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTimeline;
use App\Models\Payment;
use App\Models\ShippingRate;
use App\Services\Commerce\Payment\PaymentGatewayManager;
use App\Services\Webhook\WebhookDispatcherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutApiController extends BaseApiController
{
    public function __construct(
        protected PaymentGatewayManager $paymentManager,
        protected WebhookDispatcherService $webhookService
    ) {}

    /**
     * List active payment gateways
     */
    public function gateways(): JsonResponse
    {
        $rawGateways = $this->paymentManager->getAvailableGateways();
        $gateways = [];
        foreach ($rawGateways as $gw) {
            $code = $gw['id'];
            $type = match (true) {
                in_array($code, ['bkash', 'nagad', 'rocket'], true) => 'mfs',
                $code === 'cash_on_delivery' || $code === 'cod' => 'offline',
                default => 'card_and_bank',
            };

            $gateways[] = [
                'code' => $code,
                'name' => $gw['name'],
                'type' => $type,
                'icon' => null,
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment gateways retrieved successfully.',
            'data' => $gateways,
            'gateways' => $gateways,
            'meta' => [],
            'errors' => [],
        ], 200);
    }

    /**
     * List active shipping rates
     */
    public function shippingRates(): JsonResponse
    {
        $rates = ShippingRate::where('is_active', true)
            ->with('zone')
            ->orderBy('rate')
            ->get();

        return $this->respondWithSuccess($rates, 'Shipping rates retrieved successfully.');
    }

    /**
     * Pre-calculate order totals (subtotal, shipping, discount, total)
     */
    public function calculate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cart_uuid' => ['required', 'string'],
            'shipping_rate_id' => ['nullable', 'integer', 'exists:shipping_rates,id'],
            'coupon_code' => ['nullable', 'string'],
        ]);

        $cart = Cart::where('uuid', $validated['cart_uuid'])->with('items.product')->firstOrFail();
        $subtotal = (float) $cart->total_amount;

        $shippingFee = 120.00; // default inside Dhaka
        if (!empty($validated['shipping_rate_id'])) {
            $rate = ShippingRate::find($validated['shipping_rate_id']);
            if ($rate) {
                $shippingFee = (float) $rate->rate;
            }
        }

        $discountAmount = 0.00;
        $couponCode = $validated['coupon_code'] ?? $cart->coupon_code;
        if (!empty($couponCode)) {
            $discount = Discount::where('code', strtoupper($couponCode))->active()->first();
            if ($discount) {
                if ($discount->type === 'percentage') {
                    $discountAmount = ($subtotal * $discount->value) / 100.0;
                } else {
                    $discountAmount = (float) $discount->value;
                }
            }
        }

        $total = max(0.0, ($subtotal - $discountAmount) + $shippingFee);

        return $this->respondWithSuccess([
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'discount_amount' => $discountAmount,
            'total_amount' => $total,
            'currency' => $cart->currency ?? 'BDT',
        ], 'Totals calculated successfully.');
    }

    /**
     * Execute checkout and place order
     */
    public function process(Request $request): JsonResponse
    {
        // Resolve Cart: by cart_uuid if provided, or from CartApiService / latest session cart
        $cart = null;
        if ($request->filled('cart_uuid')) {
            $cart = Cart::where('uuid', $request->input('cart_uuid'))->with(['items.product', 'items.variant'])->first();
        }
        if (!$cart) {
            $cart = app(\App\Services\Api\CartApiService::class)->getOrCreateCart($request);
            $cart->load(['items.product', 'items.variant']);
        }

        if (!$cart || $cart->items->isEmpty()) {
            $cart = Cart::whereHas('items')->latest()->with(['items.product', 'items.variant'])->first();
        }

        if (!$cart || $cart->items->isEmpty()) {
            return $this->respondWithError('Cannot checkout with an empty cart.', 422);
        }

        // Support both structured Part 08 and legacy Part 05 payloads
        $customerName = (string) ($request->input('customer_name') ?? $request->input('name') ?? 'Guest Collector');
        $customerEmail = (string) ($request->input('customer_email') ?? $request->input('email') ?? 'collector@sorolata.com');
        $customerPhone = (string) ($request->input('customer_phone') ?? $request->input('phone') ?? '+8801700000000');
        $paymentGateway = (string) ($request->input('payment_gateway') ?? 'cash_on_delivery');
        $notes = $request->input('notes');

        $shippingAddress = $request->input('shipping_address');
        if (is_string($shippingAddress)) {
            $shippingAddress = ['address' => $shippingAddress, 'city' => 'Dhaka', 'district' => 'Dhaka'];
        } elseif (!is_array($shippingAddress)) {
            $addressStr = (string) ($request->input('address') ?? '123 Heritage Way, Dhaka');
            $shippingAddress = ['address' => $addressStr, 'city' => 'Dhaka', 'district' => 'Dhaka'];
        }

        $subtotal = (float) $cart->total_amount;
        $shippingFee = 120.00;
        $discountAmount = 0.00;

        if ($cart->coupon_code) {
            $discount = Discount::where('code', $cart->coupon_code)->active()->first();
            if ($discount) {
                $discountAmount = $discount->type === 'percentage'
                    ? ($subtotal * $discount->value) / 100.0
                    : (float) $discount->value;
            }
        }

        $total = max(0.0, ($subtotal - $discountAmount) + $shippingFee);
        $user = $request->user('sanctum') ?? $cart->user;

        $order = Order::create([
            'uuid' => (string) Str::uuid(),
            'order_number' => 'SRL-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
            'user_id' => $user?->id,
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'total_amount' => $total,
            'subtotal_amount' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_fee' => $shippingFee,
            'currency' => $cart->currency ?? 'BDT',
            'status' => OrderStatus::PENDING,
            'payment_status' => 'unpaid',
            'payment_gateway' => $paymentGateway,
            'shipping_address' => $shippingAddress,
            'notes' => $notes,
        ]);

        // Create Order Items & Deduct Stock
        foreach ($cart->items as $item) {
            $product = $item->product;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'artisan_id' => $product->artisan_id,
                'product_title' => $product->title,
                'sku' => $item->variant?->sku ?? $product->sku ?? ('SRL-' . $product->id),
                'variant_title' => $item->variant?->title,
                'unit_price' => $item->unit_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->unit_price * $item->quantity,
                'options' => $item->custom_options,
                'artisan_name' => $product->artisan?->full_name,
            ]);

            // Deduct stock
            if ($product->track_inventory) {
                $product->decrement('stock_quantity', $item->quantity);
            }
        }

        // Timeline entry
        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => 'pending',
            'title' => 'Order Received & Registered in Archival Ledger',
            'description' => 'Collector requisition received for handmade verification.',
            'location' => 'Sorolata Dhaka Central Studio',
        ]);

        // Record initial payment intent
        Payment::create([
            'uuid' => (string) Str::uuid(),
            'order_id' => $order->id,
            'gateway' => $paymentGateway,
            'currency' => $order->currency,
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);

        // Clear Cart
        $cart->items()->delete();
        $cart->total_amount = 0.00;
        $cart->coupon_code = null;
        $cart->save();

        // Dispatch Outbound Webhook
        $this->webhookService->dispatch('order.created', [
            'order_number' => $order->order_number,
            'total' => $order->total_amount,
            'customer_email' => $order->customer_email,
        ]);

        $redirectUrl = url("/checkout/pay/{$order->order_number}");
        $orderData = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status->value,
            'status_label' => $order->status->label(),
            'total_amount' => (float) $order->total_amount,
            'subtotal_amount' => (float) $order->subtotal_amount,
            'items' => OrderItemResource::collection($order->items),
            'timeline' => OrderTimelineResource::collection($order->timeline),
            'timelines' => OrderTimelineResource::collection($order->timeline),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'data' => [
                'order' => new OrderResource($order->load(['items', 'timeline'])),
                'payment_redirect_url' => $redirectUrl,
            ],
            'order' => $orderData,
            'payment' => [
                'redirect_url' => $redirectUrl,
            ],
            'payment_redirect_url' => $redirectUrl,
            'meta' => [],
            'errors' => [],
        ], 200);
    }
}
