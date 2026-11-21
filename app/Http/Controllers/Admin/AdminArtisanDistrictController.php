<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Collection;
use App\Models\District;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function storeDistrict(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:50',
            'gi_specialty' => 'nullable|string|max:255',
            'gi_specialty_bn' => 'nullable|string|max:255',
            'river_basin' => 'nullable|string|max:255',
            'river_basin_bn' => 'nullable|string|max:255',
            'historical_narrative' => 'nullable|string|max:2000',
            'historical_narrative_bn' => 'nullable|string|max:2000',
        ]);

        if (!empty($validated['division'])) {
            $validated['division'] = \App\Enums\DistrictRegion::tryFrom($validated['division']) 
                ?? \App\Enums\DistrictRegion::tryFrom($validated['division'] . ' Division') 
                ?? $validated['division'];
        } else {
            $validated['division'] = null;
        }

        $validated['uuid'] = (string) Str::uuid();
        $validated['slug'] = Str::slug($validated['name_en']);
        
        $base = $validated['slug'];
        $i = 1;
        while (District::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $base . '-' . $i++;
        }

        $district = District::create($validated);

        return back()->with('success', "District '{$district->name_en}' added successfully.");
    }

    public function updateDistrict(Request $request, int $id): RedirectResponse
    {
        $district = District::findOrFail($id);

        $validated = $request->validate([
            'name_en' => 'required|string|max:100',
            'name_bn' => 'nullable|string|max:100',
            'division' => 'nullable|string|max:50',
            'gi_specialty' => 'nullable|string|max:255',
            'gi_specialty_bn' => 'nullable|string|max:255',
            'river_basin' => 'nullable|string|max:255',
            'river_basin_bn' => 'nullable|string|max:255',
            'historical_narrative' => 'nullable|string|max:2000',
            'historical_narrative_bn' => 'nullable|string|max:2000',
        ]);

        if (!empty($validated['division'])) {
            $validated['division'] = \App\Enums\DistrictRegion::tryFrom($validated['division']) 
                ?? \App\Enums\DistrictRegion::tryFrom($validated['division'] . ' Division') 
                ?? $validated['division'];
        } else {
            $validated['division'] = null;
        }

        $district->update($validated);

        return back()->with('success', "District '{$district->name_en}' updated successfully.");
    }

    public function destroyDistrict(int $id): RedirectResponse
    {
        $district = District::withCount(['products', 'artisans'])->findOrFail($id);

        if ($district->products_count > 0 || $district->artisans_count > 0) {
            return back()->with('error', "Cannot delete '{$district->name_en}' because it is linked to {$district->products_count} product(s) and {$district->artisans_count} artisan(s).");
        }

        $name = $district->name_en;
        $district->delete();

        return back()->with('success', "District '{$name}' deleted successfully.");
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
