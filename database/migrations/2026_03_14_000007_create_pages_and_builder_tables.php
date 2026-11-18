<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('title')->index();
            $table->string('page_type')->default('landing')->index(); // home, product, landing, festival, campaign, custom
            $table->json('layout_json');
            $table->json('seo_meta')->nullable();
            $table->string('status')->default('draft')->index(); // draft, published, scheduled
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_revisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained('pages')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->json('layout_json');
            $table->string('change_summary')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_autosave')->default(false);
            $table->timestamps();

            $table->index(['page_id', 'version']);
            $table->index(['product_id', 'version']);
        });

        Schema::create('page_templates', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name')->index();
            $table->string('category')->default('landing')->index(); // hero, product_landing, faq, testimonial, festival, collection, story
            $table->string('thumbnail_url')->nullable();
            $table->json('layout_json');
            $table->boolean('is_global')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_templates');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('pages');
    }
};
