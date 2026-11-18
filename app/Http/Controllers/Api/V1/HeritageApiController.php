<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ArtisanResource;
use App\Http\Resources\Api\V1\DistrictResource;
use App\Http\Resources\Api\V1\DivisionResource;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Models\Artisan;
use App\Models\District;
use App\Models\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HeritageApiController extends BaseApiController
{
    /**
     * List all 8 administrative divisions
     */
    public function divisions(): JsonResponse
    {
        $divisions = Division::with('districts')->get();

        return $this->respondWithSuccess(
            DivisionResource::collection($divisions),
            'Divisions retrieved successfully.'
        );
    }

    /**
     * List heritage districts with geographical craft specialties
     */
    public function districts(): JsonResponse
    {
        $districts = District::with(['divisionRel'])
            ->withCount(['artisans', 'products'])
            ->get();

        return $this->respondWithSuccess(
            DistrictResource::collection($districts),
            'Districts retrieved successfully.'
        );
    }

    /**
     * District details with local guild artisans and signature products
     */
    public function district(Request $request, string $slugOrName): JsonResponse
    {
        $district = District::where('slug', $slugOrName)
            ->orWhere('name_en', $slugOrName)
            ->orWhere('uuid', $slugOrName)
            ->orWhere('id', is_numeric($slugOrName) ? (int) $slugOrName : 0)
            ->with(['divisionRel', 'artisans.workshop'])
            ->withCount(['artisans', 'products'])
            ->firstOrFail();

        $limit = min(50, max(1, (int) $request->input('limit', 16)));
        $products = $district->products()
            ->where('status', 'published')
            ->with(['category', 'artisan', 'media'])
            ->paginate($limit);

        return $this->respondWithSuccess([
            'district' => new DistrictResource($district),
            'products' => ProductListResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ], 'District details retrieved successfully.');
    }

    /**
     * List master artisans and guild creators
     */
    public function artisans(): JsonResponse
    {
        $artisans = Artisan::with(['district', 'workshop'])
            ->withCount('products')
            ->orderByDesc('years_of_mastery')
            ->get();

        return $this->respondWithSuccess(
            ArtisanResource::collection($artisans),
            'Master artisans retrieved successfully.'
        );
    }

    /**
     * Single artisan profile with workshop and signature works
     */
    public function artisan(Request $request, string $uuidOrId): JsonResponse
    {
        $artisan = Artisan::where('slug', $uuidOrId)
            ->orWhere('uuid', $uuidOrId)
            ->orWhere('id', is_numeric($uuidOrId) ? (int) $uuidOrId : 0)
            ->orWhere('full_name', $uuidOrId)
            ->with(['district', 'workshop', 'media'])
            ->withCount('products')
            ->firstOrFail();

        $limit = min(50, max(1, (int) $request->input('limit', 16)));
        $products = $artisan->products()
            ->where('status', 'published')
            ->with(['category', 'district', 'media'])
            ->paginate($limit);

        return $this->respondWithSuccess([
            'artisan' => new ArtisanResource($artisan),
            'products' => ProductListResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
        ], 'Artisan profile retrieved successfully.');
    }
}
