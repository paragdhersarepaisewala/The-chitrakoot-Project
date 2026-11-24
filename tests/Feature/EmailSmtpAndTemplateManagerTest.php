<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\OrganizationSetting;
use App\Models\User;
use App\Services\EmailNotificationService;
use Database\Seeders\EmailTemplatesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailSmtpAndTemplateManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(EmailTemplatesSeeder::class);

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
            'approval_status' => 'approved',
        ]);
    }

    public function test_admin_can_access_email_smtp_and_template_manager_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/email-templates');

        $response->assertStatus(200);
        $response->assertSee('Email SMTP & Template Manager');
        $response->assertSee('Full SMTP Testing Suite');
        $response->assertSee('AUTOMATIC EMAIL SERVICES: ACTIVE');
    }

    public function test_every_cause_has_a_dedicated_email_template(): void
    {
        // 1. Universal / General Donation Receipt
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'donation_receipt',
            'category' => 'donations',
        ]);

        // 2. Gaushala & Cow Protection Dedicated Receipt
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'donation_cause_gaushala',
            'category' => 'donations',
        ]);

        // 3. Sanskrit Gurukul & Vedic Vidyalaya Dedicated Receipt
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'donation_cause_gurukul',
            'category' => 'donations',
        ]);

        // 4. Annakshetra & Bhandara Dedicated Receipt
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'donation_cause_annakshetra',
            'category' => 'donations',
        ]);

        // 5. Monthly Sustaining Patron Receipt
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'monthly_donation_active',
            'category' => 'donations',
        ]);

        // 6. Monthly Due Reminder
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'monthly_due_reminder',
            'category' => 'donations',
        ]);

        // 7. Donation Payment Failed Assistance
        $this->assertDatabaseHas('email_templates', [
            'slug' => 'donation_failed',
            'category' => 'donations',
        ]);
    }

    public function test_template_preview_endpoint_returns_rendered_html_and_subject(): void
    {
        $template = EmailTemplate::where('slug', 'donation_receipt')->firstOrFail();

        $response = $this->actingAs($this->adminUser)->getJson("/admin/email-templates/{$template->id}/preview");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $data = $response->json();
        $this->assertStringContainsString('Aditya Sharma', $data['rendered_html']);
        $this->assertStringContainsString('श्री कामदगिरि पीठम् सेवा ट्रस्ट', $data['rendered_html']);
        $this->assertStringNotContainsString('{name}', $data['rendered_html']);
    }

    public function test_admin_can_update_email_template(): void
    {
        $template = EmailTemplate::where('slug', 'donation_receipt')->firstOrFail();

        $response = $this->actingAs($this->adminUser)->putJson("/admin/email-templates/{$template->id}", [
            'name' => 'Updated Official Receipt Template',
            'subject' => 'Official Contribution Receipt #{receipt_id} — Verified',
            'category' => 'donations',
            'description' => 'Updated trigger note',
            'content_html' => '<div>Custom Content for {name}</div>',
            'is_active' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('email_templates', [
            'id' => $template->id,
            'name' => 'Updated Official Receipt Template',
            'subject' => 'Official Contribution Receipt #{receipt_id} — Verified',
        ]);
    }

    public function test_admin_can_create_custom_email_template_and_delete_it(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson('/admin/email-templates', [
            'name' => 'Deepotsav Festival Special Appeal',
            'slug' => 'deepotsav_special_appeal',
            'category' => 'donations',
            'subject' => 'Diwali Deepotsav Seva in Chitrakoot Dham',
            'description' => 'Custom campaign appeal email',
            'content_html' => '<p>Pranam {name}, please join us for Deepotsav.</p>',
            'is_active' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $customTemplate = EmailTemplate::where('slug', 'deepotsav_special_appeal')->firstOrFail();
        $this->assertFalse($customTemplate->is_system);

        // Delete custom template
        $delResponse = $this->actingAs($this->adminUser)->deleteJson("/admin/email-templates/{$customTemplate->id}");
        $delResponse->assertStatus(200);
        $delResponse->assertJson(['success' => true]);

        $this->assertDatabaseMissing('email_templates', ['id' => $customTemplate->id]);
    }

    public function test_system_templates_cannot_be_deleted(): void
    {
        $systemTemplate = EmailTemplate::where('slug', 'donation_receipt')->firstOrFail();
        $this->assertTrue($systemTemplate->is_system);

        $response = $this->actingAs($this->adminUser)->deleteJson("/admin/email-templates/{$systemTemplate->id}");
        $response->assertStatus(422);

        $this->assertDatabaseHas('email_templates', ['id' => $systemTemplate->id]);
    }

    public function test_admin_can_toggle_individual_template_active_status(): void
    {
        $template = EmailTemplate::where('slug', 'volunteer_rejected')->firstOrFail();
        $this->assertTrue($template->is_active);

        $response = $this->actingAs($this->adminUser)->postJson("/admin/email-templates/{$template->id}/toggle");
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_active' => false]);

        $template->refresh();
        $this->assertFalse($template->is_active);
    }

    public function test_admin_can_toggle_master_email_sending_killswitch(): void
    {
        $settings = OrganizationSetting::getSettings();
        $this->assertTrue($settings->isEmailSendingEnabled());

        // Toggle OFF (Stop all email sending)
        $response = $this->actingAs($this->adminUser)->postJson('/admin/email-templates/toggle-service');
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'is_enabled' => false]);

        $settings->refresh();
        $this->assertFalse($settings->isEmailSendingEnabled());

        // When stopped, automated dispatches are intercepted and recorded as stopped
        $sent = EmailNotificationService::send('welcome_registration', 'devotee@example.com', 'Devotee Test', []);
        $this->assertFalse($sent);

        $this->assertDatabaseHas('email_logs', [
            'template_slug' => 'welcome_registration',
            'recipient_email' => 'devotee@example.com',
            'status' => 'stopped',
        ]);
    }

    public function test_admin_can_save_smtp_credentials(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/email-templates/smtp-config', [
            'smtp_host' => 'smtp.hostinger.com',
            'smtp_port' => 587,
            'smtp_username' => 'seva@chitrakootpeetham.org',
            'smtp_password' => 'SecurePass123!',
            'smtp_encryption' => 'tls',
            'smtp_from_address' => 'seva@chitrakootpeetham.org',
            'smtp_from_name' => 'Shri Kamadgiri Peetham Sewa Trust',
        ]);

        $response->assertSessionHas('success');

        $settings = OrganizationSetting::getSettings();
        $this->assertEquals('smtp.hostinger.com', $settings->smtp_host);
        $this->assertEquals(587, $settings->smtp_port);
        $this->assertEquals('seva@chitrakootpeetham.org', $settings->smtp_username);
        $this->assertTrue($settings->is_smtp_configured);
    }

    public function test_smtp_diagnostic_test_suite_returns_structured_report(): void
    {
        $response = $this->actingAs($this->adminUser)->postJson('/admin/email-templates/test-smtp', [
            'smtp_host' => '127.0.0.1',
            'smtp_port' => 2525,
            'smtp_username' => 'testuser',
            'smtp_password' => 'testpass',
        ]);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertArrayHasKey('steps', $data);
        $this->assertNotEmpty($data['steps']);
    }

    public function test_automatic_email_sent_on_contact_form_submission(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Manoj Pandey',
            'email' => 'manoj.pandey@example.com',
            'phone' => '+91 94150 12345',
            'subject' => 'चित्रकूट यात्रा एवं परिक्रमा मार्गदर्शन',
            'message' => 'हम सपरिवार कामदगिरि परिक्रमा एवं रामघाट आरती दर्शन हेतु आ रहे हैं।',
        ]);

        $response->assertSessionHas('success');

        $inquiry = ContactInquiry::where('email', 'manoj.pandey@example.com')->firstOrFail();

        $this->assertDatabaseHas('email_logs', [
            'template_slug' => 'contact_inquiry_received',
            'recipient_email' => 'manoj.pandey@example.com',
        ]);
    }

    public function test_automatic_email_sent_on_newsletter_subscription(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'reader.journal@example.com',
        ]);

        $this->assertDatabaseHas('email_logs', [
            'template_slug' => 'newsletter_welcome',
            'recipient_email' => 'reader.journal@example.com',
        ]);
    }

    public function test_automatic_cause_specific_receipt_dispatch_for_gaushala(): void
    {
        $cause = Cause::firstOrCreate(
            ['slug' => 'gaushala-seva'],
            [
                'title' => 'गौ सेवा एवं गौ संरक्षण प्रकल्प',
                'target_amount' => 500000,
                'minimum_amount' => 100,
                'status' => 'active',
            ]
        );

        $donation = Donation::create([
            'donor_name' => 'Rameshwar Tiwari',
            'donor_email' => 'rameshwar.tiwari@example.com',
            'donor_phone' => '+91 94150 54321',
            'cause_id' => $cause->id,
            'donation_type' => 'cause_specific',
            'amount' => 5100.00,
            'currency' => 'INR',
            'status' => 'verified',
            'receipt_number' => 'SKP-TEST-GAU-001',
            'paid_at' => now(),
        ]);

        EmailNotificationService::sendDonationReceipt($donation);

        $this->assertDatabaseHas('email_logs', [
            'template_slug' => 'donation_cause_gaushala',
            'recipient_email' => 'rameshwar.tiwari@example.com',
        ]);
    }

    public function test_admin_can_send_in_dashboard_email_reply_to_inquiry(): void
    {
        $inquiry = ContactInquiry::create([
            'name' => 'Suman Sharma',
            'email' => 'suman.sharma@example.com',
            'phone' => '+91 98765 00000',
            'subject' => 'अभिषेक समय सारिणी',
            'message' => 'कृपया प्रातःकाल अभिषेक का समय बताएं।',
            'status' => 'new',
            'is_read' => false,
        ]);

        $response = $this->actingAs($this->adminUser)->post("/admin/contact-inquiries/{$inquiry->id}/reply", [
            'reply_message' => 'जय कामतानाथ! प्रातः मंगला दर्शन एवं अभिषेक का समय प्रातः 05:30 बजे से है।',
        ]);

        $response->assertSessionHas('success');

        $inquiry->refresh();
        $this->assertEquals('replied', $inquiry->status);

        $this->assertDatabaseHas('email_logs', [
            'template_slug' => 'contact_inquiry_reply',
            'recipient_email' => 'suman.sharma@example.com',
        ]);
    }
}
