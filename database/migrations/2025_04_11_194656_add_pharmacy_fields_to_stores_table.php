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
        Schema::table('stores', function (Blueprint $table) {
            // Pharmacy-specific fields
            $table->string('drug_license_number')->nullable();
            $table->date('drug_license_expiry')->nullable();
            $table->string('store_contact_person_number')->nullable();
            $table->string('pan_gst_number')->nullable();
            $table->string('form_20_21')->nullable(); // File path
            $table->string('pan_gst_upload')->nullable(); // File path
            $table->string('owner_photo')->nullable(); // File path
            
            // Bank account information
            $table->enum('account_type', ['savings', 'current'])->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Pharmacy-specific fields
            $table->dropColumn('drug_license_number');
            $table->dropColumn('drug_license_expiry');
            $table->dropColumn('store_contact_person_number');
            $table->dropColumn('pan_gst_number');
            $table->dropColumn('form_20_21');
            $table->dropColumn('pan_gst_upload');
            $table->dropColumn('owner_photo');
            
            // Bank account information
            $table->dropColumn('account_type');
            $table->dropColumn('account_number');
            $table->dropColumn('bank_name');
            $table->dropColumn('ifsc_code');
        });
    }
};
