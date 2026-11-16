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
        Schema::table('donations', function (Blueprint $table) {
            if (! Schema::hasColumn('donations', 'donor_name')) {
                $table->string('donor_name')->nullable()->after('user_id');
            }
            if (! Schema::hasColumn('donations', 'donor_email')) {
                $table->string('donor_email')->nullable()->after('donor_name');
            }
            if (! Schema::hasColumn('donations', 'donor_phone')) {
                $table->string('donor_phone')->nullable()->after('donor_email');
            }
        });

        Schema::table('receipts', function (Blueprint $table) {
            if (! Schema::hasColumn('receipts', 'donor_email')) {
                $table->string('donor_email')->nullable()->after('donor_name');
            }
            if (! Schema::hasColumn('receipts', 'donor_phone')) {
                $table->string('donor_phone')->nullable()->after('donor_email');
            }
        });

        Schema::table('volunteer_applications', function (Blueprint $table) {
            if (! Schema::hasColumn('volunteer_applications', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['donor_name', 'donor_email', 'donor_phone']);
        });

        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn(['donor_email', 'donor_phone']);
        });

        Schema::table('volunteer_applications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
