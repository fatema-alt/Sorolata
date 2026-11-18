<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlist;
use App\Services\Builder\DynamicDataParserService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function __construct(
        protected DynamicDataParserService $parser
    ) {}

    public function about(): View
    {
        $page = Page::where('slug', 'about')->where('status', 'published')->first();
        if ($page && !empty($page->layout_json['sections'])) {
            $sections = array_filter($page->layout_json['sections'], fn ($s) => $s['is_active'] ?? true);
            return view('builder.landing', [
                'page' => $page,
                'sections' => $sections,
                'parser' => $this->parser,
            ]);
        }

        return view('pages.about');
    }

    public function contact(): View
    {
        $page = Page::where('slug', 'contact')->where('status', 'published')->first();
        if ($page && !empty($page->layout_json['sections'])) {
            $sections = array_filter($page->layout_json['sections'], fn ($s) => $s['is_active'] ?? true);
            return view('builder.landing', [
                'page' => $page,
                'sections' => $sections,
                'parser' => $this->parser,
            ]);
        }

        return view('pages.contact');
    }

    public function wishlist(): View
    {
        $sessionWishlist = session()->get('sorolata_wishlist', []);
        $productIds = $sessionWishlist;

        if (auth()->check()) {
            $dbWishlist = Wishlist::where('user_id', auth()->id())->pluck('product_id')->toArray();
            $productIds = array_values(array_unique(array_merge($sessionWishlist, $dbWishlist)));
        }

        $wishlistProducts = empty($productIds)
            ? collect()
            : Product::with(['artisan', 'district', 'media'])
                ->whereIn('id', $productIds)
                ->where('status', 'published')
                ->get();

        return view('pages.wishlist', [
            'products' => $wishlistProducts,
        ]);
    }

    public function toggleWishlist(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = (int) $validated['product_id'];
        $wishlist = session()->get('sorolata_wishlist', []);
        $status = 'added';

        if (in_array($productId, $wishlist, true)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $status = 'removed';
            if (auth()->check()) {
                Wishlist::where('user_id', auth()->id())->where('product_id', $productId)->delete();
            }
        } else {
            $wishlist[] = $productId;
            if (auth()->check()) {
                Wishlist::firstOrCreate([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                ]);
            }
        }

        session()->put('sorolata_wishlist', $wishlist);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'count' => count($wishlist),
                'message' => $status === 'added' ? 'পণ্যটি উইশলিস্টে যুক্ত করা হয়েছে।' : 'পণ্যটি উইশলিস্ট থেকে সরানো হয়েছে।',
            ]);
        }

        $msg = $status === 'added' ? 'পণ্যটি আপনার উইশলিস্টে যুক্ত করা হয়েছে।' : 'পণ্যটি আপনার উইশলিস্ট থেকে সরানো হয়েছে।';
        return back()->with('success', $msg);
    }

    public function trackOrder(Request $request): View
    {
        $query = trim((string) ($request->query('order_number') ?: $request->query('q', '')));
        $order = null;

        if (!empty($query)) {
            $order = Order::with(['items.product.media', 'timelines' => fn ($q) => $q->orderBy('created_at', 'desc')])
                ->where('order_number', $query)
                ->orWhere('customer_phone', $query)
                ->orWhere('customer_email', $query)
                ->latest()
                ->first();
        }

        return view('pages.track-order', [
            'order' => $order,
            'queriedNumber' => $query,
        ]);
    }

    public function search(Request $request): View
    {
        $query = $request->query('q', '');
        $results = collect();

        if (!empty($query)) {
            $results = Product::with(['artisan', 'district', 'media'])
                ->where('title', 'like', "%{$query}%")
                ->orWhere('subtitle', 'like', "%{$query}%")
                ->get();
        } else {
            $results = Product::with(['artisan', 'district', 'media'])
                ->where('is_featured', true)
                ->take(6)
                ->get();
        }

        return view('pages.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    public function requestOrderCancellation(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        $validated = $request->validate([
            'reason' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        if (in_array($order->status, [OrderStatus::SHIPPED, OrderStatus::DELIVERED, OrderStatus::CANCELLED], true)) {
            return back()->with('error', 'এই অর্ডারটি আর বাতিল করা সম্ভব নয় কারণ এটি ইতোমধ্যে ডেলিভারির উদ্দেশ্যে পাঠানো হয়েছে।');
        }

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => $order->status->value,
            'actor_type' => 'customer',
            'note' => "[CANCELLATION_REQUEST] কারণ: {$validated['reason']}. বিস্তারিত: " . ($validated['notes'] ?? 'কোনো নোট নেই।'),
            'customer_notified' => true,
        ]);

        return back()->with('success', 'আপনার অর্ডার বাতিলের অনুরোধটি সফলভাবে গ্রহণ করা হয়েছে। আমাদের সাপোর্ট টিম দ্রুত এটি পর্যালোচনা করবে।');
    }
}
