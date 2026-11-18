<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\District;
use App\Models\Product;
use App\Models\Story;
use App\Services\Story\StoryParserService;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Database\Seeders\ShoppableStorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppableStoryCommerceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            ShoppableStorySeeder::class,
            \Database\Seeders\AdminSeeder::class,
        ]);
    }

    public function test_story_parser_service_compiles_product_shortcodes(): void
    {
        $product = Product::firstOrFail();
        $parser = app(StoryParserService::class);

        $rawText = "Before the heirloom piece was spun.\n\n[product:{$product->slug}]\n\nAfter weaving was complete.";
        $parsed = $parser->parse($rawText);

        $this->assertStringContainsString('Add to Bag', $parsed);
        $this->assertStringContainsString($product->title, $parsed);
        $this->assertStringNotContainsString("[product:{$product->slug}]", $parsed);
    }

    public function test_shoppable_story_page_renders_with_in_article_product(): void
    {
        $story = Story::where('slug', 'revival-of-sonargaon-muslin-and-jamdani')->firstOrFail();

        $response = $this->get(route('stories.show', $story->slug));

        $response->assertStatus(200);
        $response->assertSee('Add to Bag');
        $response->assertSee('Featured in this Story');
        $response->assertSee('The Imperial Sonargaon Muslin Scarf');
    }

    public function test_bilingual_language_switcher_and_bangla_rendering(): void
    {
        // 1. Language switch route redirects back and sets session
        $response = $this->get(route('language.switch', 'bn'));
        $response->assertRedirect();
        $response->assertSessionHas('locale', 'bn');

        // 2. Requesting with ?lang=bn sets Bengali output
        $story = Story::where('slug', 'revival-of-sonargaon-muslin-and-jamdani')->firstOrFail();
        $response = $this->withSession(['locale' => 'bn'])->get(route('stories.show', $story->slug));

        $response->assertStatus(200);
        $response->assertSee('সোনারগাঁয়ের লুপ্ত মসলিন ও জামদানির পুনর্জন্ম');
        $response->assertSee('ঝুলিতে যোগ করুন');
        $response->assertSee('এই গল্পে উল্লেখিত হস্তশিল্প');
    }

    public function test_product_page_shows_behind_the_loom_story_link(): void
    {
        $product = Product::where('slug', 'the-imperial-sonargaon-muslin-scarf')->firstOrFail();

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Behind the Loom');
        $response->assertSee('The Revival of Ethereal Jamdani');
    }

    public function test_in_article_ajax_add_to_cart(): void
    {
        $product = Product::firstOrFail();

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 1,
        ]);
        $response->assertJsonStructure(['success', 'message', 'cart_count', 'product']);
    }

    public function test_admin_stories_crud_and_product_attachment(): void
    {
        $admin = \App\Models\User::where('role', \App\Enums\AdminRole::SUPER_ADMIN)->firstOrFail();
        $this->actingAs($admin);

        $product = Product::firstOrFail();

        // 1. Admin stories index loads
        $response = $this->get(route('admin.stories.index'));
        $response->assertStatus(200);

        // 2. Admin story create form loads
        $response = $this->get(route('admin.stories.create'));
        $response->assertStatus(200);
        $response->assertSee('Shoppable Products Inserter');

        // 3. Store new story with linked product
        $storeResponse = $this->post(route('admin.stories.store'), [
            'title' => 'Test Bogura Brassware Artistry',
            'title_bn' => 'বগুড়ার পিতল ও কাঁসাশিল্পের ইতিহাস',
            'slug' => 'test-bogura-brassware-artistry',
            'category' => 'Metal Craft',
            'category_bn' => 'ধাতুশিল্প',
            'author_name' => 'Lead Researcher',
            'author_name_bn' => 'প্রধান গবেষক',
            'reading_time' => 3,
            'content' => "Historic metallurgy.\n\n[product:{$product->slug}]\n\nCenturies of molten brass.",
            'content_bn' => "ঐতিহাসিক কাঁসাশিল্প।\n\n[product:{$product->slug}]\n\nশতবর্ষের পিটানো পিতল।",
            'is_published' => 1,
            'product_ids' => [$product->id],
        ]);

        $storeResponse->assertRedirect(route('admin.stories.index'));
        $this->assertDatabaseHas('stories', ['slug' => 'test-bogura-brassware-artistry']);
        $this->assertDatabaseHas('story_product', ['product_id' => $product->id]);
    }
}
