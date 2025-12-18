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
        Schema::table('blood_donation_records', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->after('recorded_by_admin')->constrained('provinces')->onDelete('set null');
            $table->foreignId('city_id')->nullable()->after('province_id')->constrained('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blood_donation_records', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['province_id', 'city_id']);
        });
    }
};
