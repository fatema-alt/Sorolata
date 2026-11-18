<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artisans', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('district_id')->constrained('districts')->cascadeOnDelete();
            $table->string('full_name')->index();
            $table->string('atelier_name')->nullable();
            $table->string('craft_type')->index();
            $table->unsignedInteger('years_of_mastery')->default(1);
            $table->text('biography')->nullable();
            $table->text('quote')->nullable();
            $table->string('portrait_url')->nullable();
            $table->json('awards')->nullable();
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artisans');
    }
};
