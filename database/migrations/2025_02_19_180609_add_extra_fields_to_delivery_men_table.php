<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->string('aadhar_number')->nullable();
            $table->string('aadhar_image')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('pan_image')->nullable();
            $table->string('bike_registration_number')->nullable();
            $table->string('bike_registration_image')->nullable();
            $table->string('bike_insurance_image')->nullable();
            $table->string('driving_license_number')->nullable();
            $table->string('driving_license_image')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->enum('account_type', ['savings', 'current'])->default('savings');

        });
    }
};
