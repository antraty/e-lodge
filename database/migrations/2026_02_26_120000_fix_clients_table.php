<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix clients table - remove problematic 'name' column if it exists
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'name')) {
                $table->dropColumn('name');
            }
        });

        // Ensure all required columns exist
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'first_name')) {
                $table->string('first_name')->after('id');
            }
            if (!Schema::hasColumn('clients', 'last_name')) {
                $table->string('last_name')->after('first_name');
            }
            if (!Schema::hasColumn('clients', 'email')) {
                $table->string('email')->unique()->after('last_name');
            }
            if (!Schema::hasColumn('clients', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('clients', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
        });
    }

    public function down(): void
    {
        //
    }
};
