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
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('room_number')->unique();
                $table->enum('type', ['single', 'double', 'suite', 'deluxe'])->default('double');
                $table->integer('capacity')->default(2);
                $table->decimal('price_per_night', 10, 2)->default(0.00);
                $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('rooms', 'room_number')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->string('room_number')->unique()->after('id');
                });
            }
            if (!Schema::hasColumn('rooms', 'type')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->enum('type', ['single', 'double', 'suite', 'deluxe'])->default('double')->after('room_number');
                });
            }
            $cols = ['capacity' => 'integer', 'price_per_night' => 'decimal', 'status' => 'enum', 'description' => 'text'];
            if (!Schema::hasColumn('rooms', 'capacity')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->integer('capacity')->default(2)->after('type');
                });
            }
            if (!Schema::hasColumn('rooms', 'price_per_night')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->decimal('price_per_night', 10, 2)->default(0.00)->after('capacity');
                });
            }
            if (!Schema::hasColumn('rooms', 'status')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available')->after('price_per_night');
                });
            }
            if (!Schema::hasColumn('rooms', 'description')) {
                Schema::table('rooms', function (Blueprint $table) {
                    $table->text('description')->nullable()->after('status');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
