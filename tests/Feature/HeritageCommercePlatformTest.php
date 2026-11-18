<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Contracts\LayoutEngineContract;
use App\Contracts\SeoMetadataContract;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\District;
use App\Models\Product;
use Database\Seeders\HeritageCommerceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeritageCommercePlatformTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(HeritageCommerceSeeder::class);
    }

    public function test_storefront_homepage_renders_curated_heritage_catalog(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sorolata');
        $response->assertSee('Where Every Artifact Tells An Irreplaceable Story');
        $response->assertSee('The Imperial Sonargaon Muslin Scarf');
        $response->assertSee('Shop by Heritage District');
        $response->assertSee('Featured Collections');
        $response->assertSee('The Hands Behind The Heirlooms');
        $response->assertSee('Cherished by Discerning Collectors');
    }

    public function test_product_landing_page_renders_with_custom_json_blocks(): void
    {
        $product = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->firstOrFail();

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->title);
        $response->assertSee('Master Weaver Alimuddin');
        $response->assertSee('GI Certified Heritage Muslin');
        $response->assertSee('Rooted in Narayanganj');
        $response->assertSee('River Basin');
        $response->assertSee('Acquire Piece');
    }

    public function test_product_page_generates_schema_org_json_ld_with_artisan_creator(): void
    {
        $product = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->firstOrFail();

        /** @var SeoMetadataContract $seoEngine */
        $seoEngine = app(SeoMetadataContract::class);
        $jsonLd = $seoEngine->generateProductJsonLd($product);

        $this->assertEquals('Product', $jsonLd['@type']);
        $this->assertEquals($product->title, $jsonLd['name']);
        $this->assertEquals('BDT', $jsonLd['offers']['priceCurrency']);
        $this->assertEquals('Person', $jsonLd['creator']['@type']);
        $this->assertEquals('Master Weaver Alimuddin', $jsonLd['creator']['name']);
        $this->assertEquals('Bangladesh', $jsonLd['countryOfOrigin']['name']);
    }

    public function test_json_layout_engine_renders_block_sequence(): void
    {
        $product = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->firstOrFail();

        /** @var LayoutEngineContract $layoutEngine */
        $layoutEngine = app(LayoutEngineContract::class);
        $renderedBlocks = $layoutEngine->renderProductBlocks($product);

        $this->assertNotEmpty($renderedBlocks);
        $this->assertCount(8, $renderedBlocks);
    }

    public function test_collector_bag_can_add_heirloom_product(): void
    {
        $product = Product::firstOrFail();

        $response = $this->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sorolata_collector_bag');
        $bag = session('sorolata_collector_bag');
        $this->assertEquals(1, $bag[$product->id]);
    }

    public function test_design_system_components_render_accurately(): void
    {
        $product = Product::firstOrFail();

        $view = $this->blade('<x-product-card :product="$product" />', ['product' => $product]);
        $view->assertSee('rounded-[32px]');
        $view->assertSee($product->title);

        $badgeView = $this->blade('<x-badge variant="terracotta">15% Off</x-badge>');
        $badgeView->assertSee('bg-[#C96A3D]');
        $badgeView->assertSee('15% Off');

        $buttonView = $this->blade('<x-button variant="primary">Acquire Piece</x-button>');
        $buttonView->assertSee('bg-[#214E34]');
        $buttonView->assertSee('Acquire Piece');
    }
}
