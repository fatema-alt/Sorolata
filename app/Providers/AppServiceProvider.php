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
        } catch (\Throwable) {}
    }
}
