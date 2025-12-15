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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Language code (e.g., en, fa, ps)');
            $table->string('name')->comment('Language name in English');
            $table->string('native_name')->comment('Language name in native language');
            $table->string('direction', 3)->default('ltr')->comment('Text direction: ltr or rtl');
            $table->tinyInteger('is_active')->default(1)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('is_default')->default(0)->comment('0 => not default, 1 => default language');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
