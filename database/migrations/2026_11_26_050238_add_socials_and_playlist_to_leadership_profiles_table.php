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
        Schema::table('leadership_profiles', function (Blueprint $table) {
            $table->json('social_links')->nullable()->after('bio_en');
            $table->string('youtube_playlist_title', 255)->nullable()->after('social_links');
            $table->string('youtube_playlist_url', 500)->nullable()->after('youtube_playlist_title');
            $table->boolean('is_youtube_playlist_enabled')->default(false)->after('youtube_playlist_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leadership_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'social_links',
                'youtube_playlist_title',
                'youtube_playlist_url',
                'is_youtube_playlist_enabled',
            ]);
        });
    }
};
