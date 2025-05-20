<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->text('admin_notes')->nullable()->after('payment_status');
            $table->text('notes')->nullable()->after('admin_notes');
            $table->string('shipping_method')->nullable()->after('notes');
            $table->date('estimated_delivery')->nullable()->after('shipping_method');
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropColumn(['admin_notes', 'notes', 'shipping_method', 'estimated_delivery']);
        });
    }
};
