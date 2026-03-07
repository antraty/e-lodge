<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert payments.status and payments.method from ENUM to VARCHAR(50)
        if (Schema::hasTable('payments')) {
            DB::statement("ALTER TABLE payments MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE payments MODIFY COLUMN `method` VARCHAR(50) NOT NULL DEFAULT 'cash'");
        }

        // Convert reservations.status from ENUM to VARCHAR(50)
        if (Schema::hasTable('reservations')) {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is a best-effort: change back to ENUMs with common values
        if (Schema::hasTable('payments')) {
            DB::statement("ALTER TABLE payments MODIFY COLUMN `status` ENUM('pending','partial','paid','completed','failed','refunded') NOT NULL DEFAULT 'pending'");
            DB::statement("ALTER TABLE payments MODIFY COLUMN `method` ENUM('cash','card','bank_transfer','check') NOT NULL DEFAULT 'cash'");
        }
        if (Schema::hasTable('reservations')) {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN `status` ENUM('pending','confirmed','checked_in','checked_out','cancelled','paid','partial') NOT NULL DEFAULT 'pending'");
        }
    }
};
