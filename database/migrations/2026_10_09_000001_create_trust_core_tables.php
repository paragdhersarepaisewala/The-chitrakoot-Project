<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supporter_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->string('father_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->timestamps();
        });

        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('membership_id')->unique();
            $table->enum('status', ['inactive', 'pending', 'active', 'suspended', 'expired'])->default('inactive');
            $table->integer('consecutive_qualifying_months')->default(0);
            $table->string('last_qualifying_month')->nullable(); // format YYYY-MM
            $table->date('became_member_at')->nullable();
            $table->timestamps();
        });

        Schema::create('membership_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('previous_status')->nullable();
            $table->string('new_status');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('causes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('target_amount', 12, 2)->default(0.00);
            $table->decimal('minimum_amount', 12, 2)->default(100.00);
            $table->string('image_path')->nullable();
            $table->date('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->boolean('allow_donations_after_target')->default(true);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cause_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('donation_type', ['cause_specific', 'monthly_sustaining'])->default('cause_specific');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('INR');
            $table->string('payment_gateway')->default('razorpay');
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_payment_id')->nullable();
            $table->string('gateway_signature')->nullable();
            $table->enum('status', ['pending', 'verified', 'failed', 'refunded'])->default('pending');
            $table->string('receipt_number')->nullable()->unique();
            $table->string('qualifying_year_month')->nullable(); // format YYYY-MM if monthly_sustaining
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->string('receipt_number')->unique();
            $table->string('donor_name');
            $table->decimal('amount', 12, 2);
            $table->string('cause_title');
            $table->string('donation_type');
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('organization_settings', function (Blueprint $table) {
            $table->id();
            $table->string('org_name')->default('Shri Kamadgiri Peetham Sewa Trust');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('reg_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->string('authorized_signature_path')->nullable();
            $table->string('receipt_header_title')->nullable();
            $table->text('receipt_legal_note')->nullable();
            $table->boolean('show_donation_totals')->default(true);

            // Razorpay Configuration
            $table->string('razorpay_key_id')->nullable();
            $table->string('razorpay_key_secret')->nullable();

            // SMTP Configuration
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('smtp_from_address')->nullable();
            $table->string('smtp_from_name')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_settings');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('causes');
        Schema::dropIfExists('membership_status_histories');
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('supporter_profiles');
    }
};
