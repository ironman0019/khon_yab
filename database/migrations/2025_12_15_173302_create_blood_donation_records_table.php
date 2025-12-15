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
        Schema::create('blood_donation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained('donors')->onDelete('cascade');
            $table->tinyInteger('donation_type')->comment('0 => Whole Blood, 1 => Plasma, 2 => Platelets');
            $table->integer('amount_ml')->comment('Amount of blood in milliliters');
            $table->date('donation_date');
            $table->date('expiration_date');
            $table->tinyInteger('status')->default(0)->comment('0 => Test Pending, 1 => Safe, 2 => Unsafe, 3 => Discarded');
            $table->foreignId('recorded_by_admin')->nullable()->constrained('users')->onDelete('set null');
            $table->tinyInteger('submitted_by_donor')->default(0)->comment('0 => no, 1 => yes (Submit Request)');
            $table->text('notes')->nullable()->comment('Report and additional information');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donation_records');
    }
};
