<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTimeline;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class OrderService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Create and persist a full order from the current cart state.
     */
    public function createOrderFromCart(CartService $cart, array $customerData, ?User $user = null): Order
    {
        $cartState = $cart->getContents();
        $items = $cartState['items'];

        if (empty($items)) {
            throw new InvalidArgumentException('Cannot place an order with an empty cart.');
        }

        $totals = $cartState['totals'];

        return DB::transaction(function () use ($items, $totals, $cartState, $customerData, $user, $cart) {
            $orderNumber = 'SRL-' . strtoupper(Str::random(8));

            $order = Order::create([
                'uuid' => (string) Str::uuid(),
                'order_number' => $orderNumber,
                'user_id' => $user?->id,
                'customer_name' => $customerData['name'] ?? 'Heritage Collector',
                'customer_email' => $customerData['email'] ?? 'collector@sorolata.com',
                'customer_phone' => $customerData['phone'] ?? '+8801700000000',
                'total_amount' => $totals['grand_total'],
                'subtotal_amount' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'shipping_fee' => $totals['shipping_fee'],
                'tax_amount' => $totals['tax_amount'],
                'gift_wrap' => $cartState['gift']['enabled'] ?? false,
                'gift_wrap_fee' => $totals['gift_wrap_fee'] ?? 0.0,
                'is_gift' => $cartState['gift']['enabled'] ?? false,
                'gift_recipient_name' => $cartState['gift']['recipient'] ?? null,
                'gift_message' => $cartState['gift']['message'] ?? null,
                'gift_receipt' => $cartState['gift']['receipt'] ?? false,
                'currency' => 'BDT',
                'status' => OrderStatus::PENDING,
                'payment_status' => PaymentStatus::UNPAID,
                'payment_gateway' => $customerData['payment_gateway'] ?? 'cash_on_delivery',
                'shipping_rate_id' => $cartState['shipping_rate_id'] ?? null,
                'shipping_address' => $customerData['shipping_address'] ?? [
                    'line_1' => $customerData['address'] ?? 'Gulshan-2, Dhaka',
                    'city' => $customerData['city'] ?? 'Dhaka',
                    'country' => 'Bangladesh',
                ],
                'billing_address' => $customerData['billing_address'] ?? null,
                'notes' => $cartState['note'] ?? null,
            ]);

            // Create immutable snapshots in order_items & deduct stock
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                $variant = !empty($item['variant_id']) ? ProductVariant::find($item['variant_id']) : null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product?->id,
                    'product_variant_id' => $variant?->id,
                    'product_title' => $item['title'],
                    'variant_title' => $item['variant_title'] ?? null,
                    'artisan_id' => $product?->artisan_id,
                    'artisan_name' => $product?->artisan?->full_name,
                    'sku' => $item['sku'],
                    'unit_price' => $item['unit_price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'options' => $item['options'] ?? [],
                ]);

                // Inventory deduction
                if ($variant) {
                    $this->inventoryService->deductStock($variant, $item['quantity'], $order);
                } elseif ($product) {
                    $this->inventoryService->deductStock($product, $item['quantity'], $order);
                }
            }

            // Initial Audit Timeline
            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => OrderStatus::PENDING->value,
                'actor_type' => $user ? 'customer' : 'guest',
                'actor_id' => $user?->id,
                'note' => 'Order placed successfully by collector. Awaiting artisan preparation.',
                'customer_notified' => true,
            ]);

            // Clear the cart
            $cart->clear();

            return $order;
        });
    }

    /**
     * Transition order status and append to immutable timeline.
     */
    public function transitionStatus(
        Order $order,
        OrderStatus $newStatus,
        string $note = '',
        string $actorType = 'system',
        ?int $actorId = null
    ): OrderTimeline {
        $order->update(['status' => $newStatus]);

        return OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $newStatus->value,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'note' => $note ?: "Status updated to: {$newStatus->label()}",
            'customer_notified' => true,
        ]);
    }
}
