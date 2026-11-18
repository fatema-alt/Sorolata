<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\OrderResource;
use App\Http\Resources\Api\V1\OrderTimelineResource;
use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends BaseApiController
{
    /**
     * Authenticated customer order history
     */
    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with(['items', 'timeline'])
            ->latest()
            ->paginate(10);

        return $this->respondWithPaginatedResource(
            OrderResource::collection($orders),
            'Orders retrieved successfully.'
        );
    }

    /**
     * Single order details
     */
    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items', 'timeline'])
            ->firstOrFail();

        return $this->respondWithSuccess(
            new OrderResource($order),
            'Order details retrieved successfully.'
        );
    }

    /**
     * Tracking timeline & courier status
     */
    public function tracking(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('timeline')
            ->firstOrFail();

        return $this->respondWithSuccess([
            'order_number' => $order->order_number,
            'status' => $order->status->value,
            'status_label' => $order->status->label(),
            'courier_name' => $order->courier_name,
            'tracking_number' => $order->tracking_number,
            'tracking_url' => $order->tracking_url,
            'timeline' => OrderTimelineResource::collection($order->timeline),
        ], 'Tracking timeline retrieved successfully.');
    }

    /**
     * Printable invoice / certificate payload
     */
    public function invoice(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return $this->respondWithSuccess([
            'invoice_number' => 'INV-' . $order->order_number,
            'issue_date' => $order->created_at?->format('d M, Y'),
            'order' => new OrderResource($order),
            'archival_seal_verified' => true,
        ], 'Invoice data retrieved successfully.');
    }

    /**
     * Request order return / refund
     */
    public function requestReturn(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'amount_requested' => ['nullable', 'numeric', 'min:0'],
        ]);

        $return = ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => $request->user('sanctum')?->id ?? $order->user_id,
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending',
            'amount_requested' => $validated['amount_requested'] ?? $order->total_amount,
        ]);

        return $this->respondWithSuccess($return, 'Return request submitted successfully.', 201);
    }
}
