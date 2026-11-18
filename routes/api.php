<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthApiController;
use App\Http\Controllers\Api\V1\BuilderApiController;
use App\Http\Controllers\Api\V1\CartApiController;
use App\Http\Controllers\Api\V1\CheckoutApiController;
use App\Http\Controllers\Api\V1\CmsApiController;
use App\Http\Controllers\Api\V1\CustomerApiController;
use App\Http\Controllers\Api\V1\HeritageApiController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\SearchApiController;
use App\Http\Controllers\Api\V1\SystemApiController;
use App\Http\Controllers\Api\V1\TaxonomyApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sorolata Commerce Platform REST API Architecture (v1)
|--------------------------------------------------------------------------
| Production-ready, stateless, versioned, API-first framework supporting
| Web, iOS/Android mobile apps, third-party integrations, and headless frontends.
*/

Route::prefix('v1')->group(function (): void {

    // ==========================================
    // 1. Authentication & Sanctum Token Lifecycle
    // ==========================================
    Route::prefix('auth')->group(function (): void {
        Route::post('/register', [AuthApiController::class, 'register']);
        Route::post('/login', [AuthApiController::class, 'login']);

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::post('/logout', [AuthApiController::class, 'logout']);
            Route::get('/me', [AuthApiController::class, 'me']);
            Route::post('/update-password', [AuthApiController::class, 'updatePassword']);
        });
    });

    // ==========================================
    // 2. Products Catalog & Curated Reviews
    // ==========================================
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{uuidOrSlug}', [ProductApiController::class, 'show']);
    Route::get('/products/{id}/related', [ProductApiController::class, 'related']);
    Route::get('/products/{id}/reviews', [ProductApiController::class, 'reviews']);
    Route::post('/products/{id}/reviews', [ProductApiController::class, 'submitReview'])->middleware('auth:sanctum');

    // ==========================================
    // 3. Taxonomies: Categories & Collections
    // ==========================================
    Route::get('/categories', [TaxonomyApiController::class, 'categories']);
    Route::get('/categories/{slug}', [TaxonomyApiController::class, 'category']);
    Route::get('/collections', [TaxonomyApiController::class, 'collections']);
    Route::get('/collections/{slug}', [TaxonomyApiController::class, 'collection']);

    // ==========================================
    // 4. Heritage: Divisions, Districts & Artisans
    // ==========================================
    Route::get('/divisions', [HeritageApiController::class, 'divisions']);
    Route::get('/districts', [HeritageApiController::class, 'districts']);
    Route::get('/districts/{slugOrName}', [HeritageApiController::class, 'district']);
    Route::get('/artisans', [HeritageApiController::class, 'artisans']);
    Route::get('/artisans/{uuidOrId}', [HeritageApiController::class, 'artisan']);

    // ==========================================
    // 5. Persistent Shopping Cart Operations
    // ==========================================
    Route::get('/cart', [CartApiController::class, 'show']);
    Route::post('/cart/items', [CartApiController::class, 'addItem']);
    Route::put('/cart/items/{itemId}', [CartApiController::class, 'updateItem']);
    Route::delete('/cart/items/{itemId}', [CartApiController::class, 'removeItem']);
    Route::post('/cart/coupon', [CartApiController::class, 'applyCoupon']);
    Route::delete('/cart/coupon', [CartApiController::class, 'removeCoupon']);

    // ==========================================
    // 6. Checkout & Order Lifecycle
    // ==========================================
    Route::get('/checkout/gateways', [CheckoutApiController::class, 'gateways']);
    Route::get('/checkout/shipping-rates', [CheckoutApiController::class, 'shippingRates']);
    Route::post('/checkout/calculate', [CheckoutApiController::class, 'calculate']);
    Route::post('/checkout/process', [CheckoutApiController::class, 'process']);
    Route::post('/checkout', [CheckoutApiController::class, 'process']); // legacy alias

    Route::get('/orders', [OrderApiController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/orders/{orderNumber}', [OrderApiController::class, 'show']);
    Route::get('/orders/{orderNumber}/tracking', [OrderApiController::class, 'tracking']);
    Route::get('/orders/{orderNumber}/invoice', [OrderApiController::class, 'invoice']);
    Route::post('/orders/{orderNumber}/return', [OrderApiController::class, 'requestReturn']);

    // ==========================================
    // 7. Customer CRM: Profile, Addresses & Wishlist
    // ==========================================
    Route::prefix('customer')->group(function (): void {
        Route::get('/wishlist', [CustomerApiController::class, 'wishlist']);
        Route::post('/wishlist/toggle', [CustomerApiController::class, 'toggleWishlist']);

        Route::middleware('auth:sanctum')->group(function (): void {
            Route::get('/profile', [CustomerApiController::class, 'profile']);
            Route::put('/profile', [CustomerApiController::class, 'updateProfile']);
            Route::get('/addresses', [CustomerApiController::class, 'addresses']);
            Route::post('/addresses', [CustomerApiController::class, 'saveAddress']);
        });
    });

    // ==========================================
    // 8. CMS: Mobile Homepage, Stories & Pages
    // ==========================================
    Route::get('/homepage/sections', [CmsApiController::class, 'homepageSections']);
    Route::get('/stories', [CmsApiController::class, 'stories']);
    Route::get('/stories/{slug}', [CmsApiController::class, 'story']);
    Route::get('/pages/{slug}', [CmsApiController::class, 'page']);

    // ==========================================
    // 9. Visual Builder Headless JSON Layouts
    // ==========================================
    Route::get('/builder/layout/{type}/{idOrSlug}', [BuilderApiController::class, 'layout']);
    Route::get('/builder/components', [BuilderApiController::class, 'globalComponents']);

    // ==========================================
    // 10. Search & Discovery Engine
    // ==========================================
    Route::get('/search', [SearchApiController::class, 'search']);
    Route::get('/search/suggestions', [SearchApiController::class, 'suggestions']);

    // ==========================================
    // 11. System Configuration & Feature Flags
    // ==========================================
    Route::get('/system/config', [SystemApiController::class, 'config']);
    Route::get('/notifications', [SystemApiController::class, 'notifications'])->middleware('auth:sanctum');
});
