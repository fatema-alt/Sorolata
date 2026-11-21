<?php

namespace App\Providers;

use App\Contracts\ImageProcessorContract;
use App\Contracts\LayoutEngineContract;
use App\Contracts\SeoMetadataContract;
use App\Contracts\StorytellerContract;
use App\Services\Builder\BlockRegistry;
use App\Services\Builder\LayoutEngineService;
use App\Services\Media\ImageProcessingService;
use App\Services\SEO\SeoEngineService;
use App\Services\Storyteller\StorytellerService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BlockRegistry::class, function () {
            return new BlockRegistry();
        });

        $this->app->bind(LayoutEngineContract::class, LayoutEngineService::class);
        $this->app->bind(SeoMetadataContract::class, SeoEngineService::class);
        $this->app->bind(StorytellerContract::class, StorytellerService::class);
        $this->app->bind(ImageProcessorContract::class, ImageProcessingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('districts') && !\Illuminate\Support\Facades\Schema::hasColumn('districts', 'slug')) {
                \Illuminate\Support\Facades\Schema::table('districts', function (\Illuminate\Database\Schema\Blueprint $table): void {
                    $table->string('slug')->nullable()->after('uuid');
                });
                $records = \Illuminate\Support\Facades\DB::table('districts')->select('id', 'name_en')->get();
                foreach ($records as $r) {
                    \Illuminate\Support\Facades\DB::table('districts')
                        ->where('id', $r->id)
                        ->update(['slug' => \Illuminate\Support\Str::slug((string) $r->name_en)]);
                }
            }

            if (\Illuminate\Support\Facades\Schema::hasTable('admin_settings')) {
                \Illuminate\Support\Facades\DB::table('admin_settings')->updateOrInsert(
                    ['key' => 'gtm_id'],
                    ['value' => 'GTM-W62NQS6R', 'group' => 'tracking', 'updated_at' => now()]
                );
                \Illuminate\Support\Facades\DB::table('admin_settings')->updateOrInsert(
                    ['key' => 'gtm_enabled'],
                    ['value' => '1', 'group' => 'tracking', 'updated_at' => now()]
                );
                \Illuminate\Support\Facades\DB::table('admin_settings')->updateOrInsert(
                    ['key' => 'tracking_enabled'],
                    ['value' => '1', 'group' => 'tracking', 'updated_at' => now()]
                );
                \Illuminate\Support\Facades\Cache::forget('sorolata_admin_settings_all');
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('product_restock_requests')) {
                \Illuminate\Support\Facades\Schema::create('product_restock_requests', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('product_id')->index();
                    $table->string('customer_name')->nullable();
                    $table->string('phone');
                    $table->string('email')->nullable();
                    $table->boolean('is_notified')->default(false);
                    $table->timestamp('notified_at')->nullable();
                    $table->timestamps();
                });
            }

            if (!\Illuminate\Support\Facades\Schema::hasTable('abandoned_checkouts')) {
                \Illuminate\Support\Facades\Schema::create('abandoned_checkouts', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->id();
                    $table->string('uuid')->unique();
                    $table->string('customer_name')->nullable();
                    $table->string('customer_phone')->nullable()->index();
                    $table->string('customer_email')->nullable();
                    $table->text('shipping_address')->nullable();
                    $table->string('district')->nullable();
                    $table->decimal('subtotal', 10, 2)->default(0);
                    $table->decimal('delivery_charge', 10, 2)->default(0);
                    $table->decimal('total_amount', 10, 2)->default(0);
                    $table->longText('items_json')->nullable();
                    $table->boolean('is_recovered')->default(false);
                    $table->timestamp('recovered_at')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Throwable) {}
    }
}
