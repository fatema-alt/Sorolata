<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add bilingual fields to stories
        Schema::table('stories', function (Blueprint $table): void {
            $table->string('title_bn')->nullable()->after('title');
            $table->text('excerpt_bn')->nullable()->after('excerpt');
            $table->longText('content_bn')->nullable()->after('content');
            $table->string('category_bn')->nullable()->after('category');
            $table->string('author_name_bn')->nullable()->after('author_name');
        });

        // 2. Add bilingual fields to products
        Schema::table('products', function (Blueprint $table): void {
            $table->string('title_bn')->nullable()->after('title');
            $table->string('subtitle_bn')->nullable()->after('subtitle');
            $table->text('short_description_bn')->nullable()->after('short_description');
            $table->longText('long_description_bn')->nullable()->after('long_description');
        });

        // 3. Add bilingual fields to categories
        Schema::table('categories', function (Blueprint $table): void {
            $table->string('name_bn')->nullable()->after('name');
            $table->text('description_bn')->nullable()->after('description');
        });

        // 4. Add bilingual fields to artisans
        Schema::table('artisans', function (Blueprint $table): void {
            $table->string('full_name_bn')->nullable()->after('full_name');
            $table->string('atelier_name_bn')->nullable()->after('atelier_name');
            $table->text('biography_bn')->nullable()->after('biography');
            $table->text('quote_bn')->nullable()->after('quote');
        });

        // 5. Create story_product pivot table for shoppable content-commerce linking
        Schema::create('story_product', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('story_id')->constrained('stories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('context_note_en')->nullable(); // e.g. "Featured in paragraph 2"
            $table->string('context_note_bn')->nullable(); // e.g. "২য় পরিচ্ছেদে উল্লেখিত শাড়ি"
            $table->timestamps();

            $table->unique(['story_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_product');

        Schema::table('artisans', function (Blueprint $table): void {
            $table->dropColumn(['full_name_bn', 'atelier_name_bn', 'biography_bn', 'quote_bn']);
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn(['name_bn', 'description_bn']);
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['title_bn', 'subtitle_bn', 'short_description_bn', 'long_description_bn']);
        });

        Schema::table('stories', function (Blueprint $table): void {
            $table->dropColumn(['title_bn', 'excerpt_bn', 'content_bn', 'category_bn', 'author_name_bn']);
        });
    }
};
