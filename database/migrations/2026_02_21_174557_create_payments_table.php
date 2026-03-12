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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
                $table->decimal('amount', 10, 2);
                $table->decimal('paid_amount', 10, 2)->default(0.00);
                $table->enum('status', ['pending', 'partial', 'completed', 'failed', 'refunded'])->default('pending');
                $table->enum('method', ['cash', 'card', 'bank_transfer', 'check'])->default('cash');
                $table->string('reference_number')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            if (!Schema::hasColumn('payments', 'reservation_id')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->foreignId('reservation_id')->constrained()->onDelete('cascade')->after('id');
                });
            }
            if (!Schema::hasColumn('payments', 'amount')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->decimal('amount', 10, 2)->after('reservation_id');
                });
            }
            if (!Schema::hasColumn('payments', 'paid_amount')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->decimal('paid_amount', 10, 2)->default(0.00)->after('amount');
                });
            }
            if (!Schema::hasColumn('payments', 'status')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->enum('status', ['pending', 'partial', 'completed', 'failed', 'refunded'])->default('pending')->after('paid_amount');
                });
            }
            if (!Schema::hasColumn('payments', 'method')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->enum('method', ['cash', 'card', 'bank_transfer', 'check'])->default('cash')->after('status');
                });
            }
            if (!Schema::hasColumn('payments', 'reference_number')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->string('reference_number')->nullable()->after('method');
                });
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->timestamp('paid_at')->nullable()->after('reference_number');
                });
            }
            if (!Schema::hasColumn('payments', 'notes')) {
                Schema::table('payments', function (Blueprint $table) {
                    $table->text('notes')->nullable()->after('paid_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
