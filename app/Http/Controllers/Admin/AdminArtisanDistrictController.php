<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\ArtisanCraftType;
use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Collection;
use App\Models\District;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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
            'districts' => District::orderBy('name_en')->get(),
            'craftTypes' => ArtisanCraftType::cases(),
        ]);
    }

    private function validateArtisanPayload(Request $request): array
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:120',
            'full_name_bn' => 'nullable|string|max:120',
            'atelier_name' => 'nullable|string|max:150',
            'atelier_name_bn' => 'nullable|string|max:150',
            'district_id' => 'nullable|exists:districts,id',
            'craft_type' => 'nullable|string|max:60',
            'years_of_mastery' => 'nullable|integer|min:0|max:100',
            'quote' => 'nullable|string|max:1000',
            'quote_bn' => 'nullable|string|max:1000',
            'biography' => 'nullable|string|max:5000',
            'biography_bn' => 'nullable|string|max:5000',
            'portrait_url' => 'nullable|string|max:500',
            'portrait_file' => 'nullable|image|max:5120',
            'awards_text' => 'nullable|string|max:2000',
            'is_verified' => 'nullable|boolean',
        ]);

        if ($request->hasFile('portrait_file')) {
            $file = $request->file('portrait_file');
            $dir = public_path('uploads/artisans');
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $filename = 'artisan_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $validated['portrait_url'] = '/uploads/artisans/' . $filename;
        }

        $awardsLines = array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', (string) ($validated['awards_text'] ?? '')) ?: []
        )));
        $validated['awards'] = $awardsLines;
        $validated['is_verified'] = $request->boolean('is_verified', true);
        $validated['years_of_mastery'] = (int) ($validated['years_of_mastery'] ?? 0);

        unset($validated['portrait_file'], $validated['awards_text']);

        return $validated;
    }

    public function storeArtisan(Request $request): RedirectResponse
    {
        $data = $this->validateArtisanPayload($request);
        $data['uuid'] = (string) Str::uuid();
        if (Schema::hasColumn('artisans', 'slug')) {
            $data['slug'] = Artisan::generateUniqueSlug($data['full_name']);
        }

        $artisan = Artisan::create($data);

        return back()->with('success', "Artisan '{$artisan->full_name}' added successfully.");
    }

    public function updateArtisan(Request $request, int $id): RedirectResponse
    {
        $artisan = Artisan::findOrFail($id);
        $data = $this->validateArtisanPayload($request);

        if (empty($data['portrait_url'])) {
            unset($data['portrait_url']);
        }

        $artisan->update($data);

        return back()->with('success', "Artisan '{$artisan->full_name}' updated successfully.");
    }

    public function destroyArtisan(int $id): RedirectResponse
    {
        $artisan = Artisan::withCount('products')->findOrFail($id);

        if ($artisan->products_count > 0) {
            return back()->with('error', "Cannot delete '{$artisan->full_name}' because they are linked to {$artisan->products_count} product(s).");
        }

        $name = $artisan->full_name;
        $artisan->delete();

        return back()->with('success', "Artisan '{$name}' deleted successfully.");
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
