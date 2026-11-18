<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enrich Users with Admin Roles & Profile Info
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role')->default('customer')->index()->after('email');
            $table->string('avatar_url')->nullable()->after('role');
            $table->string('phone')->nullable()->after('avatar_url');
        });

        // 2. Activity Logs (Audit Trail)
        Schema::create('activity_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action')->index(); // product.created, order.status_updated, setting.saved
            $table->text('description');
            $table->nullableMorphs('subject');
            $table->json('properties')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 3. Admin Settings Key-Value Store
        Schema::create('admin_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('group')->default('general')->index(); // general, payment, shipping, tax, seo
            $table->timestamps();
        });

        // 4. Admin Centralized Notifications
        Schema::create('admin_notifications', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type')->default('order')->index(); // order, stock, review, system
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
        Schema::dropIfExists('admin_settings');
        Schema::dropIfExists('activity_logs');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['role', 'avatar_url', 'phone']);
        });
    }
};
