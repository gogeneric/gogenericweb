<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuperAdminStocksTable extends Migration
{
    public function up()
    {
        Schema::create('super_admin_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->decimal('price', 24, 2)->default(0.00);
            $table->decimal('discount', 24, 2)->default(0.00);
            $table->string('discount_type')->default('percent');
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('veg')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('module_id')->references('id')->on('modules');
        });

        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('super_admin_stock_id')->nullable();
            $table->foreign('super_admin_stock_id')->references('id')->on('super_admin_stocks')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['super_admin_stock_id']);
            $table->dropColumn('super_admin_stock_id');
        });

        Schema::dropIfExists('super_admin_stocks');
    }
}
