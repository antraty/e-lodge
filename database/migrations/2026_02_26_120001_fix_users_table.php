<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Users table should already be fine, but let's ensure
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->after('email');
            }
        });
    }

    public function down(): void
    {
        //
    }
};
