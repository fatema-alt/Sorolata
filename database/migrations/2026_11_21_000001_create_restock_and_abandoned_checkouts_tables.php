<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('product_restock_requests')) {
            Schema::create('product_restock_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id')->index();
                $table->string('customer_name')->nullable();
                $table->string('phone');
                $table->string('email')->nullable();
                $table->boolean('is_notified')->default(false);
                $table->timestamp('notified_at')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('abandoned_checkouts')) {
            Schema::create('abandoned_checkouts', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->string('customer_name')->nullable();
                $table->string('customer_phone')->nullable()->index();
                $table->string('customer_email')->nullable();
                $table->text('shipping_address')->nullable();
                $table->string('district')->nullable();
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('delivery_charge', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->longText('items_json')->nullable();
                $table->boolean('is_recovered')->default(false);
                $table->timestamp('recovered_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_checkouts');
        Schema::dropIfExists('product_restock_requests');
    }
};
