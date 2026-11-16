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
        if (Schema::hasTable('pooja_bookings')) {
            Schema::table('pooja_bookings', function (Blueprint $table) {
                if (Schema::hasColumn('pooja_bookings', 'amount')) {
                    $table->decimal('amount', 14, 2)->default(0.00)->change();
                }
                if (Schema::hasColumn('pooja_bookings', 'quotation_amount')) {
                    $table->decimal('quotation_amount', 14, 2)->nullable()->change();
                }
            });
        }

        if (Schema::hasTable('pooja_services')) {
            Schema::table('pooja_services', function (Blueprint $table) {
                if (Schema::hasColumn('pooja_services', 'suggested_donation_amount')) {
                    $table->decimal('suggested_donation_amount', 14, 2)->default(501.00)->change();
                }
            });
        }

        if (Schema::hasTable('causes')) {
            Schema::table('causes', function (Blueprint $table) {
                if (Schema::hasColumn('causes', 'target_amount')) {
                    $table->decimal('target_amount', 14, 2)->default(0.00)->change();
                }
                if (Schema::hasColumn('causes', 'minimum_amount')) {
                    $table->decimal('minimum_amount', 14, 2)->default(100.00)->change();
                }
            });
        }

        if (Schema::hasTable('donations')) {
            Schema::table('donations', function (Blueprint $table) {
                if (Schema::hasColumn('donations', 'amount')) {
                    $table->decimal('amount', 14, 2)->change();
                }
            });
        }

        if (Schema::hasTable('receipts')) {
            Schema::table('receipts', function (Blueprint $table) {
                if (Schema::hasColumn('receipts', 'amount')) {
                    $table->decimal('amount', 14, 2)->change();
                }
            });
        }

        if (Schema::hasTable('seva_projects')) {
            Schema::table('seva_projects', function (Blueprint $table) {
                if (Schema::hasColumn('seva_projects', 'target_amount')) {
                    $table->decimal('target_amount', 14, 2)->default(500000.00)->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safe to keep decimal(14, 2) capacity
    }
};
