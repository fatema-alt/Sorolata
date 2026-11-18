<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('districts', 'slug')) {
            Schema::table('districts', function (Blueprint $table): void {
                $table->string('slug')->nullable()->unique()->after('uuid');
            });

            // Backfill slugs for existing districts
            $districts = DB::table('districts')->select('id', 'name_en')->get();
            foreach ($districts as $district) {
                $slug = Str::slug($district->name_en);
                $originalSlug = $slug;
                $counter = 1;
                while (DB::table('districts')->where('slug', $slug)->where('id', '!=', $district->id)->exists()) {
                    $slug = "{$originalSlug}-{$counter}";
                    $counter++;
                }
                DB::table('districts')->where('id', $district->id)->update(['slug' => $slug]);
            }
        }

        if (!Schema::hasColumn('artisans', 'slug')) {
            Schema::table('artisans', function (Blueprint $table): void {
                $table->string('slug')->nullable()->unique()->after('uuid');
            });

            // Backfill slugs for existing artisans
            $artisans = DB::table('artisans')->select('id', 'full_name')->get();
            foreach ($artisans as $artisan) {
                $slug = Str::slug($artisan->full_name);
                $originalSlug = $slug;
                $counter = 1;
                while (DB::table('artisans')->where('slug', $slug)->where('id', '!=', $artisan->id)->exists()) {
                    $slug = "{$originalSlug}-{$counter}";
                    $counter++;
                }
                DB::table('artisans')->where('id', $artisan->id)->update(['slug' => $slug]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('districts', 'slug')) {
            Schema::table('districts', function (Blueprint $table): void {
                $table->dropColumn('slug');
            });
        }

        if (Schema::hasColumn('artisans', 'slug')) {
            Schema::table('artisans', function (Blueprint $table): void {
                $table->dropColumn('slug');
            });
        }
    }
};
