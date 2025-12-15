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
        Schema::create('blood_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_donation_record_id')->constrained('blood_donation_records')->onDelete('cascade');
            $table->tinyInteger('hiv_result')->comment('0 => negative, 1 => positive');
            $table->tinyInteger('hbv_result')->comment('0 => negative, 1 => positive');
            $table->tinyInteger('hcv_result')->comment('0 => negative, 1 => positive');
            $table->tinyInteger('syphilis_result')->comment('0 => negative, 1 => positive');
            $table->tinyInteger('malaria_result')->comment('0 => negative, 1 => positive');
            $table->tinyInteger('overall_result')->comment('0 => safe, 1 => unsafe');
            $table->date('test_date');
            $table->foreignId('tested_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('test_logs')->nullable()->comment('Test logs and additional information');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_tests');
    }
};
