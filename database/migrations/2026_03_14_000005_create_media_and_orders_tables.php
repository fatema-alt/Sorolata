<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->morphs('attachable');
            $table->string('file_path');
            $table->string('disk')->default('public');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('aspect_ratio', 10)->default('1:1');
            $table->text('blur_placeholder')->nullable();
            $table->string('alt_text')->nullable();
            $table->json('variants')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('order_number')->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->decimal('total_amount', 12, 2);
            $table->decimal('subtotal_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->string('status')->default('pending')->index();
            $table->string('payment_status')->default('unpaid')->index();
            $table->string('payment_gateway')->default('bkash');
            $table->json('shipping_address');
            $table->json('billing_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('artisan_id')->nullable()->constrained('artisans')->nullOnDelete();
            $table->string('product_title');
            $table->string('artisan_name')->nullable();
            $table->string('sku');
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('subtotal', 12, 2);
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('media_assets');
    }
};
