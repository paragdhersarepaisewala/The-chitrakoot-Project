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
        Schema::table('posts', function (Blueprint $table) {
            if (! Schema::hasColumn('posts', 'featured_image_caption')) {
                $table->string('featured_image_caption')->nullable()->after('featured_image');
            }
            if (! Schema::hasColumn('posts', 'featured_image_alt')) {
                $table->string('featured_image_alt')->nullable()->after('featured_image_caption');
            }
            if (! Schema::hasColumn('posts', 'video_url')) {
                $table->string('video_url')->nullable()->after('featured_image_alt');
            }
            if (! Schema::hasColumn('posts', 'tags')) {
                $table->string('tags')->nullable()->after('video_url');
            }
            if (! Schema::hasColumn('posts', 'focus_keyword')) {
                $table->string('focus_keyword')->nullable()->after('tags');
            }
            if (! Schema::hasColumn('posts', 'seo_score')) {
                $table->unsignedTinyInteger('seo_score')->default(75)->after('focus_keyword');
            }
            if (! Schema::hasColumn('posts', 'is_hero')) {
                $table->boolean('is_hero')->default(false)->after('seo_score');
            }
            if (! Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'featured_image_caption',
                'featured_image_alt',
                'video_url',
                'tags',
                'focus_keyword',
                'seo_score',
                'is_hero',
                'published_at',
            ]);
        });
    }
};
