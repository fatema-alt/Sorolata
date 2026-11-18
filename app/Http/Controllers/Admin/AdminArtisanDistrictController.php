<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Collection;
use App\Models\District;
use Illuminate\Contracts\View\View;

class AdminArtisanDistrictController extends Controller
{
    public function artisans(): View
    {
        $artisans = Artisan::with(['district', 'products'])
            ->withCount('products')
            ->latest()
            ->paginate(15);

        return view('admin.artisans.index', [
            'artisans' => $artisans,
        ]);
    }

    public function districts(): View
    {
        $districts = District::withCount(['products', 'artisans'])
            ->orderBy('name_en')
            ->paginate(15);

        return view('admin.districts.index', [
            'districts' => $districts,
        ]);
    }

    public function collections(): View
    {
        $collections = Collection::withCount('products')
            ->latest()
            ->paginate(15);

        return view('admin.collections.index', [
            'collections' => $collections,
        ]);
    }
}
