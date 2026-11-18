<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('title')->index();
            $table->string('subtitle')->nullable();
            $table->string('sku')->unique()->index();
            $table->decimal('price', 12, 2);
            $table->decimal('compare_at_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            $table->integer('stock_quantity')->default(1);
            $table->string('currency', 3)->default('BDT');
            $table->string('status')->default('published')->index();

            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained('districts')->nullOnDelete();

            // Core Page Builder & Landing Page JSON Engines
            $table->json('layout_config')->nullable();
            $table->json('theme_tokens')->nullable();
            $table->json('heritage_specs')->nullable();
            $table->json('story_meta')->nullable();

            $table->boolean('is_featured')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_layouts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->json('block_payload');
            $table->boolean('is_published')->default(false);
            $table->string('change_summary')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['product_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_layouts');
        Schema::dropIfExists('products');
    }
};
