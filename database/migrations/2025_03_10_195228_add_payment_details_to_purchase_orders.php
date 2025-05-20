<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->decimal('total_amount', 24, 2)->default(0);
            $table->decimal('paid_amount', 24, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn('total_amount');
            $table->dropColumn('paid_amount');
            $table->dropColumn('payment_status');
        });
    }
};
