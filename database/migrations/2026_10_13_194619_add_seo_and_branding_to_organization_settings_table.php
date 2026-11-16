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
            $table->string('site_tagline')->nullable()->after('org_name');
            $table->text('meta_description')->nullable()->after('site_tagline');
            $table->string('default_og_image')->nullable()->after('favicon_path');
            $table->string('ga4_measurement_id')->nullable()->after('default_og_image');
            $table->text('ga4_custom_script')->nullable()->after('ga4_measurement_id');
            $table->string('ga4_report_url')->nullable()->after('ga4_custom_script');
            $table->string('google_site_verification')->nullable()->after('ga4_report_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_tagline',
                'meta_description',
                'default_og_image',
                'ga4_measurement_id',
                'ga4_custom_script',
                'ga4_report_url',
                'google_site_verification',
            ]);
        });
    }
};
