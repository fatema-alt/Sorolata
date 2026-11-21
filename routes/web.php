<?php

use App\Http\Controllers\AccountPageController;
use App\Http\Controllers\ArtisanPageController;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryPageController;
use App\Http\Controllers\CheckoutPageController;
use App\Http\Controllers\CollectionPageController;
use App\Http\Controllers\DistrictPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\StoryPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sorolata Storefront Web Routes
|--------------------------------------------------------------------------
*/

// Language Switcher
Route::get('/language/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'bn'], true)) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

// Failsafe Auto-Clean Redirects: Strip any /public/ or /sorolata/ from incoming URLs
Route::any('/public/{fallbackPlaceholder}', function (string $fallbackPlaceholder) {
    $clean = preg_replace('#^sorolata/?(public/?)?#', '', $fallbackPlaceholder);
    return redirect('/' . ltrim($clean, '/'), 301);
})->where('fallbackPlaceholder', '.*');

Route::any('/sorolata/{fallbackPlaceholder}', function (string $fallbackPlaceholder) {
    $clean = preg_replace('#^public/?#', '', $fallbackPlaceholder);
    return redirect('/' . ltrim($clean, '/'), 301);
})->where('fallbackPlaceholder', '.*');

Route::get('/public', fn() => redirect('/', 301));
Route::get('/sorolata', fn() => redirect('/', 301));

// Direct Storage Delivery Fallback (Bypasses missing symlink on cPanel/Hostinger shared hosting)
Route::get('/storage/{path}', function (string $path) {
    $cleanPath = str_replace(['..', "\0"], '', $path);
    $cleanPath = ltrim($cleanPath, '/\\');

    $fullPath = storage_path('app/public/' . $cleanPath);
    if (file_exists($fullPath) && is_file($fullPath)) {
        return response()->file($fullPath, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    $altPath = public_path('storage/' . $cleanPath);
    if (file_exists($altPath) && is_file($altPath)) {
        return response()->file($altPath, [
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    abort(404);
})->where('path', '.*')->name('storage.delivery');

// SEO XML Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Home & Catalog Discovery
Route::get('/', [ProductController::class, 'index'])->name('storefront.home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/category/{slug}', [CategoryPageController::class, 'show'])->name('category.show');
Route::get('/categories/{slug}', fn(string $slug) => redirect()->route('category.show', $slug, 301));
Route::get('/collection/{slug}', [CollectionPageController::class, 'show'])->name('collection.show');
Route::get('/collections/{slug}', fn(string $slug) => redirect()->route('collection.show', $slug, 301))->name('collections.show');

// Geographical & Human Heritage Hubs
Route::get('/districts', [DistrictPageController::class, 'index'])->name('districts.index');
Route::get('/district/{slug}', [DistrictPageController::class, 'show'])->name('district.show');
Route::get('/districts/{slug}', fn(string $slug) => redirect()->route('district.show', $slug, 301));
Route::get('/artisans', [ArtisanPageController::class, 'index'])->name('artisans.index');
Route::get('/artisan/{slug}', [ArtisanPageController::class, 'show'])->name('artisan.show');
Route::get('/artisans/{slug}', fn(string $slug) => redirect()->route('artisan.show', $slug, 301));

// Heritage Journal & Stories
Route::get('/stories', [StoryPageController::class, 'index'])->name('stories.index');
Route::get('/stories/{slug}', [StoryPageController::class, 'show'])->name('stories.show');
Route::get('/story/{slug}', fn(string $slug) => redirect()->route('stories.show', $slug, 301));

// Product Bespoke Landing Page & Reviews
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{slug}', fn(string $slug) => redirect()->route('products.show', $slug, 301));
Route::post('/product/{slug}/review', [ProductController::class, 'submitReview'])->name('products.review.submit');

// Cart & Frictionless Checkout Flow
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout', [CheckoutPageController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutPageController::class, 'process'])->name('checkout.process');
Route::post('/checkout/coupon/apply', [CheckoutPageController::class, 'applyCoupon'])->name('checkout.coupon.apply');
Route::post('/checkout/coupon/remove', [CheckoutPageController::class, 'removeCoupon'])->name('checkout.coupon.remove');

// Customer Storefront Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Customer\CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Customer\CustomerAuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [\App\Http\Controllers\Customer\CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Customer\CustomerAuthController::class, 'register'])->name('register.submit');
});
Route::post('/logout', [\App\Http\Controllers\Customer\CustomerAuthController::class, 'logout'])->name('customer.logout')->middleware('auth');

// Payment Gateway Hosted & Callback Endpoints
Route::get('/payment/manual/{order}', [\App\Http\Controllers\PaymentController::class, 'manualInstruction'])->name('payment.manual.instruction');
Route::post('/payment/manual/submit/{order}', [\App\Http\Controllers\PaymentController::class, 'submitManualPayment'])->name('payment.manual.submit');
Route::get('/payment/bkash/checkout/{order}', [\App\Http\Controllers\PaymentController::class, 'bkashCheckout'])->name('payment.bkash.checkout');
Route::match(['get', 'post'], '/payment/bkash/callback/{order}', [\App\Http\Controllers\PaymentController::class, 'bkashCallback'])->name('payment.bkash.callback');

Route::get('/payment/sslcommerz/checkout/{order}', [\App\Http\Controllers\PaymentController::class, 'sslcommerzCheckout'])->name('payment.sslcommerz.checkout');
Route::post('/payment/sslcommerz/success', [\App\Http\Controllers\PaymentController::class, 'sslcommerzSuccess'])->name('payment.sslcommerz.success');
Route::post('/payment/sslcommerz/fail', [\App\Http\Controllers\PaymentController::class, 'sslcommerzFail'])->name('payment.sslcommerz.fail');
Route::get('/payment/sslcommerz/cancel', [\App\Http\Controllers\PaymentController::class, 'sslcommerzCancel'])->name('payment.sslcommerz.cancel');
Route::post('/payment/sslcommerz/ipn', [\App\Http\Controllers\PaymentController::class, 'sslcommerzIpn'])->name('payment.sslcommerz.ipn');

// Account Dashboard & Order Tracking
Route::get('/account', [AccountPageController::class, 'index'])->name('account.index');
Route::post('/account/profile', [AccountPageController::class, 'updateProfile'])->name('account.profile.update');
Route::get('/track-order', [StaticPageController::class, 'trackOrder'])->name('track.order');
Route::post('/track-order/cancel-request/{order}', [StaticPageController::class, 'requestOrderCancellation'])->name('track.order.cancel-request');

// Curator Wishlist
Route::get('/wishlist', [StaticPageController::class, 'wishlist'])->name('wishlist.index');
Route::post('/wishlist/toggle', [StaticPageController::class, 'toggleWishlist'])->name('wishlist.toggle');

// Static Editorial Pages & Search
Route::get('/about', [StaticPageController::class, 'about'])->name('pages.about');
Route::get('/contact', [StaticPageController::class, 'contact'])->name('pages.contact');
Route::get('/search', [StaticPageController::class, 'search'])->name('search.index');
Route::get('/docs/api', fn() => view('docs.api'))->name('docs.api');

// Visual Page Builder Studio & Public Landing Engine
Route::get('/admin/builder/product/{id}', [BuilderController::class, 'product'])->name('builder.product')->middleware(['admin']);
Route::get('/admin/builder/page/{id}', [BuilderController::class, 'page'])->name('builder.page')->middleware(['admin']);
Route::get('/builder/product/{id}', fn($id) => redirect()->route('builder.product', $id))->middleware(['admin']);
Route::get('/builder/page/{id}', fn($id) => redirect()->route('builder.page', $id))->middleware(['admin']);
Route::get('/landing/{slug}', [BuilderController::class, 'landing'])->name('builder.landing');
Route::get('/page/{slug}', [BuilderController::class, 'landing'])->name('builder.page.show');

/*
|--------------------------------------------------------------------------
| Sorolata Enterprise CMS & Admin Studio Routes
|--------------------------------------------------------------------------
*/

// Admin Guest Authentication Routes
Route::get('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.submit');

// Protected Admin Studio Operations
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function (): void {
    // Session & Profile Lifecycle
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminAuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Admin\AdminAuthController::class, 'updateProfile'])->name('profile.update');

    // Executive Dashboard & Command Search
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/command-search', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'commandSearch'])->name('command-search');

    // Visual CMS Pages Management
    Route::get('/pages', [\App\Http\Controllers\Admin\AdminPageController::class, 'index'])->name('pages.index');
    Route::post('/pages', [\App\Http\Controllers\Admin\AdminPageController::class, 'store'])->name('pages.store');
    Route::delete('/pages/{id}', [\App\Http\Controllers\Admin\AdminPageController::class, 'destroy'])->name('pages.destroy');

    // Artifacts Catalog & Product Management
    Route::get('/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [\App\Http\Controllers\Admin\AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [\App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/{id}/duplicate', [\App\Http\Controllers\Admin\AdminProductController::class, 'duplicate'])->name('products.duplicate');
    Route::delete('/products/{id}/media/{mediaId}', [\App\Http\Controllers\Admin\AdminProductController::class, 'deleteMedia'])->name('products.media.destroy');

    // Orders & Collector Fulfillment
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{id}/verify-payment', [\App\Http\Controllers\Admin\AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::post('/orders/{id}/reject-payment', [\App\Http\Controllers\Admin\AdminOrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('/orders/{id}/shipping', [\App\Http\Controllers\Admin\AdminOrderController::class, 'updateShipping'])->name('orders.update-shipping');
    Route::post('/orders/{id}/dispatch-steadfast', [\App\Http\Controllers\Admin\AdminOrderController::class, 'dispatchSteadfast'])->name('orders.dispatch-steadfast');
    Route::post('/orders/{id}/cancellation-approve', [\App\Http\Controllers\Admin\AdminOrderController::class, 'approveCancellation'])->name('orders.cancellation-approve');
    Route::post('/orders/{id}/cancellation-decline', [\App\Http\Controllers\Admin\AdminOrderController::class, 'declineCancellation'])->name('orders.cancellation-decline');
    Route::get('/orders/{id}/invoice', [\App\Http\Controllers\Admin\AdminOrderController::class, 'invoice'])->name('orders.invoice');

    // Collectors & Patrons Directory
    Route::get('/customers', [\App\Http\Controllers\Admin\AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [\App\Http\Controllers\Admin\AdminCustomerController::class, 'show'])->name('customers.show');

    // Reviews Moderation
    Route::get('/reviews', [\App\Http\Controllers\Admin\AdminReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/reply', [\App\Http\Controllers\Admin\AdminReviewController::class, 'reply'])->name('reviews.reply');
    Route::delete('/reviews/{id}', [\App\Http\Controllers\Admin\AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Discounts & Coupons
    Route::get('/discounts', [\App\Http\Controllers\Admin\AdminDiscountController::class, 'index'])->name('discounts.index');
    Route::post('/discounts', [\App\Http\Controllers\Admin\AdminDiscountController::class, 'store'])->name('discounts.store');
    Route::delete('/discounts/{id}', [\App\Http\Controllers\Admin\AdminDiscountController::class, 'destroy'])->name('discounts.destroy');

    // Media Library
    Route::get('/media', [\App\Http\Controllers\Admin\AdminMediaController::class, 'index'])->name('media.index');

    // Editorial Stories & Content-to-Commerce CMS
    Route::resource('stories', \App\Http\Controllers\Admin\AdminStoryController::class);

    // Provenance Hubs: Artisans, Districts, Collections
    Route::get('/artisans', [\App\Http\Controllers\Admin\AdminArtisanDistrictController::class, 'artisans'])->name('artisans.index');
    Route::get('/districts', [\App\Http\Controllers\Admin\AdminArtisanDistrictController::class, 'districts'])->name('districts.index');
    Route::get('/collections', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'store'])->name('collections.store');
    Route::put('/collections/{id}', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{id}', [\App\Http\Controllers\Admin\AdminCollectionController::class, 'destroy'])->name('collections.destroy');

    // Storefront Settings & Configuration
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/backup/create', [\App\Http\Controllers\Admin\AdminSettingController::class, 'createBackup'])->name('settings.backup.create');
    Route::get('/settings/backup/download/{filename}', [\App\Http\Controllers\Admin\AdminSettingController::class, 'downloadBackup'])->name('settings.backup.download');
    Route::delete('/settings/backup/{filename}', [\App\Http\Controllers\Admin\AdminSettingController::class, 'deleteBackup'])->name('settings.backup.delete');

    // Activity Audit Ledger
    Route::get('/logs', [\App\Http\Controllers\Admin\AdminActivityLogController::class, 'index'])->name('logs.index');
});
