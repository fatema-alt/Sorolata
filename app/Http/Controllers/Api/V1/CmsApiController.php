<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\Api\V1\ArtisanResource;
use App\Http\Resources\Api\V1\CollectionResource;
use App\Http\Resources\Api\V1\DistrictResource;
use App\Http\Resources\Api\V1\PageResource;
use App\Http\Resources\Api\V1\ProductListResource;
use App\Http\Resources\Api\V1\StoryResource;
use App\Models\Announcement;
use App\Models\Artisan;
use App\Models\Collection;
use App\Models\District;
use App\Models\Page;
use App\Models\Product;
use App\Models\Story;
use Illuminate\Http\JsonResponse;

class CmsApiController extends BaseApiController
{
    /**
     * Aggregated homepage layout for Mobile Apps & Headless Frontends
     */
    public function homepageSections(): JsonResponse
    {
        $heroProducts = Product::where('status', 'published')
            ->where('is_featured', true)
            ->with(['category', 'district', 'artisan', 'media'])
            ->limit(4)
            ->get();

        $featuredCollections = Collection::where('is_featured', true)
            ->withCount('products')
            ->limit(6)
            ->get();

        $featuredArtisans = Artisan::where('is_verified', true)
            ->with('district')
            ->limit(4)
            ->get();

        $heritageDistricts = District::withCount('artisans')
            ->limit(6)
            ->get();

        $gazetteStories = Story::where('is_published', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $announcement = Announcement::where('is_active', true)->first();

        return $this->respondWithSuccess([
            'announcement' => $announcement ? [
                'text' => $announcement->text,
                'bn_text' => $announcement->bn_text,
                'link_url' => $announcement->link_url,
            ] : null,
            'hero' => [
                'headline' => 'Living Heritage of Bengal',
                'subheadline' => 'Rare handcrafted heirlooms crafted by hereditary guild masters along ancient river basins.',
                'featured_products' => ProductListResource::collection($heroProducts),
            ],
            'collections' => CollectionResource::collection($featuredCollections),
            'artisans' => ArtisanResource::collection($featuredArtisans),
            'districts' => DistrictResource::collection($heritageDistricts),
            'stories' => StoryResource::collection($gazetteStories),
            'trust_badges' => [
                ['title' => '100% Genuine Handloom', 'subtitle' => 'National Crafts Council Certified'],
                ['title' => 'Geographical Indication', 'subtitle' => 'Verified Sonargaon & Tangail Origin'],
                ['title' => 'Insured Express Courier', 'subtitle' => 'Temperature stabilized DHL global shipping'],
            ],
        ], 'Homepage sections retrieved successfully.');
    }

    /**
     * List heritage gazette editorial stories
     */
    public function stories(): JsonResponse
    {
        $stories = Story::where('is_published', true)
            ->latest('published_at')
            ->paginate(10);

        return $this->respondWithPaginatedResource(
            StoryResource::collection($stories),
            'Stories retrieved successfully.'
        );
    }

    /**
     * Single story details
     */
    public function story(string $slug): JsonResponse
    {
        $story = Story::where('slug', $slug)->firstOrFail();

        return $this->respondWithSuccess(
            new StoryResource($story),
            'Story retrieved successfully.'
        );
    }

    /**
     * Static and CMS pages
     */
    public function page(string $slug): JsonResponse
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->with('seo')
            ->firstOrFail();

        return $this->respondWithSuccess(
            new PageResource($page),
            'Page retrieved successfully.'
        );
    }
}
