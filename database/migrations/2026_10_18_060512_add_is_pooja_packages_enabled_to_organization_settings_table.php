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
        Schema::table('organization_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('organization_settings', 'is_pooja_packages_enabled')) {
                $table->boolean('is_pooja_packages_enabled')->default(false)->after('is_80g_enabled');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_settings', function (Blueprint $table) {
            if (Schema::hasColumn('organization_settings', 'is_pooja_packages_enabled')) {
                $table->dropColumn('is_pooja_packages_enabled');
            }
        });
    }
};
