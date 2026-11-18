<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Commerce\ReturnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function __construct(
        protected ReturnService $returnService
    ) {}

    public function show(string $orderNumber): JsonResponse
    {
        $order = Order::with([
            'items.product.media',
            'items.variant',
            'timelines',
            'returns',
            'shippingRate',
        ])
        ->where('order_number', $orderNumber)
        ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function requestReturn(Request $request, string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'reason' => 'required|string',
            'customer_note' => 'nullable|string',
            'evidence_images' => 'nullable|array',
        ]);

        $returnRequest = $this->returnService->createReturnRequest(
            order: $order,
            reason: $validated['reason'],
            customerNote: $validated['customer_note'] ?? '',
            images: $validated['evidence_images'] ?? [],
            user: $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Return request submitted for curation review.',
            'return_request' => $returnRequest,
        ]);
    }
}
