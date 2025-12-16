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
        Schema::create('blood_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('bag_id')->unique()->comment('Bag ID - Unique identifier for each blood bag');
            $table->foreignId('blood_donation_record_id')->constrained('blood_donation_records')->onDelete('cascade');
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade')->comment('Province for reporting purposes');
            $table->string('blood_type')->comment('A, B, AB, O');
            $table->string('rh_factor')->comment('positive or negative');
            $table->date('entry_date')->comment('Date when blood bag entered inventory');
            $table->date('exit_date')->nullable()->comment('Date when blood bag was removed/used');
            $table->date('expiration_date')->comment('Expiration date of the blood bag');
            $table->tinyInteger('status')->default(0)->comment('0 => In Stock, 1 => Used, 2 => Expired, 3 => Discarded');
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null')->comment('User who added to inventory');
            $table->foreignId('removed_by')->nullable()->constrained('users')->onDelete('set null')->comment('User who removed from inventory');
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
        Schema::dropIfExists('blood_inventories');
    }
};
