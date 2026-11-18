<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Artisan;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Campaign;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\CraftType;
use App\Models\CustomerNote;
use App\Models\CustomerProfile;
use App\Models\District;
use App\Models\Division;
use App\Models\Material;
use App\Models\MediaAsset;
use App\Models\MediaAttachment;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductFaq;
use App\Models\ProductOrigin;
use App\Models\ProductSpecification;
use App\Models\SeoMetadata;
use App\Models\StockAdjustment;
use App\Models\Tag;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use App\Models\Workshop;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DatabaseArchitectureSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            \Database\Seeders\HeritageCommerceSeeder::class,
            \Database\Seeders\PageTemplateSeeder::class,
            \Database\Seeders\CommerceEngineSeeder::class,
            \Database\Seeders\AdminSeeder::class,
            \Database\Seeders\EnterpriseSchemaSeeder::class,
        ]);
    }

    public function test_content_and_commerce_separation_architecture(): void
    {
        $product = Product::firstOrFail();

        // 1. Commerce data lives on Product
        $this->assertNotEmpty($product->sku);
        $this->assertGreaterThan(0, $product->price);
        $this->assertNotNull($product->stock_quantity);

        // 2. Content data lives in separated ProductContent table
        $content = $product->content;
        $this->assertNotNull($content);
        $this->assertInstanceOf(ProductContent::class, $content);
        $this->assertNotEmpty($content->story_title);
        $this->assertNotEmpty($content->craft_technique);
        $this->assertNotEmpty($content->care_guide);

        // 3. Technical specifications live in separated table
        $specs = $product->specifications;
        $this->assertGreaterThanOrEqual(1, $specs->count());
        $this->assertInstanceOf(ProductSpecification::class, $specs->first());

        // 4. Curated FAQs live in separated table
        $faqs = $product->faqs;
        $this->assertGreaterThanOrEqual(1, $faqs->count());
        $this->assertInstanceOf(ProductFaq::class, $faqs->first());
    }

    public function test_heritage_relationship_graph(): void
    {
        // Division -> District -> Artisan -> Workshop -> ProductOrigin
        $division = Division::where('slug', 'dhaka')->firstOrFail();
        $this->assertGreaterThanOrEqual(1, $division->districts()->count());

        $district = $division->districts()->first();
        $this->assertInstanceOf(District::class, $district);
        $this->assertEquals($division->id, $district->division_id);

        $artisan = Artisan::firstOrFail();
        $this->assertInstanceOf(District::class, $artisan->district);

        $workshop = Workshop::firstOrFail();
        $this->assertInstanceOf(Artisan::class, $workshop->artisan);
        $this->assertInstanceOf(District::class, $workshop->district);

        $product = Product::firstOrFail();
        $origin = $product->origin;
        $this->assertNotNull($origin);
        $this->assertInstanceOf(ProductOrigin::class, $origin);
        $this->assertNotNull($origin->craftType);
        $this->assertTrue($origin->craftType->is_gi_certified);
        $this->assertNotEmpty($origin->gi_certificate_number);
    }

    public function test_material_composition_and_percentage(): void
    {
        $product = Product::firstOrFail();
        $materials = $product->materials;

        $this->assertGreaterThanOrEqual(1, $materials->count());
        $firstMat = $materials->first();
        $this->assertInstanceOf(Material::class, $firstMat);
        $this->assertGreaterThan(0, (int) $firstMat->pivot->percentage);
        $this->assertTrue($firstMat->is_sustainable);
    }

    public function test_dynamic_attributes_and_filtering_relationship(): void
    {
        $attribute = Attribute::where('slug', 'color')->firstOrFail();
        $this->assertGreaterThanOrEqual(1, $attribute->values()->count());

        $val = $attribute->values()->first();
        $this->assertInstanceOf(AttributeValue::class, $val);
        $this->assertNotNull($val->color_hex);

        $product = Product::firstOrFail();
        $product->attributeValues()->syncWithoutDetaching([$val->id]);

        $this->assertTrue($product->attributeValues->contains('id', $val->id));
    }

    public function test_polymorphic_seo_metadata_engine(): void
    {
        $product = Product::firstOrFail();
        $seo = $product->seo;

        $this->assertNotNull($seo);
        $this->assertInstanceOf(SeoMetadata::class, $seo);
        $this->assertEquals('Product', $seo->schema_type);
        $this->assertTrue($seo->is_indexable);
        $this->assertIsArray($seo->focus_keywords);

        // Can also attach to Category
        $category = Category::firstOrFail();
        $catSeo = SeoMetadata::updateOrCreate([
            'seoable_type' => Category::class,
            'seoable_id' => $category->id,
        ], [
            'meta_title' => $category->name . ' — Heritage Collection',
            'meta_description' => 'Explore authentic textiles.',
            'schema_type' => 'CollectionPage',
        ]);

        $this->assertEquals($catSeo->id, $category->seo->id);
    }

    public function test_polymorphic_media_attachment_engine(): void
    {
        $product = Product::firstOrFail();
        $media = MediaAsset::firstOrCreate([
            'uuid' => (string) Str::uuid(),
            'attachable_type' => Product::class,
            'attachable_id' => $product->id,
            'file_path' => '/storage/media/flagship-hero.webp',
            'mime_type' => 'image/webp',
            'file_size' => 204800,
            'width' => 1920,
            'height' => 1080,
            'alt_text' => 'High resolution Jamdani weave detail',
        ]);

        $attachment = MediaAttachment::create([
            'media_asset_id' => $media->id,
            'mediable_type' => Product::class,
            'mediable_id' => $product->id,
            'role' => 'hero',
            'sort_order' => 1,
        ]);

        $this->assertInstanceOf(MediaAttachment::class, $attachment);
        $this->assertEquals($product->id, $attachment->mediable->id);
        $this->assertTrue($product->mediaAttachments->contains('id', $attachment->id));
    }

    public function test_multi_warehouse_inventory_and_stock_adjustments(): void
    {
        $warehouse = Warehouse::where('code', 'WH-DHK-01')->firstOrFail();
        $product = Product::firstOrFail();

        $stock = WarehouseStock::where('warehouse_id', $warehouse->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        $this->assertGreaterThanOrEqual(1, $stock->quantity_on_hand);

        // Record stock adjustment with polymorphic reference
        $order = Order::first();
        $adjustment = StockAdjustment::create([
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'delta' => -1,
            'reason' => 'sold',
            'reference_type' => $order ? Order::class : null,
            'reference_id' => $order?->id,
            'user_id' => User::first()?->id,
        ]);

        $this->assertInstanceOf(StockAdjustment::class, $adjustment);
        $this->assertEquals(-1, $adjustment->delta);
    }

    public function test_customer_profile_crm_and_persistent_cart(): void
    {
        $user = User::firstOrFail();

        $profile = $user->profile;
        $this->assertNotNull($profile);
        $this->assertInstanceOf(CustomerProfile::class, $profile);
        $this->assertEquals('patron', $profile->membership_tier);

        // Staff CRM Note
        $note = CustomerNote::create([
            'customer_id' => $user->id,
            'staff_id' => $user->id,
            'note' => 'Preferred patron for rare Jamdani exhibitions.',
            'is_pinned' => true,
        ]);

        $this->assertTrue($user->customerNotes->contains('id', $note->id));

        // Persistent Cart & Items
        $cart = Cart::create([
            'uuid' => (string) Str::uuid(),
            'user_id' => $user->id,
            'currency' => 'BDT',
            'total_amount' => 15000.00,
        ]);

        $product = Product::firstOrFail();
        $item = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unit_price' => 15000.00,
            'custom_options' => ['gift_wrap' => true],
        ]);

        $this->assertEquals(1, $cart->items()->count());
        $this->assertEquals($item->id, $cart->items->first()->id);
    }

    public function test_navigation_hierarchy_and_menu_items(): void
    {
        $menu = Menu::where('code', 'header_main')->firstOrFail();
        $this->assertGreaterThanOrEqual(4, $menu->items()->count());

        $firstItem = $menu->items()->first();
        $this->assertInstanceOf(MenuItem::class, $firstItem);
        $this->assertNotEmpty($firstItem->title);
        $this->assertNotEmpty($firstItem->url);
    }

    public function test_uuid_and_soft_delete_integrity(): void
    {
        // 1. CraftType UUID & Soft Delete
        $craft = CraftType::create([
            'name' => 'Dhamrai Bell Metal Craft',
            'bn_name' => 'ধামরাই কাঁসা শিল্প',
            'description' => 'Ancient lost-wax bell metal casting.',
        ]);
        $this->assertNotEmpty($craft->uuid);
        $craft->delete();
        $this->assertSoftDeleted($craft);

        // 2. Workshop UUID & Soft Delete
        $workshop = Workshop::create([
            'name' => 'Dhamrai Metalworks',
            'location' => 'Dhamrai',
        ]);
        $this->assertNotEmpty($workshop->uuid);
        $workshop->delete();
        $this->assertSoftDeleted($workshop);

        // 3. Campaign UUID & Soft Delete
        $campaign = Campaign::create([
            'title' => 'Pahela Baishakh Heritage Showcase',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
        ]);
        $this->assertNotEmpty($campaign->uuid);
        $campaign->delete();
        $this->assertSoftDeleted($campaign);
    }
}
