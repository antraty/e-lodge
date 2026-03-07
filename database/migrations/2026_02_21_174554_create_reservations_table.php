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
        if (!Schema::hasTable('reservations')) {
            Schema::create('reservations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('room_id')->constrained()->onDelete('cascade');
                $table->date('check_in');
                $table->date('check_out');
                $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
                $table->integer('number_of_guests')->default(1);
                $table->decimal('total_price', 10, 2)->default(0.00);
                $table->text('special_requests')->nullable();
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('reservations', 'client_id')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->foreignId('client_id')->constrained()->onDelete('cascade')->after('id');
                });
            }
            if (!Schema::hasColumn('reservations', 'room_id')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->foreignId('room_id')->constrained()->onDelete('cascade')->after('client_id');
                });
            }
            $cols = ['check_in' => 'date','check_out' => 'date','status' => 'enum','number_of_guests' => 'integer','total_price' => 'decimal','special_requests' => 'text'];
            if (!Schema::hasColumn('reservations', 'check_in')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->date('check_in')->after('room_id');
                });
            }
            if (!Schema::hasColumn('reservations', 'check_out')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->date('check_out')->after('check_in');
                });
            }
            if (!Schema::hasColumn('reservations', 'status')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending')->after('check_out');
                });
            }
            if (!Schema::hasColumn('reservations', 'number_of_guests')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->integer('number_of_guests')->default(1)->after('status');
                });
            }
            if (!Schema::hasColumn('reservations', 'total_price')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->decimal('total_price', 10, 2)->default(0.00)->after('number_of_guests');
                });
            }
            if (!Schema::hasColumn('reservations', 'special_requests')) {
                Schema::table('reservations', function (Blueprint $table) {
                    $table->text('special_requests')->nullable()->after('total_price');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
