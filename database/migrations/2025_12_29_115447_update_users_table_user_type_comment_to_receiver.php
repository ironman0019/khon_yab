<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Update the comment on user_type column
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type TINYINT DEFAULT 0 COMMENT '0 => receiver, 1 => donor, 2 => laboratory'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // Revert the comment
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type TINYINT DEFAULT 0 COMMENT '0 => user, 1 => donor, 2 => laboratory'");
    }
};
