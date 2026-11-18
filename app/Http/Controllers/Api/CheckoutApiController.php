<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Commerce\CartService;
use App\Services\Commerce\CommerceAnalyticsService;
use App\Services\Commerce\OrderService;
use App\Services\Commerce\Payment\PaymentGatewayManager;
use App\Services\Commerce\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutApiController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected OrderService $orderService,
        protected ShippingService $shippingService,
        protected PaymentGatewayManager $paymentManager,
        protected CommerceAnalyticsService $analytics
    ) {}

    public function gateways(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'gateways' => $this->paymentManager->getAvailableGateways(),
        ]);
    }

    public function shippingRates(Request $request): JsonResponse
    {
        $location = (string) $request->input('location', 'Dhaka');
        $cartTotals = $this->cart->getTotals();

        $rates = $this->shippingService->getAvailableRates(
            location: $location,
            weight: $cartTotals['total_weight'],
            subtotal: $cartTotals['subtotal']
        );

        return response()->json([
            'success' => true,
            'rates' => $rates,
        ]);
    }

    public function process(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'district_id' => 'nullable|integer|exists:districts,id',
            'shipping_rate_id' => 'nullable|integer|exists:shipping_rates,id',
            'payment_gateway' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        if (!empty($validated['shipping_rate_id'])) {
            $this->cart->setShippingRate((int) $validated['shipping_rate_id']);
        }

        if (!empty($validated['notes'])) {
            $this->cart->setOrderNote($validated['notes']);
        }

        $this->analytics->recordCheckoutStarted($this->cart->getTotals()['subtotal']);

        $customerData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'payment_gateway' => $validated['payment_gateway'],
            'shipping_address' => [
                'line_1' => $validated['address'],
                'city' => $validated['city'] ?? 'Dhaka',
                'country' => 'Bangladesh',
            ],
            'notes' => $validated['notes'] ?? null,
        ];

        $order = $this->orderService->createOrderFromCart(
            cart: $this->cart,
            customerData: $customerData,
            user: $request->user()
        );

        $this->analytics->recordPurchase($order);

        // Initiate payment through driver
        $gateway = $this->paymentManager->driver($order->payment_gateway->value);
        $paymentRes = $gateway->initiatePayment($order);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'order' => [
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'status' => $order->status->value,
                'payment_status' => $order->payment_status->value,
            ],
            'payment' => [
                'redirect_url' => $paymentRes->redirectUrl,
                'transaction_id' => $paymentRes->transactionId,
                'instructions' => $paymentRes->instructions,
            ],
        ]);
    }
}
