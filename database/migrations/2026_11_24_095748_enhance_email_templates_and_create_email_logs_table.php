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
        if (Schema::hasTable('organization_settings')) {
            Schema::table('organization_settings', function (Blueprint $table) {
                if (! Schema::hasColumn('organization_settings', 'is_email_sending_enabled')) {
                    $table->boolean('is_email_sending_enabled')->default(true)->after('is_smtp_configured');
                }
            });
        }

        if (Schema::hasTable('email_templates')) {
            Schema::table('email_templates', function (Blueprint $table) {
                if (! Schema::hasColumn('email_templates', 'category')) {
                    $table->string('category', 60)->default('general')->after('name');
                }
                if (! Schema::hasColumn('email_templates', 'description')) {
                    $table->text('description')->nullable()->after('slug');
                }
                if (! Schema::hasColumn('email_templates', 'placeholders_hint')) {
                    $table->text('placeholders_hint')->nullable()->after('content_html');
                }
                if (! Schema::hasColumn('email_templates', 'is_system')) {
                    $table->boolean('is_system')->default(true)->after('is_active');
                }
            });
        }

        if (! Schema::hasTable('email_logs')) {
            Schema::create('email_logs', function (Blueprint $table) {
                $table->id();
                $table->string('template_slug', 100)->nullable()->index();
                $table->string('recipient_email', 191)->index();
                $table->string('recipient_name', 191)->nullable();
                $table->string('subject', 255);
                $table->string('status', 30)->default('sent')->index(); // sent, failed, simulated, stopped
                $table->text('error_message')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');

        if (Schema::hasTable('email_templates')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $columns = ['category', 'description', 'placeholders_hint', 'is_system'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('email_templates', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('organization_settings')) {
            Schema::table('organization_settings', function (Blueprint $table) {
                if (Schema::hasColumn('organization_settings', 'is_email_sending_enabled')) {
                    $table->dropColumn('is_email_sending_enabled');
                }
            });
        }
    }
};
