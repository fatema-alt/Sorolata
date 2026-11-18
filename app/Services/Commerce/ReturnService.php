<?php

declare(strict_types=1);

namespace App\Services\Commerce;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\ReturnStatus;
use App\Enums\StockMovementType;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\ReturnRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnService
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Submit a return request for an existing order.
     */
    public function createReturnRequest(
        Order $order,
        string $reason,
        string $customerNote = '',
        array $images = [],
        ?User $user = null
    ): ReturnRequest {
        return ReturnRequest::create([
            'uuid' => (string) Str::uuid(),
            'order_id' => $order->id,
            'user_id' => $user?->id ?? $order->user_id,
            'status' => ReturnStatus::PENDING,
            'reason' => $reason,
            'customer_note' => $customerNote,
            'refund_amount' => $order->total_amount,
            'refund_type' => 'full',
            'evidence_images' => $images,
        ]);
    }

    /**
     * Staff approval of the return request.
     */
    public function approveReturn(ReturnRequest $returnRequest, float $refundAmount, string $adminNote = ''): void
    {
        $returnRequest->update([
            'status' => ReturnStatus::APPROVED,
            'refund_amount' => $refundAmount,
            'admin_note' => $adminNote,
        ]);

        OrderTimeline::create([
            'order_id' => $returnRequest->order_id,
            'status' => 'return_approved',
            'actor_type' => 'staff',
            'note' => "Return approved for ৳{$refundAmount}. Collector instructed to ship artifact to atelier.",
            'customer_notified' => true,
        ]);
    }

    /**
     * Complete the refund and restock returned items into inventory.
     */
    public function processRefund(ReturnRequest $returnRequest): void
    {
        DB::transaction(function () use ($returnRequest) {
            $returnRequest->update(['status' => ReturnStatus::REFUNDED]);

            $order = $returnRequest->order;
            $order->update([
                'status' => OrderStatus::REFUNDED,
                'payment_status' => PaymentStatus::REFUNDED,
            ]);

            // Restock items into inventory
            foreach ($order->items as $item) {
                if ($item->variant) {
                    $this->inventoryService->adjustStock(
                        item: $item->variant,
                        delta: $item->quantity,
                        type: StockMovementType::RETURN,
                        reason: "Restocked from Return #{$returnRequest->uuid}",
                        reference: $returnRequest
                    );
                } elseif ($item->product) {
                    $this->inventoryService->adjustStock(
                        item: $item->product,
                        delta: $item->quantity,
                        type: StockMovementType::RETURN,
                        reason: "Restocked from Return #{$returnRequest->uuid}",
                        reference: $returnRequest
                    );
                }
            }

            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => OrderStatus::REFUNDED->value,
                'actor_type' => 'system',
                'note' => "Refund of ৳{$returnRequest->refund_amount} executed and items restocked to guild inventory.",
                'customer_notified' => true,
            ]);
        });
    }
}
