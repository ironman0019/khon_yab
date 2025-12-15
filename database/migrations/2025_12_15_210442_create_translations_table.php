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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->comment('Translation key (e.g., menu.dashboard, button.submit)');
            $table->string('group')->default('general')->comment('Translation group: menu, form, button, report, dashboard, etc.');
            $table->string('language_code', 10)->comment('Language code (e.g., en, fa, ps)');
            $table->text('value')->comment('Translated text');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['key', 'group', 'language_code'], 'translation_unique');
            $table->index(['group', 'language_code']);
            $table->foreign('language_code')->references('code')->on('languages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
