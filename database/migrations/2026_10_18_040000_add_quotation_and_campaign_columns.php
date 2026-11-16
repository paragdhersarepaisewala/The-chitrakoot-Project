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
            if (! Schema::hasColumn('pooja_bookings', 'ritual_name')) {
                $table->string('ritual_name')->nullable()->after('pooja_service_id');
            }
            if (! Schema::hasColumn('pooja_bookings', 'quotation_amount')) {
                $table->decimal('quotation_amount', 10, 2)->nullable()->after('amount');
            }
            if (! Schema::hasColumn('pooja_bookings', 'quotation_notes')) {
                $table->text('quotation_notes')->nullable()->after('quotation_amount');
            }
            if (! Schema::hasColumn('pooja_bookings', 'quotation_token')) {
                $table->string('quotation_token', 64)->nullable()->unique()->after('quotation_notes');
            }
            if (! Schema::hasColumn('pooja_bookings', 'quotation_expires_at')) {
                $table->dateTime('quotation_expires_at')->nullable()->after('quotation_token');
            }
            if (! Schema::hasColumn('pooja_bookings', 'quoted_at')) {
                $table->dateTime('quoted_at')->nullable()->after('quotation_expires_at');
            }
        });

        Schema::table('seva_projects', function (Blueprint $table) {
            if (! Schema::hasColumn('seva_projects', 'target_amount')) {
                $table->decimal('target_amount', 12, 2)->default(500000.00)->after('category');
            }
            if (! Schema::hasColumn('seva_projects', 'is_featured')) {
                $table->boolean('is_featured')->default(true)->after('is_published');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pooja_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'ritual_name',
                'quotation_amount',
                'quotation_notes',
                'quotation_token',
                'quotation_expires_at',
                'quoted_at',
            ]);
        });

        Schema::table('seva_projects', function (Blueprint $table) {
            $table->dropColumn([
                'target_amount',
                'is_featured',
            ]);
        });
    }
};
