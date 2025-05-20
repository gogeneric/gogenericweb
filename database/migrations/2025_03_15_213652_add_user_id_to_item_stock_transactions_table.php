<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('item_stock_transactions', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->after('purchase_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('item_stock_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
