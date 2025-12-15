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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade')->comment('Hospital/Clinic/Authorized user who made the request');
            $table->string('blood_type')->comment('A, B, AB, O');
            $table->string('rh_factor')->comment('positive or negative');
            $table->integer('number_of_bags')->comment('Number of blood bags requested');
            $table->string('patient_name')->comment('Name of the patient');
            $table->integer('patient_age')->comment('Age of the patient');
            $table->text('request_reason')->comment('Reason for blood request');
            $table->string('contact_number')->comment('Contact number');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->string('medical_center')->comment('Medical center name');
            $table->tinyInteger('status')->default(0)->comment('0 => Pending, 1 => Approved, 2 => Rejected, 3 => Completed');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->comment('Admin who approved/rejected the request');
            $table->dateTime('approval_date')->nullable()->comment('Date when request was approved/rejected');
            $table->text('rejection_reason')->nullable()->comment('Reason for rejection if rejected');
            $table->text('notes')->nullable()->comment('Additional notes and information');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
