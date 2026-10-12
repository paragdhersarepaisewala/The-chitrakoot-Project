<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->id();
                $table->string('title_hi');
                $table->string('title_en')->nullable();
                $table->string('slug')->unique();
                $table->string('category')->default('Spiritual');
                $table->text('excerpt')->nullable();
                $table->longText('content_hi');
                $table->longText('content_en')->nullable();
                $table->string('featured_image')->nullable();
                $table->string('author_name')->default('श्री कामदगिरि पीठम्');
                $table->unsignedInteger('likes_count')->default(0);
                $table->unsignedInteger('views_count')->default(0);
                $table->enum('status', ['published', 'draft'])->default('published');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('post_comments')) {
            Schema::create('post_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('name');
                $table->string('email')->nullable();
                $table->text('comment');
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('post_comments');
        Schema::dropIfExists('posts');
    }
};
