<?php

namespace Tests\Feature;

use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\MediaFile;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use App\Services\ShortcodeParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class SecurityPenetrationRemediationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_notification_poll_includes_commenter_name_and_dashboard_escapes_html(): void
    {
        $admin = User::factory()->create(['role' => 'super_admin', 'is_active' => true]);
        $post = Post::where('status', 'published')->firstOrFail();

        PostComment::create([
            'post_id' => $post->id,
            'name' => 'Attacker <img src=x onerror=alert(1)>',
            'comment' => 'Payload <script>alert(2)</script>',
            'is_approved' => false,
        ]);

        ContactInquiry::create([
            'name' => 'Guest <svg onload=alert(3)>',
            'email' => 'guest@example.com',
            'subject' => 'Test Subject',
            'message' => 'Hello',
            'status' => 'new',
            'is_read' => false,
        ]);

        $poll = $this->actingAs($admin)->getJson('/admin/api/notifications/poll');
        $poll->assertStatus(200);

        $notifications = collect($poll->json('notifications'));
        $commentNotif = $notifications->firstWhere('type', 'comment');
        $this->assertNotNull($commentNotif);
        $this->assertStringContainsString('Attacker', $commentNotif['description']);

        $adminDashboard = $this->actingAs($admin)->get('/admin');
        $adminDashboard->assertStatus(200);
        $adminDashboard->assertSee('escAdminHtml(', false);
    }

    public function test_shortcode_parser_blocks_javascript_urls_onclick_breakout_and_poll_dom_xss(): void
    {
        $jsImage = ShortcodeParser::parse('[image src="javascript:alert(1)" alt="bad"]');
        $this->assertSame('', $jsImage);

        $breakoutImage = ShortcodeParser::parse('[image src="https://example.com/photo.jpg\' , alert(1), \'" caption="Safe"]');
        $this->assertStringNotContainsString("openSingleImageLightbox('", $breakoutImage);
        $this->assertStringContainsString('data-lightbox-src="', $breakoutImage);

        $jsVideo = ShortcodeParser::parse('[video src="javascript:alert(1)"]');
        $this->assertSame('', $jsVideo);

        $poll = ShortcodeParser::parse('[poll question="Test?" opt1="<img src=x onerror=alert(1)>"]');
        $this->assertStringContainsString('escPollHtml(opt)', $poll);
        $this->assertStringNotContainsString('<span>${opt}</span>', $poll);
    }

    public function test_donation_verify_payment_blocks_order_swap_and_payment_replay(): void
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update(['razorpay_key_secret' => 'rzp_secret_regression_test']);

        $donationCheap = Donation::create([
            'donation_type' => 'cause_specific',
            'amount' => 10,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => 'order_cheap_10',
            'status' => 'pending',
        ]);

        $donationHigh = Donation::create([
            'donation_type' => 'cause_specific',
            'amount' => 50000,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => 'order_high_50000',
            'status' => 'pending',
        ]);

        $cheapSig = hash_hmac('sha256', 'order_cheap_10|pay_cheap_10', 'rzp_secret_regression_test');

        // Attempt to verify high-value donation using cheap order ID & signature
        $swapAttempt = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donationHigh->id,
            'razorpay_order_id' => 'order_cheap_10',
            'razorpay_payment_id' => 'pay_cheap_10',
            'razorpay_signature' => $cheapSig,
        ]);
        $swapAttempt->assertStatus(400);
        $this->assertEquals('pending', $donationHigh->fresh()->status);

        // Legitimate verification of cheap donation succeeds
        $legitAttempt = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donationCheap->id,
            'razorpay_order_id' => 'order_cheap_10',
            'razorpay_payment_id' => 'pay_cheap_10',
            'razorpay_signature' => $cheapSig,
        ]);
        $legitAttempt->assertStatus(200);
        $this->assertEquals('verified', $donationCheap->fresh()->status);

        // Replay of pay_cheap_10 against high-value donation fails
        $highSigWithReplayedPay = hash_hmac('sha256', 'order_high_50000|pay_cheap_10', 'rzp_secret_regression_test');
        $replayAttempt = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donationHigh->id,
            'razorpay_order_id' => 'order_high_50000',
            'razorpay_payment_id' => 'pay_cheap_10',
            'razorpay_signature' => $highSigWithReplayedPay,
        ]);
        $replayAttempt->assertStatus(400);
        $this->assertEquals('pending', $donationHigh->fresh()->status);
    }

    public function test_donation_webhook_rejects_underpaid_amount_mismatch(): void
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update(['razorpay_key_secret' => 'webhook_amount_secret']);

        $donation = Donation::create([
            'donation_type' => 'cause_specific',
            'amount' => 1000.00,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => 'order_amt_check_1000',
            'status' => 'pending',
        ]);

        $payload = json_encode([
            'event' => 'payment.captured',
            'payload' => [
                'payment' => [
                    'entity' => [
                        'id' => 'pay_underpaid_1',
                        'order_id' => 'order_amt_check_1000',
                        'amount' => 100, // ₹1 instead of ₹1000
                        'currency' => 'INR',
                    ],
                ],
            ],
        ]);

        $sig = hash_hmac('sha256', $payload, 'webhook_amount_secret');

        $response = $this->call(
            'POST',
            '/api/donations/webhook',
            [],
            [],
            [],
            $this->transformHeadersToServerVars([
                'X-Razorpay-Signature' => $sig,
                'Content-Type' => 'application/json',
            ]),
            $payload
        );

        $response->assertStatus(400);
        $this->assertEquals('pending', $donation->fresh()->status);
    }

    public function test_pooja_quotation_blocks_order_swap_and_payment_replay(): void
    {
        $settings = OrganizationSetting::getSettings();
        $settings->update(['razorpay_key_secret' => 'pooja_secret_xyz']);

        $service = PoojaService::firstOrCreate(
            ['slug' => 'regression-pooja-service'],
            ['title_hi' => 'अनुष्ठान', 'title_en' => 'Ritual', 'is_active' => true]
        );

        $booking1 = PoojaBooking::create([
            'booking_number' => 'SKP-PB-REG-01',
            'pooja_service_id' => $service->id,
            'ritual_name' => 'रुद्राभिषेक',
            'devotee_name' => 'Devotee One',
            'phone' => '9876543210',
            'email' => 'one@example.com',
            'booking_date' => now()->addDays(4)->format('Y-m-d'),
            'time_slot' => '07:00 AM',
            'amount' => 500,
            'quotation_amount' => 500,
            'quotation_token' => 'quote_token_one_12345',
            'quotation_expires_at' => now()->addHours(6),
            'status' => 'quoted',
            'payment_status' => 'pending',
        ]);

        $booking2 = PoojaBooking::create([
            'booking_number' => 'SKP-PB-REG-02',
            'pooja_service_id' => $service->id,
            'ritual_name' => 'महामृत्युंजय',
            'devotee_name' => 'Devotee Two',
            'phone' => '9876543211',
            'email' => 'two@example.com',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'time_slot' => '08:00 AM',
            'amount' => 11000,
            'quotation_amount' => 11000,
            'quotation_token' => 'quote_token_two_12345',
            'quotation_expires_at' => now()->addHours(6),
            'status' => 'quoted',
            'payment_status' => 'pending',
        ]);

        $order1 = $this->postJson("/pooja/quotation/{$booking1->quotation_token}/create-order");
        $order1->assertStatus(200);
        $orderId1 = $order1->json('order_id');

        $order2 = $this->postJson("/pooja/quotation/{$booking2->quotation_token}/create-order");
        $order2->assertStatus(200);
        $orderId2 = $order2->json('order_id');

        $sig1 = hash_hmac('sha256', "{$orderId1}|pay_pooja_1", 'pooja_secret_xyz');

        // Attempt to use booking 1's order ID to verify booking 2
        $swapRes = $this->postJson("/pooja/quotation/{$booking2->quotation_token}/verify", [
            'razorpay_order_id' => $orderId1,
            'razorpay_payment_id' => 'pay_pooja_1',
            'razorpay_signature' => $sig1,
        ]);
        $swapRes->assertStatus(422);
        $this->assertEquals('pending', $booking2->fresh()->payment_status);

        // Legitimate verify for booking 1
        $okRes = $this->postJson("/pooja/quotation/{$booking1->quotation_token}/verify", [
            'razorpay_order_id' => $orderId1,
            'razorpay_payment_id' => 'pay_pooja_1',
            'razorpay_signature' => $sig1,
        ]);
        $okRes->assertStatus(200);
        $this->assertEquals('paid', $booking1->fresh()->payment_status);

        // Replay pay_pooja_1 against booking 2
        $sig2Replay = hash_hmac('sha256', "{$orderId2}|pay_pooja_1", 'pooja_secret_xyz');
        $replayRes = $this->postJson("/pooja/quotation/{$booking2->quotation_token}/verify", [
            'razorpay_order_id' => $orderId2,
            'razorpay_payment_id' => 'pay_pooja_1',
            'razorpay_signature' => $sig2Replay,
        ]);
        $replayRes->assertStatus(422);
        $this->assertEquals('pending', $booking2->fresh()->payment_status);
    }

    public function test_otp_is_invalidated_after_five_failed_verification_attempts(): void
    {
        $user = User::factory()->unverified()->create([
            'email' => 'otpbrute@example.com',
            'email_otp' => '654321',
            'email_otp_expires_at' => now()->addMinutes(10),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $res = $this->actingAs($user)->post('/register/verify-otp', ['otp' => '000000']);
            $res->assertSessionHasErrors('otp');
        }

        // OTP must be invalidated in DB after 5 failed tries
        $this->assertNull($user->fresh()->email_otp);

        // Even the formerly valid OTP must now fail
        $later = $this->actingAs($user)->post('/register/verify-otp', ['otp' => '654321']);
        $later->assertSessionHasErrors('otp');
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_password_reset_otp_is_invalidated_after_five_failed_attempts(): void
    {
        $resetUser = User::factory()->create([
            'email' => 'resetbrute@example.com',
            'email_otp' => '778899',
            'email_otp_expires_at' => now()->addMinutes(10),
        ]);

        for ($i = 0; $i < 5; $i++) {
            $res = $this->post('/reset-password', [
                'email' => 'resetbrute@example.com',
                'otp' => '111111',
                'password' => 'NewSecurePass123!',
                'password_confirmation' => 'NewSecurePass123!',
            ]);
            $res->assertStatus(302);
            $res->assertSessionHasErrors('otp');
        }

        $this->assertNull($resetUser->fresh()->email_otp);
    }

    public function test_inactive_or_rejected_user_cannot_login_or_access_admin(): void
    {
        $inactiveAdmin = User::factory()->create([
            'email' => 'inactive.admin@example.com',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'super_admin',
            'is_active' => false,
            'approval_status' => 'approved',
        ]);

        $loginAttempt = $this->post('/login', [
            'email' => 'inactive.admin@example.com',
            'password' => 'SecretPass123!',
        ]);
        $loginAttempt->assertSessionHasErrors('email');
        $this->assertGuest();

        $directAccess = $this->actingAs($inactiveAdmin)->get('/admin');
        $directAccess->assertStatus(403);
    }

    #[TestWith(['content_admin'])]
    #[TestWith(['media_editor'])]
    #[TestWith(['membership_admin'])]
    public function test_non_super_admin_staff_cannot_access_sensitive_admin_controls(string $role): void
    {
        $staff = User::factory()->create([
            'role' => $role,
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $this->actingAs($staff)->get('/admin/settings')->assertStatus(403);
        $this->actingAs($staff)->get('/admin/users')->assertStatus(403);
        $this->actingAs($staff)->get('/admin/email-templates')->assertStatus(403);
        $this->actingAs($staff)->post('/admin/flush-cache')->assertStatus(403);
        $this->actingAs($staff)->get('/admin/donations/export-csv')->assertStatus(403);
    }

    public function test_donation_admin_can_access_financial_routes_but_not_settings_or_users(): void
    {
        $donationAdmin = User::factory()->create([
            'role' => 'donation_admin',
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $this->actingAs($donationAdmin)->get('/admin/donations/export-csv')->assertStatus(200);
        $this->actingAs($donationAdmin)->get('/admin/causes')->assertStatus(200);
        $this->actingAs($donationAdmin)->get('/admin/pooja-bookings')->assertStatus(200);

        $this->actingAs($donationAdmin)->get('/admin/settings')->assertStatus(403);
        $this->actingAs($donationAdmin)->get('/admin/users')->assertStatus(403);
    }

    public function test_super_admin_cannot_demote_themselves(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
            'approval_status' => 'approved',
        ]);

        $res = $this->actingAs($superAdmin)->post("/admin/users/{$superAdmin->id}/role", [
            'role' => 'donor',
            'approval_status' => 'approved',
        ]);

        $res->assertSessionHasErrors('role');
        $this->assertEquals('super_admin', $superAdmin->fresh()->role);
    }

    public function test_csv_export_neutralizes_formula_injection(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'is_active' => true]);

        Donation::create([
            'donor_name' => '=CMD|"/C calc"!A0',
            'donor_email' => 'csv@example.com',
            'donor_phone' => '9876543210',
            'donation_type' => 'cause_specific',
            'amount' => 101.00,
            'currency' => 'INR',
            'status' => 'verified',
            'receipt_number' => 'SKP-CSV-SEC-01',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin/donations/export-csv');
        $response->assertStatus(200);
        $csv = $response->streamedContent();
        $this->assertStringContainsString("'=CMD|", $csv);
    }

    public function test_svg_upload_strips_script_tags_and_event_handlers(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'is_active' => true]);

        $maliciousSvg = '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><script>alert(2)</script><circle cx="10" cy="10" r="5" onclick="alert(3)"/></svg>';
        $svgFile = UploadedFile::fake()->createWithContent('icon.svg', $maliciousSvg);

        $response = $this->actingAs($superAdmin)->postJson('/admin/media-gallery/upload', [
            'file' => $svgFile,
        ]);

        $response->assertStatus(200);
        $relativePath = $response->json('relative_path');
        $fullPath = public_path(ltrim((string) $relativePath, '/'));

        $this->assertFileExists($fullPath);
        $storedContent = File::get($fullPath);
        $this->assertStringNotContainsString('<script', strtolower($storedContent));
        $this->assertStringNotContainsString('onload=', strtolower($storedContent));
        $this->assertStringNotContainsString('onclick=', strtolower($storedContent));

        // Cleanup uploaded test file
        File::delete($fullPath);
        MediaFile::where('file_path', $relativePath)->delete();
    }
}
