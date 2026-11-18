<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('slug')->unique();
            $table->string('title')->index();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('category')->default('Heritage & Craft');
            $table->string('author_name')->default('Curator Desk');
            $table->string('hero_image')->nullable();
            $table->unsignedInteger('reading_time')->default(5);
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('reviews', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('author_name');
            $table->string('author_location')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('title')->nullable();
            $table->text('content');
            $table->json('photos')->nullable();
            $table->boolean('is_verified_purchase')->default(true);
            $table->unsignedInteger('likes_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('stories');
    }
};
