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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('paid_amount', 12, 2)->default(0.00)->after('total_amount');
            $table->decimal('due_amount', 12, 2)->default(0.00)->after('paid_amount');
            $table->decimal('advance_delivery_charge', 12, 2)->default(0.00)->after('due_amount');
            $table->string('transaction_id', 100)->nullable()->after('payment_gateway');
            $table->json('payment_details')->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paid_amount',
                'due_amount',
                'advance_delivery_charge',
                'transaction_id',
                'payment_details',
            ]);
        });
    }
};

