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
        Schema::table('pooja_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('pooja_bookings', 'quotation_order_id')) {
                $table->string('quotation_order_id', 100)->nullable()->after('quotation_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pooja_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('pooja_bookings', 'quotation_order_id')) {
                $table->dropColumn('quotation_order_id');
            }
        });
    }
};
