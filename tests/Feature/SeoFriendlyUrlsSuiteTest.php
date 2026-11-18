<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Product;
use App\Models\Story;
use App\Models\UrlRedirect;
use Database\Seeders\HeritageCommerceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoFriendlyUrlsSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(HeritageCommerceSeeder::class);
    }

    public function test_district_resolves_via_seo_friendly_slug_and_has_canonical_tag(): void
    {
        $district = District::firstOrFail();
        $this->assertNotEmpty($district->slug);

        $response = $this->get('/district/' . $district->slug);
        $response->assertStatus(200);
        $response->assertSee($district->name_en);
        $response->assertSee('<link rel="canonical" href="' . route('district.show', $district->slug) . '">', false);
    }

    public function test_district_backward_compatible_via_uuid(): void
    {
        $district = District::firstOrFail();

        $response = $this->get('/district/' . $district->uuid);
        $response->assertStatus(200);
        $response->assertSee($district->name_en);
    }

    public function test_plural_districts_url_redirects_301_to_singular_canonical(): void
    {
        $district = District::firstOrFail();

        $response = $this->get('/districts/' . $district->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('district.show', $district->slug));
    }

    public function test_artisan_resolves_via_seo_friendly_slug_and_has_canonical_tag(): void
    {
        $artisan = Artisan::firstOrFail();
        $this->assertNotEmpty($artisan->slug);

        $response = $this->get('/artisan/' . $artisan->slug);
        $response->assertStatus(200);
        $response->assertSee($artisan->full_name);
        $response->assertSee('<link rel="canonical" href="' . route('artisan.show', $artisan->slug) . '">', false);
    }

    public function test_artisan_backward_compatible_via_uuid(): void
    {
        $artisan = Artisan::firstOrFail();

        $response = $this->get('/artisan/' . $artisan->uuid);
        $response->assertStatus(200);
        $response->assertSee($artisan->full_name);
    }

    public function test_plural_artisans_url_redirects_301_to_singular_canonical(): void
    {
        $artisan = Artisan::firstOrFail();

        $response = $this->get('/artisans/' . $artisan->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('artisan.show', $artisan->slug));
    }

    public function test_plural_products_url_redirects_301_to_singular_canonical(): void
    {
        $product = Product::where('status', 'published')->firstOrFail();

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('products.show', $product->slug));
    }

    public function test_product_uuid_redirects_301_to_slug(): void
    {
        $product = Product::where('status', 'published')->firstOrFail();

        $response = $this->get('/product/' . $product->uuid);
        $response->assertStatus(301);
        $response->assertRedirect(route('products.show', $product->slug));
    }

    public function test_plural_categories_url_redirects_301_to_singular_canonical(): void
    {
        $category = Category::firstOrFail();

        $response = $this->get('/categories/' . $category->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('category.show', $category->slug));
    }

    public function test_category_uuid_redirects_301_to_slug(): void
    {
        $category = Category::firstOrFail();

        $response = $this->get('/category/' . $category->uuid);
        $response->assertStatus(301);
        $response->assertRedirect(route('category.show', $category->slug));
    }

    public function test_plural_collections_url_redirects_301_to_singular_canonical(): void
    {
        $collection = Collection::firstOrFail();

        $response = $this->get('/collections/' . $collection->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('collection.show', $collection->slug));
    }

    public function test_collection_uuid_redirects_301_to_slug(): void
    {
        $collection = Collection::firstOrFail();

        $response = $this->get('/collection/' . $collection->uuid);
        $response->assertStatus(301);
        $response->assertRedirect(route('collection.show', $collection->slug));
    }

    public function test_singular_story_url_redirects_301_to_plural_stories(): void
    {
        $story = Story::where('is_published', true)->firstOrFail();

        $response = $this->get('/story/' . $story->slug);
        $response->assertStatus(301);
        $response->assertRedirect(route('stories.show', $story->slug));
    }

    public function test_story_uuid_redirects_301_to_slug(): void
    {
        $story = Story::where('is_published', true)->firstOrFail();

        $response = $this->get('/stories/' . $story->uuid);
        $response->assertStatus(301);
        $response->assertRedirect(route('stories.show', $story->slug));
    }

    public function test_dynamic_sitemap_xml_generates_valid_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type') ?? '');

        $district = District::firstOrFail();
        $artisan = Artisan::firstOrFail();
        $product = Product::where('status', 'published')->firstOrFail();

        $response->assertSee(route('district.show', $district->slug));
        $response->assertSee(route('artisan.show', $artisan->slug));
        $response->assertSee(route('products.show', $product->slug));
        $response->assertSee('</urlset>', false);
    }

    public function test_url_redirect_middleware_handles_legacy_urls_and_counts_hits(): void
    {
        $redirect = UrlRedirect::create([
            'source_url' => '/legacy-old-catalog',
            'destination_url' => '/shop',
            'status_code' => 301,
            'hits_count' => 0,
            'is_active' => true,
        ]);

        $response = $this->get('/legacy-old-catalog');
        $response->assertStatus(301);
        $response->assertRedirect('/shop');

        $this->assertEquals(1, $redirect->fresh()->hits_count);
    }
}
