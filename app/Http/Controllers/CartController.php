<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(): View
    {
        $bag = session()->get('sorolata_collector_bag', []);
        $productIds = array_keys($bag);
        
        $items = Product::with(['artisan', 'district', 'media'])
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) use ($bag) {
                $qty = $bag[$product->id] ?? 1;
                return [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $product->price * $qty,
                ];
            });

        $subtotal = $items->sum('subtotal');
        $upsellProducts = Product::with(['artisan', 'district', 'media'])
            ->whereNotIn('id', $productIds)
            ->take(3)
            ->get();

        return view('cart.index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'upsellProducts' => $upsellProducts,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::with('category')->findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);

        $cart = session()->get('sorolata_collector_bag', []);
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;
        session()->put('sorolata_collector_bag', $cart);

        $analyticsItem = [
            'item_id' => (string) $product->id,
            'item_name' => $product->title,
            'price' => (float) $product->price,
            'item_category' => $product->category?->name ?? 'Heritage Handcraft',
            'quantity' => $quantity,
        ];

        $analyticsPayload = [
            'event_id' => 'evt_atc_' . $product->id . '_' . time() . '_' . substr(bin2hex(random_bytes(3)), 0, 6),
            'item' => $analyticsItem,
            'value' => (float) $product->price * $quantity,
            'currency' => 'BDT',
        ];

        session()->flash('analytics_add_to_cart', $analyticsPayload);

        if ($request->expectsJson() || $request->ajax()) {
            $totalCount = array_sum(session()->get('sorolata_collector_bag', []));
            return response()->json([
                'success' => true,
                'message' => __('Added :quantity × :title to your Collector Bag.', ['quantity' => $quantity, 'title' => $product->title]),
                'cart_count' => $totalCount,
                'product' => [
                    'id' => $product->id,
                    'title' => $product->title,
                    'price' => $product->formatted_price,
                ],
                'analytics' => $analyticsPayload,
            ]);
        }

        return back()->with('success', __('Added :quantity × :title to your Collector Bag.', ['quantity' => $quantity, 'title' => $product->title]));
    }

    public function update(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = session()->get('sorolata_collector_bag', []);
        $qty = (int) $validated['quantity'];

        if ($qty <= 0) {
            unset($cart[$productId]);
            session()->put('sorolata_collector_bag', $cart);
            return back()->with('success', __('Item removed from your Collector Bag.'));
        }

        $cart[$productId] = $qty;
        session()->put('sorolata_collector_bag', $cart);

        return back()->with('success', __('Collector Bag quantity updated.'));
    }

    public function remove(int $productId): RedirectResponse
    {
        $cart = session()->get('sorolata_collector_bag', []);
        unset($cart[$productId]);
        session()->put('sorolata_collector_bag', $cart);

        return back()->with('success', __('Item removed from your Collector Bag.'));
    }
}
