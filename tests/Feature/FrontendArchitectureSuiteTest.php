<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Collection;
use App\Models\District;
use App\Models\Order;
use App\Models\Product;
use App\Models\Story;
use Database\Seeders\HeritageCommerceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendArchitectureSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(HeritageCommerceSeeder::class);
    }

    public function test_homepage_renders_all_editorial_sections(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Sorolata');
        $response->assertSee('Where Every Artifact Tells An Irreplaceable Story');
        $response->assertSee('Featured Collections');
        $response->assertSee('Shop by Traditional Guild');
        $response->assertSee('New Arrivals');
        $response->assertSee('Archival Best Sellers');
        $response->assertSee('Curator Distinction');
        $response->assertSee('Our Heritage Manifesto');
        $response->assertSee('Shop by Heritage District');
        $response->assertSee('The Hands Behind The Heirlooms');
        $response->assertSee('Trending Releases');
        $response->assertSee('Cherished by Discerning Collectors');
        $response->assertSee('@SorolataHeritage');
        $response->assertSee('Receive Intimate Archival Dispatches');
    }

    public function test_shop_catalog_page_renders_with_filters(): void
    {
        $response = $this->get(route('shop.index'));

        $response->assertStatus(200);
        $response->assertSee('The Living Archive');
        $response->assertSee('Craft Categories');
        $response->assertSee('Heritage District');
    }

    public function test_category_page_renders_with_guild_context(): void
    {
        $category = Category::firstOrFail();

        $response = $this->get(route('category.show', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertSee('Heritage FAQ');
    }

    public function test_collection_page_renders(): void
    {
        $collection = Collection::firstOrFail();

        $response = $this->get(route('collection.show', $collection->slug));

        $response->assertStatus(200);
        $response->assertSee($collection->title);
        $response->assertSee('The Curation Thesis');
    }

    public function test_districts_index_and_show_pages(): void
    {
        $district = District::firstOrFail();

        $indexResponse = $this->get(route('districts.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Districts of Bengal');

        $showResponse = $this->get(route('district.show', $district->uuid));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($district->name_en);
        $showResponse->assertSee('River Basin');
    }

    public function test_artisans_index_and_show_pages(): void
    {
        $artisan = Artisan::firstOrFail();

        $indexResponse = $this->get(route('artisans.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('The Master Artisans');

        $showResponse = $this->get(route('artisan.show', $artisan->uuid));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($artisan->full_name);
        $showResponse->assertSee('Years Generational Mastery');
    }

    public function test_heritage_gazette_stories_pages(): void
    {
        $story = Story::firstOrFail();

        $indexResponse = $this->get(route('stories.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Chronicles of Bengal Craft');

        $showResponse = $this->get(route('stories.show', $story->slug));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($story->title);
        $showResponse->assertSee('min read');
    }

    public function test_collector_bag_cart_page(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Collector Bag');
    }

    public function test_one_page_checkout_and_order_placement(): void
    {
        $checkoutResponse = $this->get(route('checkout.index'));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('One-Page Checkout');
        $checkoutResponse->assertSee('Payment Selection');

        $processResponse = $this->post(route('checkout.process'), [
            'customer_name' => 'Tariqul Rahman',
            'customer_email' => 'tariqul@example.com',
            'customer_phone' => '+880 1711 222333',
            'delivery_address' => 'House 14, Road 5, Dhanmondi',
            'city_district' => 'Dhaka',
            'payment_gateway' => 'bkash',
            'gift_note' => 'Please include handwritten blessing.',
        ]);

        $processResponse->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'customer_email' => 'tariqul@example.com',
            'customer_name' => 'Tariqul Rahman',
        ]);
    }

    public function test_static_editorial_pages(): void
    {
        $aboutResponse = $this->get(route('pages.about'));
        $aboutResponse->assertStatus(200);
        $aboutResponse->assertSee('Craft as a Sacred Living Continuum');

        $contactResponse = $this->get(route('pages.contact'));
        $contactResponse->assertStatus(200);
        $contactResponse->assertSee('Connect With Our Atelier');

        $wishlistResponse = $this->get(route('wishlist.index'));
        $wishlistResponse->assertStatus(200);
        $wishlistResponse->assertSee('Your Saved Heirlooms');

        $trackResponse = $this->get(route('track.order'));
        $trackResponse->assertStatus(200);
        $trackResponse->assertSee('Track Your Heirloom');

        $searchResponse = $this->get(route('search.index', ['q' => 'Muslin']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('Search results for');
    }
}
