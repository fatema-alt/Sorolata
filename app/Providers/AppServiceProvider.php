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
        //
    }
}
