<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
        $hasSlug = Schema::hasColumn('districts', 'slug');

        $query = District::with(['artisans', 'products.media', 'products.artisan']);

        if ($hasSlug) {
            $district = $query->where('slug', $uuidOrName)
                ->orWhere('uuid', $uuidOrName)
                ->orWhere('name_en', $uuidOrName)
                ->first();
        } else {
            $district = $query->where('uuid', $uuidOrName)
                ->orWhere('name_en', $uuidOrName)
                ->first();
        }

        // If not found yet by exact query, match by slugified name_en or normalized letters
        if (!$district) {
            $normalizedSearch = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', $uuidOrName)));
            $allDistricts = District::with(['artisans', 'products.media', 'products.artisan'])->get();
            foreach ($allDistricts as $d) {
                $slugFromName = Str::slug((string) $d->name_en);
                $normalizedName = strtolower(trim(preg_replace('/[^a-zA-Z0-9]/', '', (string) $d->name_en)));
                
                if ($slugFromName === $uuidOrName || 
                    $normalizedName === $normalizedSearch || 
                    str_contains($slugFromName, $uuidOrName) || 
                    str_contains($uuidOrName, $slugFromName) ||
                    str_contains($normalizedName, $normalizedSearch)) {
                    $district = $d;
                    break;
                }
            }
        }

        if (!$district) {
            abort(404, "Geographical basin '{$uuidOrName}' not found.");
        }

        return view('districts.show', [
            'district' => $district,
            'products' => $district->products,
            'artisans' => $district->artisans,
        ]);
    }
}
