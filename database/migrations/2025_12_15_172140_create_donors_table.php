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
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('mobile_number');
            $table->string('national_code');
            $table->integer('age');
            $table->string('gender');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->text('address');
            $table->string('blood_type');
            $table->string('rh_factor')->comment('positive or negative');
            $table->tinyInteger('health_status')->default(0)->comment('0 => not healthy, 1 => healthy');
            $table->date('last_donation_date')->nullable();
            $table->tinyInteger('ability_to_donate')->default(0)->comment('0 => no, 1 => yes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
