<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateway;
use App\Enums\PaymentStatus;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\CustomerProfile;
use App\Models\Discount;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerStorefrontProductionSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected District $district;
    protected Artisan $artisan;
    protected Category $category;
    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->district = District::create([
            'name_en' => 'Tangail',
            'name_bn' => 'টাঙ্গাইল',
            'division' => \App\Enums\DistrictRegion::DHAKA,
            'slug' => 'tangail',
            'is_active' => true,
        ]);

        $this->artisan = Artisan::create([
            'full_name' => 'Basudeb Basak',
            'slug' => 'basudeb-basak',
            'district_id' => $this->district->id,
            'craft_type' => \App\Enums\ArtisanCraftType::JAMDANI_WEAVING,
            'experience_years' => 30,
            'is_verified' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Heritage Handloom',
            'slug' => 'heritage-handloom',
        ]);

        $this->product = Product::create([
            'title' => 'Tangail Mulberry Silk Jamdani',
            'slug' => 'tangail-mulberry-silk-jamdani',
            'subtitle' => 'Heirloom Handloom Saree',
            'description' => 'Fine handcrafted silk saree woven in Tangail.',
            'category_id' => $this->category->id,
            'district_id' => $this->district->id,
            'artisan_id' => $this->artisan->id,
            'price' => 15000.00,
            'currency' => 'BDT',
            'status' => 'published',
            'sku' => 'TNG-JMD-001',
            'stock' => 5,
        ]);
    }

    /** @test */
    public function track_order_page_does_not_leak_stranger_customer_data_when_unqueried(): void
    {
        // Given an order exists in the database
        $order = Order::create([
            'order_number' => 'SRL-2026-SECRET',
            'customer_name' => 'Private Customer',
            'customer_email' => 'private@example.com',
            'customer_phone' => '01888888888',
            'shipping_address' => 'Secret Home, Gulshan-2, Dhaka',
            'city_district' => 'Dhaka',
            'subtotal_amount' => 15000.00,
            'shipping_fee' => 80.00,
            'discount_amount' => 0.00,
            'total_amount' => 15080.00,
            'payment_gateway' => PaymentGateway::BKASH,
            'payment_status' => PaymentStatus::PAID,
            'status' => OrderStatus::CONFIRMED,
        ]);

        // When accessing /track-order without search query
        $response = $this->get(route('track.order'));

        $response->assertStatus(200);
        // It must NOT display the private customer's data
        $response->assertDontSee('Private Customer');
        $response->assertDontSee('private@example.com');
        $response->assertDontSee('01888888888');
        $response->assertDontSee('Secret Home, Gulshan-2');
        $response->assertSee(__('Order Tracking & Delivery Status'));
    }

    /** @test */
    public function customer_can_register_and_is_authenticated(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Nusrat Jahan',
            'email' => 'nusrat@example.com',
            'phone' => '01712345678',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'terms' => '1',
        ]);

        $response->assertRedirect(route('account.index'));
        $this->assertAuthenticated();

        $user = User::where('email', 'nusrat@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Nusrat Jahan', $user->name);
        $this->assertEquals('01712345678', $user->phone);
        $this->assertTrue(Hash::check('SecurePass123!', $user->password));

        // CustomerProfile record created
        $this->assertDatabaseHas('customer_profiles', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function customer_can_login_with_email_and_logout(): void
    {
        $user = User::create([
            'name' => 'Rafiqul Islam',
            'email' => 'rafiq@example.com',
            'phone' => '01799999999',
            'password' => Hash::make('MySecretPass@1'),
            'role' => 'customer',
        ]);

        $response = $this->post(route('login.submit'), [
            'login' => 'rafiq@example.com',
            'password' => 'MySecretPass@1',
        ]);

        $response->assertRedirect(route('account.index'));
        $this->assertAuthenticatedAs($user);

        // Logout
        $logoutResponse = $this->post(route('customer.logout'));
        $logoutResponse->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function customer_can_login_with_phone_number(): void
    {
        $user = User::create([
            'name' => 'Tanvir Ahmed',
            'email' => 'tanvir@example.com',
            'phone' => '01811223344',
            'password' => Hash::make('ValidPass123!'),
            'role' => 'customer',
        ]);

        $response = $this->post(route('login.submit'), [
            'login' => '01811223344',
            'password' => 'ValidPass123!',
        ]);

        $response->assertRedirect(route('account.index'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function customer_profile_update_persists_changes(): void
    {
        $user = User::create([
            'name' => 'Original Name',
            'email' => 'update_test@example.com',
            'phone' => '01700112233',
            'password' => Hash::make('Pass1234!'),
            'role' => 'customer',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('account.profile.update'), [
            'name' => 'Updated Collector Name',
            'phone' => '01999887766',
            'address' => 'House 42, Road 11, Banani, Dhaka',
            'district' => 'Dhaka',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Collector Name',
            'phone' => '01999887766',
        ]);

        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->id,
            'address_line_1' => 'House 42, Road 11, Banani, Dhaka',
            'city' => 'Dhaka',
        ]);
    }

    /** @test */
    public function checkout_coupon_application_and_removal(): void
    {
        // Setup discount in database
        $discount = Discount::create([
            'name' => '10% Off Eid Promo',
            'code' => 'PROMO10',
            'type' => DiscountType::PERCENTAGE,
            'value' => 10.00, // 10%
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addMonth(),
        ]);

        // Put product in session bag: subtotal 15000
        $sessionData = ['sorolata_collector_bag' => [$this->product->id => 1]];

        // Apply valid coupon
        $response = $this->from(route('checkout.index'))
            ->withSession($sessionData)
            ->post(route('checkout.coupon.apply'), [
                'coupon_code' => 'promo10', // case insensitive test
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('coupon_success');
        $response->assertSessionHas('checkout_coupon', function ($coupon) {
            return $coupon['code'] === 'PROMO10' && $coupon['amount'] == 1500.00; // 10% of 15000
        });

        // Remove coupon
        $removeResponse = $this->from(route('checkout.index'))
            ->withSession($sessionData + [
                'checkout_coupon' => ['code' => 'PROMO10', 'amount' => 1500.00]
            ])->post(route('checkout.coupon.remove'));

        $removeResponse->assertRedirect(route('checkout.index'));
        $removeResponse->assertSessionMissing('checkout_coupon');
    }

    /** @test */
    public function checkout_rejects_expired_or_invalid_coupon(): void
    {
        Discount::create([
            'name' => 'Expired Winter Clearance',
            'code' => 'EXPIRED20',
            'type' => DiscountType::FIXED_AMOUNT,
            'value' => 500.00,
            'is_active' => true,
            'starts_at' => now()->subMonths(2),
            'ends_at' => now()->subDay(),
        ]);

        $sessionData = ['sorolata_collector_bag' => [$this->product->id => 1]];

        $response = $this->from(route('checkout.index'))
            ->withSession($sessionData)
            ->post(route('checkout.coupon.apply'), [
                'coupon_code' => 'EXPIRED20',
            ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('coupon_error');
        $response->assertSessionMissing('checkout_coupon');
    }

    /** @test */
    public function wishlist_can_be_toggled_in_session_and_database(): void
    {
        // 1. Guest toggle (adds to session)
        $response = $this->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);

        $response->assertRedirect();
        $this->assertEquals([$this->product->id], session('sorolata_wishlist'));

        // Toggle again (removes from session)
        $response2 = $this->withSession(['sorolata_wishlist' => [$this->product->id]])
            ->post(route('wishlist.toggle'), [
                'product_id' => $this->product->id,
            ]);

        $response2->assertRedirect();
        $this->assertEquals([], session('sorolata_wishlist'));

        // 2. Authenticated toggle (saves to Wishlist model table)
        $user = User::create([
            'name' => 'Wishlist User',
            'email' => 'wishlist@example.com',
            'password' => Hash::make('Pass123!'),
            'role' => 'customer',
        ]);

        $this->actingAs($user)->post(route('wishlist.toggle'), [
            'product_id' => $this->product->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $this->product->id,
        ]);
    }

    /** @test */
    public function customer_can_submit_product_review_with_verified_purchase_check(): void
    {
        $user = User::create([
            'name' => 'Verified Buyer',
            'email' => 'buyer@example.com',
            'password' => Hash::make('Pass123!'),
            'role' => 'customer',
        ]);

        // Create an order for this user containing the product
        $order = Order::create([
            'user_id' => $user->id,
            'order_number' => 'SRL-2026-BUYER-01',
            'customer_name' => 'Verified Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '01711223344',
            'shipping_address' => 'Gulshan, Dhaka',
            'city_district' => 'Dhaka',
            'subtotal_amount' => 15000.00,
            'shipping_fee' => 80.00,
            'total_amount' => 15080.00,
            'payment_gateway' => PaymentGateway::BKASH,
            'payment_status' => PaymentStatus::PAID,
            'status' => OrderStatus::DELIVERED,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_title' => $this->product->title,
            'sku' => $this->product->sku,
            'quantity' => 1,
            'unit_price' => 15000.00,
            'subtotal' => 15000.00,
        ]);

        // Submit review as authenticated buyer
        $response = $this->actingAs($user)->post(route('products.review.submit', $this->product->slug), [
            'rating' => 5,
            'author_name' => 'Verified Buyer',
            'author_location' => 'ঢাকা, বাংলাদেশ',
            'title' => 'অনবদ্য সিল্কের বুনন',
            'content' => 'এই শাড়িটির সুতার মান এবং পাড়ের নকশা সত্যিই চমৎকার। কারিগরের হাতের কাজ অসাধারণ।',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('review_success');

        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'is_verified_purchase' => true,
            'title' => 'অনবদ্য সিল্কের বুনন',
        ]);
    }
}
