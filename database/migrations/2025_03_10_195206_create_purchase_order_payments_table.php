<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchase_order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 24, 2);
            $table->enum('payment_method', ['bank_transfer', 'cash', 'cheque'])->default('bank_transfer');
            $table->string('transaction_id');
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_payments');
    }
};
