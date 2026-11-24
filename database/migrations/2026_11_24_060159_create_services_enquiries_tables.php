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
        Schema::create('atithi_vishram_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enquiry_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('devotee_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('number_of_devotees')->default(1);
            $table->unsignedInteger('number_of_rooms')->default(1);
            $table->string('room_preference')->default('standard');
            $table->string('purpose_of_stay')->default('darshan_parikrama');
            $table->string('id_proof_type')->nullable();
            $table->text('special_requests')->nullable();
            $table->string('status')->default('new');
            $table->boolean('is_read')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('check_in_date');
        });

        Schema::create('temple_booking_enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('enquiry_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('devotee_name');
            $table->string('phone');
            $table->string('email');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('event_type');
            $table->string('event_title')->nullable();
            $table->date('proposed_date');
            $table->date('alternate_date')->nullable();
            $table->unsignedInteger('duration_days')->default(1);
            $table->unsignedInteger('expected_gathering')->default(50);
            $table->boolean('requires_priest')->default(true);
            $table->boolean('requires_catering')->default(false);
            $table->boolean('requires_accommodation')->default(false);
            $table->text('additional_requirements')->nullable();
            $table->string('status')->default('new');
            $table->boolean('is_read')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['event_type', 'proposed_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temple_booking_enquiries');
        Schema::dropIfExists('atithi_vishram_enquiries');
    }
};
