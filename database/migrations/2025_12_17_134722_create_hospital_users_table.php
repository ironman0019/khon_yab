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
        Schema::create('hospital_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('hospital_name');
            $table->string('hospital_code')->unique()->comment('Unique hospital registration code');
            $table->string('mobile_number');
            $table->string('phone_number')->nullable()->comment('Landline phone number');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->text('address');
            $table->string('license_number')->nullable()->comment('Hospital license number');
            $table->string('contact_person_name')->comment('Name of the main contact person');
            $table->tinyInteger('status')->default(0)->comment('0 => pending, 1 => active, 2 => inactive, 3 => verified');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital_users');
    }
};
