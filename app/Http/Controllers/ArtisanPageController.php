<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Artisan;
use Illuminate\Contracts\View\View;

class ArtisanPageController extends Controller
{
    public function index(): View
    {
        $artisans = Artisan::with(['district', 'products.media'])
            ->where('is_verified', true)
            ->get();

        return view('artisans.index', [
            'artisans' => $artisans,
        ]);
    }

    public function show(string $uuid): View
    {
        $artisan = Artisan::with(['district', 'products.media', 'products.district'])
            ->where('slug', $uuid)
            ->orWhere('uuid', $uuid)
            ->orWhere('full_name', $uuid)
            ->firstOrFail();

        return view('artisans.show', [
            'artisan' => $artisan,
            'products' => $artisan->products,
        ]);
    }
}
