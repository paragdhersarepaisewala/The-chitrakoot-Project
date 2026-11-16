<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('article_categories')) {
            Schema::create('article_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name_hi');
                $table->string('name_en')->nullable();
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('color', 30)->default('#D95D0F');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('article_categories');
    }
};
