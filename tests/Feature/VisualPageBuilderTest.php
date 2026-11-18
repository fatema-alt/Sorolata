<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\BlockType;
use App\Livewire\Builder\VisualPageBuilder;
use App\Models\Artisan;
use App\Models\District;
use App\Models\Page;
use App\Models\PageRevision;
use App\Models\PageTemplate;
use App\Models\Product;
use App\Services\Builder\DynamicDataParserService;
use Database\Seeders\HeritageCommerceSeeder;
use Database\Seeders\PageTemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class VisualPageBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            HeritageCommerceSeeder::class,
            PageTemplateSeeder::class,
            \Database\Seeders\AdminSeeder::class,
        ]);

        $admin = \App\Models\User::where('role', \App\Enums\AdminRole::SUPER_ADMIN)->first();
        if ($admin) {
            $this->actingAs($admin);
        }
    }

    public function test_builder_route_loads_for_product(): void
    {
        $product = Product::firstOrFail();

        $response = $this->get(route('builder.product', $product->id));

        $response->assertStatus(200);
        $response->assertSee('Studio Engine');
    }

    public function test_builder_route_loads_for_cms_page(): void
    {
        $page = Page::create([
            'title' => 'Heritage Jamdani Exhibition',
            'slug' => 'heritage-jamdani-exhibition',
            'page_type' => 'campaign',
            'status' => 'draft',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'campaign',
                'sections' => [
                    [
                        'id' => 'sec_exhibit_1',
                        'type' => 'hero_cinematic',
                        'is_active' => true,
                        'settings' => ['headline' => 'Living Traditions of Sonargaon'],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Heritage Exhibition',
                'description' => 'Bengal Craft Exhibition',
            ],
        ]);

        $response = $this->get(route('builder.page', $page->id));

        $response->assertStatus(200);
        $response->assertSee('Heritage Jamdani Exhibition');
    }

    public function test_visual_builder_livewire_component_mounts_and_loads_sections(): void
    {
        $product = Product::firstOrFail();

        Livewire::test(VisualPageBuilder::class, ['productId' => $product->id])
            ->assertSet('productId', $product->id)
            ->assertSet('title', $product->title)
            ->assertSet('activeViewport', 'desktop')
            ->assertCount('sections', 7) // default 7 sections
            ->assertSee('Cinematic Hero Showcase');
    }

    public function test_builder_can_switch_viewport_modes(): void
    {
        $product = Product::firstOrFail();

        Livewire::test(VisualPageBuilder::class, ['productId' => $product->id])
            ->call('setViewport', 'tablet')
            ->assertSet('activeViewport', 'tablet')
            ->call('setViewport', 'mobile')
            ->assertSet('activeViewport', 'mobile')
            ->call('setViewport', 'desktop')
            ->assertSet('activeViewport', 'desktop');
    }

    public function test_builder_can_add_reorder_duplicate_and_delete_sections(): void
    {
        $product = Product::firstOrFail();

        $test = Livewire::test(VisualPageBuilder::class, ['productId' => $product->id]);

        $initialCount = count($test->get('sections'));

        // Add section
        $test->call('addSection', BlockType::HERITAGE_PROVENANCE->value);
        $this->assertCount($initialCount + 1, $test->get('sections'));

        $sections = $test->get('sections');
        $lastSection = end($sections);
        $this->assertEquals(BlockType::HERITAGE_PROVENANCE->value, $lastSection['type']);

        // Duplicate section
        $test->call('duplicateSection', $lastSection['id']);
        $this->assertCount($initialCount + 2, $test->get('sections'));

        // Toggle visibility
        $test->call('toggleSection', $lastSection['id']);
        $currentSections = $test->get('sections');
        $updatedSec = collect($currentSections)->firstWhere('id', $lastSection['id']);
        $this->assertFalse($updatedSec['is_active']);

        // Delete section
        $test->call('deleteSection', $lastSection['id']);
        $this->assertCount($initialCount + 1, $test->get('sections'));
    }

    public function test_builder_can_import_pre_built_template(): void
    {
        $product = Product::firstOrFail();
        $template = PageTemplate::firstOrFail();

        Livewire::test(VisualPageBuilder::class, ['productId' => $product->id])
            ->call('importTemplate', $template->id)
            ->assertSet('statusMessage', "Imported template: {$template->name}");
    }

    public function test_builder_can_publish_changes_and_create_revisions(): void
    {
        $product = Product::firstOrFail();

        Livewire::test(VisualPageBuilder::class, ['productId' => $product->id])
            ->call('publish')
            ->assertSet('statusMessage', '✓ Published Live');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('page_revisions', [
            'product_id' => $product->id,
        ]);
    }

    public function test_dynamic_data_parser_service_interpolates_model_tags(): void
    {
        $parser = app(DynamicDataParserService::class);
        $product = Product::with(['artisan', 'district'])->firstOrFail();

        $text = 'Discover {{ product.title }} crafted by {{ artisan.name }} in {{ district.name }}. Price: {{ product.price }}';
        $parsed = $parser->parse($text, $product);

        $this->assertStringContainsString($product->title, $parsed);
        $this->assertStringContainsString($product->artisan->full_name, $parsed);
        $this->assertStringContainsString($product->district->name_en, $parsed);
        $this->assertStringContainsString($product->formatted_price, $parsed);
        $this->assertStringNotContainsString('{{', $parsed);
    }

    public function test_public_landing_page_renders_published_builder_page(): void
    {
        $page = Page::create([
            'title' => 'Centuries of Jamdani Weaving',
            'slug' => 'centuries-of-jamdani',
            'page_type' => 'landing',
            'status' => 'published',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'landing',
                'sections' => [
                    [
                        'id' => 'sec_hero_pub',
                        'type' => 'hero_cinematic',
                        'is_active' => true,
                        'settings' => [
                            'badge_text' => 'GI Certified Royal Heritage',
                            'headline' => 'The Imperial Archival Collection',
                            'subheadline' => 'Handspun cotton threads woven on pit looms alongside the Shitalakshya River.',
                            'bg_color' => '#F8F6F2',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Jamdani Heritage Archival Landing',
                'description' => 'Mughal Jamdani Weaving Masterpieces',
            ],
        ]);

        $response = $this->get(route('builder.landing', $page->slug));

        $response->assertStatus(200);
        $response->assertSee('The Imperial Archival Collection');
        $response->assertSee('GI Certified Royal Heritage');
    }

    public function test_admin_pages_index_and_create_store_lifecycle(): void
    {
        // 1. Pages Studio index loads
        $response = $this->get(route('admin.pages.index'));
        $response->assertStatus(200);
        $response->assertSee('Pages Studio');

        // 2. Admin creates a new custom campaign page
        $storeResponse = $this->post(route('admin.pages.store'), [
            'title' => 'Pohela Boishakh Heirloom Drop',
            'slug' => 'boishakh-heirloom-drop',
            'page_type' => 'festival',
            'status' => 'published',
        ]);

        $page = Page::where('slug', 'boishakh-heirloom-drop')->firstOrFail();
        $storeResponse->assertRedirect(route('builder.page', $page->id));

        // 3. Dynamic route renders the newly created page
        $publicResponse = $this->get(route('builder.page.show', $page->slug));
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('Pohela Boishakh Heirloom Drop');
    }

    public function test_builder_can_add_modular_elements_and_upload_image(): void
    {
        Storage::fake('public');

        $product = Product::firstOrFail();

        $test = Livewire::test(VisualPageBuilder::class, ['productId' => $product->id])
            ->call('setActiveTab', 'elements')
            ->assertSet('activeTab', 'elements')
            ->call('addElement', 'element_heading')
            ->call('addElement', 'element_image')
            ->call('addElement', 'element_product_card')
            ->call('addElement', 'element_button');

        // Verify elements were added
        $sections = $test->get('sections');
        $types = collect($sections)->pluck('type')->toArray();
        $this->assertContains('element_heading', $types);
        $this->assertContains('element_image', $types);
        $this->assertContains('element_product_card', $types);
        $this->assertContains('element_button', $types);

        // Test real image upload without GD dependency
        $file = \Illuminate\Http\UploadedFile::fake()->create('loom_craft.jpg', 100, 'image/jpeg');
        $test->set('uploadedImage', $file);

        // Verify image URL was set to storage path
        $updatedSections = $test->get('sections');
        $selectedIndex = $test->get('selectedIndex');
        $this->assertStringContainsString('/storage/builder_uploads/', $updatedSections[$selectedIndex]['settings']['image_url']);
    }

    public function test_public_landing_renders_modular_elements(): void
    {
        $page = Page::create([
            'title' => 'Modular Elements Showcase',
            'slug' => 'modular-elements-showcase',
            'page_type' => 'landing',
            'status' => 'published',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'landing',
                'sections' => [
                    [
                        'id' => 'sec_el_heading',
                        'type' => 'element_heading',
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Preserved Loom Masterpieces',
                            'alignment' => 'center',
                            'text_color' => '#214E34',
                        ],
                    ],
                    [
                        'id' => 'sec_el_quote',
                        'type' => 'element_artisan_quote',
                        'is_active' => true,
                        'settings' => [
                            'headline' => 'Ustad Dewan',
                            'subheadline' => 'We weave the songs of ancient rivers into 300-count cotton.',
                            'badge_text' => 'National Living Treasure',
                        ],
                    ],
                ],
            ],
            'seo_meta' => [
                'title' => 'Modular Elements Showcase',
                'description' => 'Showcase of Sorolata Elements',
            ],
        ]);

        $response = $this->get(route('builder.landing', $page->slug));
        $response->assertStatus(200);
        $response->assertSee('Preserved Loom Masterpieces');
        $response->assertSee('We weave the songs of ancient rivers');
        $response->assertSee('National Living Treasure');
    }

    public function test_builder_can_insert_element_at_target_index_and_reorder_via_drag_and_drop(): void
    {
        $page = Page::create([
            'title' => 'Drag Drop Test Page',
            'slug' => 'drag-drop-test-page',
            'page_type' => 'landing',
            'status' => 'draft',
            'layout_json' => [
                'version' => 1,
                'page_type' => 'landing',
                'sections' => [
                    [
                        'id' => 'sec_initial_1',
                        'type' => 'hero_cinematic',
                        'is_active' => true,
                        'settings' => ['headline' => 'First Section'],
                    ],
                    [
                        'id' => 'sec_initial_2',
                        'type' => 'specs_anatomy',
                        'is_active' => true,
                        'settings' => ['headline' => 'Second Section'],
                    ],
                ],
            ],
        ]);

        $test = Livewire::test(VisualPageBuilder::class, ['pageId' => $page->id])
            // Drag and drop insert at index 1 (between first and second section)
            ->call('insertElementAt', 'element_heading', 1)
            ->assertSet('statusMessage', 'Dropped Heading Element at position #2');

        $sections = $test->get('sections');
        $this->assertCount(3, $sections);
        $this->assertEquals('element_heading', $sections[1]['type']);
        $this->assertEquals('specs_anatomy', $sections[2]['type']);

        // Now test reorder: move index 2 (specs_anatomy) to index 0 (top)
        $test->call('reorderSections', 2, 0)
            ->assertSet('statusMessage', 'Section reordered');

        $reordered = $test->get('sections');
        $this->assertEquals('specs_anatomy', $reordered[0]['type']);
        $this->assertEquals('hero_cinematic', $reordered[1]['type']);
        $this->assertEquals('element_heading', $reordered[2]['type']);
    }

    public function test_shajgoj_magazine_template_exists_and_renders_content_to_commerce_blocks(): void
    {
        $template = PageTemplate::where('name', 'Shajgoj Content-to-Commerce Magazine Homepage')->first();
        $this->assertNotNull($template);
        $this->assertEquals('home', $template->category);

        $page = Page::where('slug', 'shajgoj-magazine')->first();
        $this->assertNotNull($page);

        $response = $this->get(route('builder.landing', $page->slug));
        $response->assertStatus(200);
        $response->assertSee('Are you a Green or Red Flag?');
        $response->assertSee('TOPIC ARCHIVES');
        $response->assertSee('Heritage Gazette Stories');
        $response->assertSee('AS FEATURED IN OUR STORIES');
        $response->assertSee('Living Chronicles');
        $response->assertSee('SOROLATA HERITAGE GUILD');
    }
}
