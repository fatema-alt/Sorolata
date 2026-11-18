<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryPageController extends Controller
{
    public function show(string $slug): View|\Illuminate\Http\RedirectResponse
    {
        $category = Category::with(['products.artisan', 'products.district', 'products.media'])
            ->where('slug', $slug)
            ->orWhere('uuid', $slug)
            ->firstOrFail();

        if ($slug === $category->uuid && !empty($category->slug)) {
            return redirect()->route('category.show', $category->slug, 301);
        }

        $subcategories = Category::where('parent_id', $category->id)->get();

        return view('categories.show', [
            'category' => $category,
            'products' => $category->products,
            'subcategories' => $subcategories,
        ]);
    }
}
