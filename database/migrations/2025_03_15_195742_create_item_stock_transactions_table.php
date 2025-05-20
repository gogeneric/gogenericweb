<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity')->unsigned();
            $table->string('type', 10); // 'credit' or 'debit'
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('item_id');
            $table->index('purchase_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_stock_transactions');
    }
};
