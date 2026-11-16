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
        if (! Schema::hasTable('homepage_settings')) {
            Schema::create('homepage_settings', function (Blueprint $table) {
                $table->string('setting_key', 100)->primary();
                $table->longText('setting_value')->nullable();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('homepage_settings', 'setting_key')) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->string('setting_key', 100)->nullable();
                $table->longText('setting_value')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_settings');
    }
};
