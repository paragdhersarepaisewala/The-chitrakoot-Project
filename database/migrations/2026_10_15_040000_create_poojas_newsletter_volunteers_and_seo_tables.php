<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Sacred Pooja & Aarti Services
        if (! Schema::hasTable('pooja_services')) {
            Schema::create('pooja_services', function (Blueprint $table) {
                $table->id();
                $table->string('title_hi');
                $table->string('title_en')->nullable();
                $table->string('slug')->unique();
                $table->text('summary_hi')->nullable();
                $table->longText('description_hi')->nullable();
                $table->decimal('suggested_donation_amount', 10, 2)->default(501.00);
                $table->integer('duration_minutes')->default(45);
                $table->string('timings_display')->default('नित्य प्रातः 07:00 AM व सायं 06:00 PM');
                $table->json('benefits_hi')->nullable();
                $table->string('featured_image')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Devotee Pooja & Sankalp Bookings
        if (! Schema::hasTable('pooja_bookings')) {
            Schema::create('pooja_bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_number')->unique();
                $table->foreignId('pooja_service_id')->nullable()->constrained('pooja_services')->onDelete('set null');
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('devotee_name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->string('gotra')->nullable();
                $table->string('nakshatra')->nullable();
                $table->string('sankalp_purpose')->nullable();
                $table->date('booking_date');
                $table->string('time_slot')->default('प्रातः मंगला दर्शन');
                $table->text('prasad_address')->nullable();
                $table->decimal('amount', 10, 2)->default(0.00);
                $table->string('payment_status')->default('pending'); // pending, paid, verified, cash_at_temple
                $table->string('payment_id')->nullable();
                $table->string('status')->default('confirmed'); // confirmed, completed, cancelled
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 3. Newsletter Subscribers
        if (! Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('source')->default('website_footer');
                $table->boolean('is_active')->default(true);
                $table->timestamp('subscribed_at')->useCurrent();
                $table->timestamps();
            });
        }

        // 4. Volunteer Registrations
        if (! Schema::hasTable('volunteer_applications')) {
            Schema::create('volunteer_applications', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('occupation')->nullable();
                $table->json('seva_areas')->nullable();
                $table->string('availability')->nullable();
                $table->text('experience_notes')->nullable();
                $table->string('status')->default('pending'); // pending, approved, contacted, completed
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. Universal CMS Page SEO Settings
        if (! Schema::hasTable('page_seo_settings')) {
            Schema::create('page_seo_settings', function (Blueprint $table) {
                $table->id();
                $table->string('page_key')->unique();
                $table->string('page_title');
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->string('canonical_url')->nullable();
                $table->string('og_image')->nullable();
                $table->boolean('no_index')->default(false);
                $table->longText('structured_data')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_seo_settings');
        Schema::dropIfExists('volunteer_applications');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('pooja_bookings');
        Schema::dropIfExists('pooja_services');
    }
};
