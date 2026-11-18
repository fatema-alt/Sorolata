<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\District;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['artisan', 'district', 'media', 'category'])
            ->where('status', 'published');

        // Filter by Category
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category'));
            });
        }

        // Filter by District
        if ($request->filled('district')) {
            $query->where('district_id', $request->query('district'));
        }

        // Filter by Artisan
        if ($request->filled('artisan')) {
            $query->where('artisan_id', $request->query('artisan'));
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->query('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->query('max_price'));
        }

        // Sorting
        match ($request->query('sort', 'newest')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'popular' => $query->orderBy('is_featured', 'desc'),
            default => $query->orderBy('id', 'desc'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $districts = District::withCount('products')->get();
        $artisans = Artisan::where('is_verified', true)->get();

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'districts' => $districts,
            'artisans' => $artisans,
            'activeCategory' => $request->query('category'),
            'activeDistrict' => $request->query('district'),
            'activeSort' => $request->query('sort', 'newest'),
        ]);
    }
}
