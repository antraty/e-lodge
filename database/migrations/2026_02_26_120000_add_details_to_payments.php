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
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'card_number')) {
                    $table->string('card_number')->nullable()->after('method');
                }
                if (!Schema::hasColumn('payments', 'mobile_number')) {
                    $table->string('mobile_number')->nullable()->after('card_number');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (Schema::hasColumn('payments', 'card_number')) {
                    $table->dropColumn('card_number');
                }
                if (Schema::hasColumn('payments', 'mobile_number')) {
                    $table->dropColumn('mobile_number');
                }
            });
        }
    }
};
