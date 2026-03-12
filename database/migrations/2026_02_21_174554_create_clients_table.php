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
        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('first_name');
                $table->string('last_name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('postal_code')->nullable();
                $table->string('country')->nullable();
                $table->string('id_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // If the table already exists (older minimal migration ran), add missing columns safely
            if (!Schema::hasColumn('clients', 'first_name')) {
                Schema::table('clients', function (Blueprint $table) {
                    $table->string('first_name')->after('id');
                });
            }
            if (!Schema::hasColumn('clients', 'last_name')) {
                Schema::table('clients', function (Blueprint $table) {
                    $table->string('last_name')->after('first_name');
                });
            }
            if (!Schema::hasColumn('clients', 'email')) {
                Schema::table('clients', function (Blueprint $table) {
                    $table->string('email')->unique()->after('last_name');
                });
            }
            $additional = ['phone','address','city','postal_code','country','id_number','notes'];
            foreach ($additional as $col) {
                if (!Schema::hasColumn('clients', $col)) {
                    Schema::table('clients', function (Blueprint $table) use ($col) {
                        if ($col === 'notes') {
                            $table->text($col)->nullable()->after('id_number');
                        } else {
                            $table->string($col)->nullable()->after('email');
                        }
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
