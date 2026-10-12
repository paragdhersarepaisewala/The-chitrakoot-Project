<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name_hi');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->string('tagline_hi')->nullable();
            $table->string('tagline_en')->nullable();
            $table->text('summary_hi')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_hi')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->string('map_url')->nullable();
            $table->text('visitor_info')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('seva_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title_hi');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->string('category')->default('seva');
            $table->text('summary_hi')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_hi')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->foreignId('cause_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title_hi');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->dateTime('event_date');
            $table->dateTime('event_end_date')->nullable();
            $table->string('location_hi')->nullable();
            $table->string('location_en')->nullable();
            $table->text('summary_hi')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_hi')->nullable();
            $table->longText('description_en')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('registration_url')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title_hi');
            $table->string('title_en');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('type', ['photo', 'video', 'mixed'])->default('photo');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->onDelete('cascade');
            $table->enum('item_type', ['image', 'youtube_video', 'video_file'])->default('image');
            $table->string('media_path')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('caption_hi')->nullable();
            $table->string('caption_en')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('leadership_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name_hi');
            $table->string('name_en');
            $table->string('title_hi');
            $table->string('title_en');
            $table->enum('role_category', ['peethadhishwar', 'trustee', 'office_bearer', 'administrator', 'volunteer'])->default('trustee');
            $table->string('photo_path')->nullable();
            $table->text('bio_hi')->nullable();
            $table->text('bio_en')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_profiles');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('events');
        Schema::dropIfExists('seva_projects');
        Schema::dropIfExists('destinations');
    }
};
