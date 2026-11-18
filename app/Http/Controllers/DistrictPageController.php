<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Contracts\View\View;

class DistrictPageController extends Controller
{
    public function index(): View
    {
        $districts = District::withCount(['products', 'artisans'])->get();

        return view('districts.index', [
            'districts' => $districts,
        ]);
    }

    public function show(string $uuidOrName): View
    {
        $district = District::with(['artisans', 'products.media', 'products.artisan'])
            ->where('slug', $uuidOrName)
            ->orWhere('uuid', $uuidOrName)
            ->orWhere('name_en', $uuidOrName)
            ->firstOrFail();

        return view('districts.show', [
            'district' => $district,
            'products' => $district->products,
            'artisans' => $district->artisans,
        ]);
    }
}
