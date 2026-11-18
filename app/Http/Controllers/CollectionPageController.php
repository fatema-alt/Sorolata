<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class CollectionPageController extends Controller
{
    public function show(string $slug): View|\Illuminate\Http\RedirectResponse
    {
        $collection = Collection::where('slug', $slug)
            ->orWhere('uuid', $slug)
            ->firstOrFail();

        if ($slug === $collection->uuid && !empty($collection->slug)) {
            return redirect()->route('collection.show', $collection->slug, 301);
        }

        $products = Product::with(['artisan', 'district', 'media'])
            ->where('status', 'published')
            ->take(8)
            ->get();

        return view('collections.show', [
            'collection' => $collection,
            'products' => $products,
        ]);
    }
}
