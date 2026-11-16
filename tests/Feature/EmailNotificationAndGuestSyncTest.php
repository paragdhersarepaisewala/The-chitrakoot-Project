<?php

namespace Tests\Feature;

use App\Models\Cause;
use App\Models\Donation;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\User;
use App\Models\VolunteerApplication;
use Database\Seeders\CmsFeaturesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationAndGuestSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed default templates
        $this->seed(CmsFeaturesSeeder::class);
    }

    public function test_guest_volunteer_application_sends_acknowledgment_email(): void
    {
        Mail::fake();

        $response = $this->post('/volunteer/apply', [
            'name' => 'Aarav Sharma',
            'email' => 'aarav.sharma@example.com',
            'phone' => '+91 98765 43210',
            'city' => 'Chitrakoot',
            'state' => 'MP',
            'seva_areas' => ['गौसेवा', 'वैदिक गुरुकुल'],
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('volunteer_applications', [
            'name' => 'Aarav Sharma',
            'email' => 'aarav.sharma@example.com',
            'status' => 'pending',
        ]);

        Mail::assertSentCount(1);
    }

    public function test_admin_approving_and_rejecting_volunteer_dispatches_emails(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => 'super_admin']);
        $applicant = VolunteerApplication::create([
            'name' => 'Meera Patel',
            'email' => 'meera.patel@example.com',
            'phone' => '+91 94150 11111',
            'seva_areas' => ['अन्नक्षेत्र सेवा'],
            'status' => 'pending',
        ]);

        // Admin approves
        $response = $this->actingAs($admin)->post("/admin/volunteers/{$applicant->id}/status", [
            'status' => 'approved',
            'admin_notes' => 'Welcome to Kamadgiri Seva Dal.',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('approved', $applicant->fresh()->status);
        Mail::assertSentCount(1);

        // Admin rejects another applicant
        $applicant2 = VolunteerApplication::create([
            'name' => 'Rohan Gupta',
            'email' => 'rohan.gupta@example.com',
            'phone' => '+91 94150 22222',
            'seva_areas' => ['गौशाला'],
            'status' => 'pending',
        ]);

        $this->actingAs($admin)->post("/admin/volunteers/{$applicant2->id}/status", [
            'status' => 'rejected',
            'admin_notes' => 'Slots full for this season.',
        ]);

        $this->assertEquals('rejected', $applicant2->fresh()->status);
        Mail::assertSentCount(2);
    }

    public function test_guest_donation_creates_receipt_and_sends_email_with_guest_info(): void
    {
        Mail::fake();

        $cause = Cause::create([
            'title' => 'Gau Seva Ashram',
            'slug' => 'gau-seva-ashram',
            'short_description' => 'Dedicated Gau Seva',
            'goal_amount' => 100000,
            'raised_amount' => 5000,
            'status' => 'active',
        ]);

        // Guest creates donation order
        $orderRes = $this->postJson('/api/donations/create-order', [
            'cause_id' => $cause->id,
            'donation_type' => 'cause_specific',
            'amount' => 1100,
            'donor_name' => 'Vikram Singhania',
            'donor_email' => 'vikram.singhania@example.com',
            'donor_phone' => '+91 98888 77777',
        ]);

        $orderRes->assertStatus(200)->assertJson(['success' => true]);
        $donationId = $orderRes->json('donation_id');

        $this->assertDatabaseHas('donations', [
            'id' => $donationId,
            'donor_name' => 'Vikram Singhania',
            'donor_email' => 'vikram.singhania@example.com',
            'status' => 'pending',
            'user_id' => null,
        ]);

        // Verify payment
        $verifyRes = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donationId,
            'razorpay_payment_id' => 'pay_mock_12345',
            'donor_name' => 'Vikram Singhania',
            'donor_email' => 'vikram.singhania@example.com',
            'donor_phone' => '+91 98888 77777',
        ]);

        $verifyRes->assertStatus(200)->assertJson(['success' => true]);

        $this->assertDatabaseHas('donations', [
            'id' => $donationId,
            'status' => 'verified',
            'donor_email' => 'vikram.singhania@example.com',
        ]);

        $this->assertDatabaseHas('receipts', [
            'donation_id' => $donationId,
            'donor_name' => 'Vikram Singhania',
            'donor_email' => 'vikram.singhania@example.com',
        ]);

        Mail::assertSentCount(1);
    }

    public function test_guest_pooja_booking_sends_email_and_admin_confirmation_notifies_devotee(): void
    {
        Mail::fake();

        $service = PoojaService::create([
            'title_hi' => 'कामदगिरि नित्य अभिषेक',
            'title_en' => 'Kamadgiri Abhishek',
            'slug' => 'kamadgiri-abhishek',
            'suggested_donation_amount' => 501,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        // Guest books pooja
        $bookRes = $this->post('/pooja/book', [
            'pooja_service_id' => $service->id,
            'devotee_name' => 'Sunita Devi',
            'phone' => '+91 98111 22334',
            'email' => 'sunita.devi@example.com',
            'gotra' => 'शांडिल्य',
            'nakshatra' => 'अश्विनी',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'time_slot' => 'प्रातः मंगला दर्शन',
            'amount' => 501,
        ]);

        $booking = PoojaBooking::where('email', 'sunita.devi@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('pending', $booking->status);
        $this->assertNull($booking->user_id);

        Mail::assertSentCount(1);

        // Admin confirms the booking
        $admin = User::factory()->create(['role' => 'super_admin']);
        $this->actingAs($admin)->post("/admin/pooja-bookings/{$booking->id}/status", [
            'booking_status' => 'confirmed',
            'admin_notes' => 'Acharya Ji will conduct the abhishek at 07:00 AM.',
        ]);

        $this->assertEquals('confirmed', $booking->fresh()->status);
        Mail::assertSentCount(2);
    }

    public function test_guest_history_automatically_synchronizes_when_signing_up_or_viewing_dashboard(): void
    {
        $guestEmail = 'devotee.family@example.com';

        // 1. Prior guest donation
        $donation = Donation::create([
            'user_id' => null,
            'donor_name' => 'Devotee Family',
            'donor_email' => $guestEmail,
            'amount' => 2100,
            'donation_type' => 'cause_specific',
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'status' => 'verified',
            'receipt_number' => 'SKP-REC-GUEST-01',
            'paid_at' => now(),
        ]);

        // 2. Prior guest volunteer application
        $volunteer = VolunteerApplication::create([
            'user_id' => null,
            'name' => 'Devotee Family Member',
            'email' => $guestEmail,
            'phone' => '+91 94150 99999',
            'seva_areas' => ['गौसेवा'],
            'status' => 'approved',
        ]);

        // 3. Prior guest pooja booking
        $pooja = PoojaBooking::create([
            'booking_number' => 'SKP-PB-GUEST-01',
            'user_id' => null,
            'devotee_name' => 'Devotee Family',
            'phone' => '+91 94150 99999',
            'email' => $guestEmail,
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'time_slot' => 'सायं 06:00 PM',
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'amount' => 1100,
        ]);

        // Now devotee signs up / logs in with this exact email
        $user = User::factory()->create([
            'name' => 'Devotee Family',
            'email' => $guestEmail,
            'role' => 'donor',
        ]);

        // Access dashboard
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('SKP-REC-GUEST-01');
        $response->assertSee('SKP-PB-GUEST-01');
        $response->assertSee('VOL-'.sprintf('%04d', $volunteer->id));

        // Verify records in DB have been automatically linked to $user->id
        $this->assertEquals($user->id, $donation->fresh()->user_id);
        $this->assertEquals($user->id, $volunteer->fresh()->user_id);
        $this->assertEquals($user->id, $pooja->fresh()->user_id);
    }
}
