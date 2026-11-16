<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationAndAuthAgeVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_rejects_under_18(): void
    {
        $under18Dob = Carbon::now()->subYears(16)->format('Y-m-d');

        $response = $this->post('/register/step-1', [
            'name' => 'Minor Devotee',
            'email' => 'minor@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'date_of_birth' => $under18Dob,
            'role' => 'donor',
        ]);

        $response->assertSessionHasErrors('date_of_birth');
        $this->assertDatabaseMissing('users', ['email' => 'minor@example.com']);
    }

    public function test_registration_accepts_18_plus(): void
    {
        $adultDob = Carbon::now()->subYears(22)->format('Y-m-d');

        $response = $this->post('/register/step-1', [
            'name' => 'Adult Devotee',
            'email' => 'adult@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'date_of_birth' => $adultDob,
            'role' => 'donor',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'adult@example.com']);
        $user = User::where('email', 'adult@example.com')->first();
        $this->assertNotNull($user->profile);
        $this->assertEquals($adultDob, Carbon::parse($user->profile->date_of_birth)->format('Y-m-d'));
    }

    public function test_unverified_devotee_is_redirected_to_otp_step_when_smtp_is_configured(): void
    {
        // Configure SMTP in organization settings
        $settings = OrganizationSetting::getSettings();
        $settings->update([
            'smtp_host' => 'smtp.mailtrap.io',
            'smtp_port' => 2525,
            'smtp_username' => 'testuser',
            'smtp_password' => 'testpass',
            'is_smtp_configured' => true,
        ]);

        $user = User::factory()->create([
            'email_verified_at' => null,
            'role' => 'donor',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/register?step=2&email='.urlencode($user->email));
    }

    public function test_verified_devotee_can_access_dashboard_normally(): void
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update([
            'smtp_host' => 'smtp.mailtrap.io',
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'role' => 'donor',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('प्रणाम');
    }

    public function test_admin_notification_polling_and_mark_seen(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        // Create a donation
        Donation::create([
            'donor_name' => 'Ramesh Kumar',
            'donor_email' => 'ramesh@example.com',
            'donor_phone' => '9876543210',
            'amount' => 5100,
            'payment_status' => 'success',
            'receipt_number' => 'REC-NOTIF-TEST',
        ]);

        $response = $this->actingAs($admin)->getJson('/admin/api/notifications/poll');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_count',
            'notifications' => [
                '*' => ['id', 'type', 'title', 'description', 'url', 'time_ago', 'is_unread'],
            ],
        ]);

        $this->assertGreaterThanOrEqual(1, $response->json('unread_count'));

        // Mark seen
        $markSeenResponse = $this->actingAs($admin)->postJson('/admin/api/notifications/mark-seen');
        $markSeenResponse->assertStatus(200);
        $this->assertTrue($markSeenResponse->json('success'));

        // Poll again - unread count should now be 0
        $pollAgain = $this->actingAs($admin)->getJson('/admin/api/notifications/poll');
        $this->assertEquals(0, $pollAgain->json('unread_count'));
    }

    public function test_devotee_notification_polling_and_quotation_alert(): void
    {
        $devotee = User::factory()->create([
            'role' => 'donor',
            'email' => 'devoteenotif@example.com',
            'email_verified_at' => now(),
        ]);

        // Create a booking with status quotation_sent
        PoojaBooking::create([
            'user_id' => $devotee->id,
            'booking_number' => 'BK-NOTIF-01',
            'devotee_name' => $devotee->name,
            'email' => $devotee->email,
            'phone' => '9876543210',
            'pooja_name' => 'विशेष रुद्राभिषेक',
            'booking_date' => now()->addDays(2),
            'status' => 'quoted',
            'quotation_token' => 'test-quotation-token-12345',
            'quotation_amount' => 3100,
            'quotation_expires_at' => now()->addHours(24),
        ]);

        $response = $this->actingAs($devotee)->getJson('/api/user/notifications/poll');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'unread_count',
            'active_quotations_count',
            'notifications',
        ]);

        $this->assertEquals(1, $response->json('active_quotations_count'));
        $this->assertGreaterThanOrEqual(1, $response->json('unread_count'));

        // Mark seen
        $markSeen = $this->actingAs($devotee)->postJson('/api/user/notifications/mark-seen');
        $markSeen->assertStatus(200);
        $this->assertTrue($markSeen->json('success'));

        $pollAgain = $this->actingAs($devotee)->getJson('/api/user/notifications/poll');
        $this->assertEquals(0, $pollAgain->json('unread_count'));
    }

    public function test_devotee_can_resend_registration_otp(): void
    {
        $user = User::factory()->create([
            'email' => 'resendtest@example.com',
            'email_verified_at' => null,
            'email_otp' => '111111',
            'email_otp_expires_at' => now()->addMinutes(5),
            'role' => 'donor',
        ]);

        $response = $this->post('/register/resend-otp', [
            'email' => 'resendtest@example.com',
        ]);

        $response->assertRedirect('/register?step=2&email='.urlencode('resendtest@example.com'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotEquals('111111', $user->email_otp);
        $this->assertEquals(6, strlen($user->email_otp));
    }

    public function test_resend_otp_button_rendered_on_step_2(): void
    {
        $response = $this->get('/register?step=2&email=test@example.com');
        $response->assertStatus(200);
        $response->assertSee('Resend OTP Code');
        $response->assertSee("Didn't receive the OTP code?", false);
    }

    public function test_resend_otp_for_verified_user_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'alreadyverified@example.com',
            'email_verified_at' => now(),
            'role' => 'donor',
        ]);

        $response = $this->post('/register/resend-otp', [
            'email' => 'alreadyverified@example.com',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_temporary_otp_bypass_available_when_smtp_not_configured(): void
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update([
            'is_smtp_configured' => false,
            'smtp_host' => null,
        ]);

        $user = User::factory()->create([
            'email' => 'bypasstest@example.com',
            'email_verified_at' => null,
            'email_otp' => '654321',
            'role' => 'donor',
        ]);

        // Step 2 page renders testing mode banner and bypass option
        $response = $this->actingAs($user)->get('/register?step=2&email=bypasstest@example.com');
        $response->assertStatus(200);
        $response->assertSee('Testing Mode (SMTP Server Not Attached)');
        $response->assertSee('Auto-fill Testing Code');
        $response->assertSee('Bypass OTP Verification');

        // Can bypass OTP and proceed to step 3
        $bypassResponse = $this->actingAs($user)->post('/register/bypass-otp', [
            'email' => 'bypasstest@example.com',
        ]);

        $bypassResponse->assertRedirect('/register?step=3');
        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->email_otp);
    }

    public function test_saving_smtp_settings_auto_removes_testing_otp_and_disables_bypass(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin']);

        // Admin saves SMTP configuration
        $response = $this->actingAs($admin)->post(route('admin.updateSmtpConfig'), [
            'smtp_host' => 'smtp.liveprovider.com',
            'smtp_port' => 587,
            'smtp_username' => 'admin@kamadgiripeetham.org',
            'smtp_password' => 'secret123',
            'smtp_encryption' => 'tls',
            'smtp_from_address' => 'noreply@kamadgiripeetham.org',
            'smtp_from_name' => 'Shri Kamadgiri Peetham Sewa Trust',
        ]);

        $response->assertSessionHas('success');

        $settings = OrganizationSetting::getSettings();
        $this->assertTrue($settings->isSmtpConfigured());

        $user = User::factory()->create([
            'email' => 'realdevotee@example.com',
            'email_verified_at' => null,
            'email_otp' => '789123',
            'role' => 'donor',
        ]);

        // Step 2 must NO LONGER render temporary testing banner, testing OTP, or bypass button
        $step2Response = $this->actingAs($user)->get('/register?step=2&email=realdevotee@example.com');
        $step2Response->assertStatus(200);
        $step2Response->assertDontSee('Testing Mode (SMTP Server Not Attached)');
        $step2Response->assertDontSee('Auto-fill Testing Code');
        $step2Response->assertDontSee('Bypass OTP Verification');

        // Bypassing is rejected
        $bypassAttempt = $this->actingAs($user)->post('/register/bypass-otp', [
            'email' => 'realdevotee@example.com',
        ]);
        $bypassAttempt->assertSessionHasErrors('otp');
        $user->refresh();
        $this->assertNull($user->email_verified_at);
    }
}
