<?php

namespace Tests\Feature;

use App\Mail\TempleNotificationMailable;
use App\Models\AtithiVishramEnquiry;
use App\Models\HomepageSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\TempleBookingEnquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ServicesUpcomingAndAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected User $devoteeUser;

    protected function setUp(): void
    {
        parent::setUp();
        HomepageSetting::clearCache();
        $this->seed();

        $this->adminUser = User::where('role', 'super_admin')->first() ?? User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $this->devoteeUser = User::factory()->create([
            'name' => 'Shri Devotee',
            'email' => 'devotee@kamadgiripeetham.org',
            'role' => 'donor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    protected function tearDown(): void
    {
        HomepageSetting::clearCache();
        parent::tearDown();
    }

    public function test_services_hub_page_renders_successfully(): void
    {
        $response = $this->get('/services');

        $response->assertStatus(200);
        $response->assertSee('समस्त मंदिर सेवाएं एवं तीर्थ सुविधा केंद्र');
        $response->assertSee('वैदिक पूजा, हवन एवं संकल्प');
        $response->assertSee('नित्य आरती व दर्शन सारणी');
        $response->assertSee('अतिथि विश्राम गृह (प्रवास पूछताछ)');
        $response->assertSee('मंदिर परिसर व मंडप आरक्षण');
        $response->assertSee(route('pooja.bookingForm'));
        $response->assertSee(route('services.atithiVishram'));
        $response->assertSee(route('services.templeBooking'));
    }

    public function test_aarti_timetable_page_renders_successfully(): void
    {
        $response = $this->get('/services/aarti');

        $response->assertStatus(200);
        $response->assertSee('नित्य महाआरती एवं गर्भगृह दर्शन समय सारणी');
        $response->assertSee('दैनिक नियमित आरती चक्र');
        $response->assertSee('मंगला आरती');
        $response->assertSee('अमावस्या एवं महापर्व विशेष समय चक्र');
    }

    public function test_atithi_vishram_page_renders_form(): void
    {
        $response = $this->get('/services/atithi-vishram');

        $response->assertStatus(200);
        $response->assertSee('अतिथि विश्राम गृह (प्रवास पूछताछ)');
        $response->assertSee('तीर्थ प्रवास पूछताछ प्रपत्र');
        $response->assertSee('आगमन तिथि');
        $response->assertSee('प्रस्थान तिथि');
        $response->assertSee('कक्ष प्राथमिकता');
    }

    public function test_atithi_vishram_enquiry_submission_creates_record_and_dispatches_emails(): void
    {
        Mail::fake();

        $formData = [
            'devotee_name' => 'राघवेंद्र शर्मा',
            'phone' => '9876543210',
            'email' => 'raghavendra@example.com',
            'city' => 'अयोध्या',
            'state' => 'उत्तर प्रदेश',
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'check_out_date' => now()->addDays(4)->format('Y-m-d'),
            'number_of_devotees' => 4,
            'number_of_rooms' => 2,
            'room_preference' => 'family_suite',
            'purpose_of_stay' => 'darshan_parikrama',
            'id_proof_type' => 'aadhaar',
            'special_requests' => 'बुजुर्ग माता-पिता हेतु भूतल पर कक्ष व्यवस्था कृपा करें।',
        ];

        $response = $this->post('/services/atithi-vishram', $formData);

        $response->assertRedirect('/services/atithi-vishram');
        $response->assertSessionHas('success');
        $response->assertSessionHas('enquiry_number');

        $this->assertDatabaseHas('atithi_vishram_enquiries', [
            'devotee_name' => 'राघवेंद्र शर्मा',
            'phone' => '9876543210',
            'email' => 'raghavendra@example.com',
            'number_of_devotees' => 4,
            'number_of_rooms' => 2,
            'room_preference' => 'family_suite',
            'status' => 'new',
        ]);

        Mail::assertSent(TempleNotificationMailable::class, function ($mail) {
            return $mail->hasTo('raghavendra@example.com');
        });
    }

    public function test_atithi_vishram_enquiry_validation_rules(): void
    {
        $response = $this->post('/services/atithi-vishram', [
            'devotee_name' => '',
            'phone' => '',
            'email' => 'invalid-email',
            'check_in_date' => now()->subDays(2)->format('Y-m-d'), // past date
            'check_out_date' => now()->subDays(4)->format('Y-m-d'),
            'number_of_devotees' => 0,
            'room_preference' => 'invalid_room_type',
        ]);

        $response->assertSessionHasErrors([
            'devotee_name',
            'phone',
            'email',
            'check_in_date',
            'check_out_date',
            'number_of_devotees',
            'room_preference',
        ]);
    }

    public function test_temple_booking_page_renders_form(): void
    {
        $response = $this->get('/services/temple-booking');

        $response->assertStatus(200);
        $response->assertSee('मंदिर परिसर एवं सभा मंडप आरक्षण पूछताछ');
        $response->assertSee('केवल पूर्व-पूछताछ (Booking Enquiry)');
        $response->assertSee('आयोजन का प्रकार');
        $response->assertSee('प्रस्तावित तिथि');
    }

    public function test_temple_booking_enquiry_submission_creates_record_and_dispatches_emails(): void
    {
        Mail::fake();

        $formData = [
            'devotee_name' => 'दिनेश चंद्र वाजपेयी',
            'phone' => '9450000000',
            'email' => 'dinesh@example.com',
            'city' => 'कानपुर',
            'state' => 'उत्तर प्रदेश',
            'event_type' => 'shadi',
            'event_title' => 'आयुष्मान चिरंजीव विवाह संस्कार',
            'proposed_date' => now()->addMonths(2)->format('Y-m-d'),
            'alternate_date' => now()->addMonths(2)->addDays(7)->format('Y-m-d'),
            'duration_days' => 2,
            'expected_gathering' => 250,
            'requires_priest' => '1',
            'requires_catering' => '1',
            'requires_accommodation' => '1',
            'additional_requirements' => 'वैदिक मंडप सजावट एवं 200 लोगों हेतु पंगत महाप्रसाद।',
        ];

        $response = $this->post('/services/temple-booking', $formData);

        $response->assertRedirect('/services/temple-booking');
        $response->assertSessionHas('success');
        $response->assertSessionHas('enquiry_number');

        $this->assertDatabaseHas('temple_booking_enquiries', [
            'devotee_name' => 'दिनेश चंद्र वाजपेयी',
            'phone' => '9450000000',
            'email' => 'dinesh@example.com',
            'event_type' => 'shadi',
            'duration_days' => 2,
            'expected_gathering' => 250,
            'requires_priest' => true,
            'requires_catering' => true,
            'requires_accommodation' => true,
            'status' => 'new',
        ]);

        Mail::assertSent(TempleNotificationMailable::class, function ($mail) {
            return $mail->hasTo('dinesh@example.com');
        });
    }

    public function test_temple_booking_enquiry_validation_rules(): void
    {
        $response = $this->post('/services/temple-booking', [
            'devotee_name' => '',
            'phone' => '',
            'email' => 'bad-email',
            'event_type' => 'unsupported_event',
            'proposed_date' => now()->subDays(5)->format('Y-m-d'),
            'duration_days' => 0,
            'expected_gathering' => 2, // minimum is 5
        ]);

        $response->assertSessionHasErrors([
            'devotee_name',
            'phone',
            'email',
            'event_type',
            'proposed_date',
            'duration_days',
            'expected_gathering',
        ]);
    }

    public function test_admin_can_view_and_filter_atithi_vishram_enquiries(): void
    {
        $enq = AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-TEST-0001',
            'devotee_name' => 'गोपाल दास',
            'phone' => '9999999999',
            'email' => 'gopal@example.com',
            'city' => 'मथुरा',
            'check_in_date' => now()->addDays(3),
            'check_out_date' => now()->addDays(5),
            'number_of_devotees' => 2,
            'number_of_rooms' => 1,
            'room_preference' => 'standard',
            'purpose_of_stay' => 'darshan_parikrama',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/atithi-vishram');

        $response->assertStatus(200);
        $response->assertSee('Atithi Vishram (Guest Stay) Enquiries');
        $response->assertSee('#AVE-TEST-0001');
        $response->assertSee('गोपाल दास');

        // Test search filter
        $searchResponse = $this->actingAs($this->adminUser)->get('/admin/atithi-vishram?q=मथुरा');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('#AVE-TEST-0001');
    }

    public function test_admin_can_update_atithi_vishram_status_and_dispatches_status_email(): void
    {
        Mail::fake();

        $enq = AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-TEST-0002',
            'devotee_name' => 'सुमित्रा देवी',
            'phone' => '8888888888',
            'email' => 'sumitra@example.com',
            'check_in_date' => now()->addDays(5),
            'check_out_date' => now()->addDays(7),
            'number_of_devotees' => 3,
            'number_of_rooms' => 1,
            'room_preference' => 'ac_room',
            'purpose_of_stay' => 'darshan_parikrama',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/atithi-vishram/'.$enq->id.'/status', [
            'status' => 'confirmed',
            'admin_notes' => 'कक्ष क्रमांक १०२ आरक्षित किया गया है। चेक-इन पर पहचान पत्र दिखाएं।',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('atithi_vishram_enquiries', [
            'id' => $enq->id,
            'status' => 'confirmed',
            'is_read' => true,
        ]);

        Mail::assertSent(TempleNotificationMailable::class, function ($mail) {
            return $mail->hasTo('sumitra@example.com');
        });
    }

    public function test_admin_can_delete_atithi_vishram_enquiry(): void
    {
        $enq = AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-DEL-0001',
            'devotee_name' => 'परीक्षण भक्त',
            'phone' => '7777777777',
            'email' => 'testdelete@example.com',
            'check_in_date' => now()->addDays(5),
            'check_out_date' => now()->addDays(6),
            'number_of_devotees' => 1,
            'number_of_rooms' => 1,
            'room_preference' => 'standard',
            'purpose_of_stay' => 'darshan_parikrama',
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->delete('/admin/atithi-vishram/'.$enq->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('atithi_vishram_enquiries', ['id' => $enq->id]);
    }

    public function test_admin_can_view_and_filter_temple_booking_enquiries(): void
    {
        $booking = TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-TEST-0001',
            'devotee_name' => 'आचार्य विद्याधर',
            'phone' => '9988776655',
            'email' => 'acharya@example.com',
            'city' => 'उज्जैन',
            'event_type' => 'katha',
            'event_title' => 'श्रीमद्भागवत कथा अमृत महोत्सव',
            'proposed_date' => now()->addMonths(1),
            'duration_days' => 7,
            'expected_gathering' => 500,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/temple-bookings');

        $response->assertStatus(200);
        $response->assertSee('Temple Hall & Premises Booking Enquiries');
        $response->assertSee('#TBE-TEST-0001');
        $response->assertSee('आचार्य विद्याधर');

        // Test event_type filter
        $typeFilterResponse = $this->actingAs($this->adminUser)->get('/admin/temple-bookings?event_type=katha');
        $typeFilterResponse->assertStatus(200);
        $typeFilterResponse->assertSee('#TBE-TEST-0001');
    }

    public function test_admin_can_update_temple_booking_status_and_dispatches_status_email(): void
    {
        Mail::fake();

        $booking = TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-TEST-0002',
            'devotee_name' => 'श्रीकांत द्विवेदी',
            'phone' => '9811223344',
            'email' => 'shrikant@example.com',
            'event_type' => 'hawan',
            'event_title' => 'शतचंडी महायज्ञ',
            'proposed_date' => now()->addMonths(2),
            'duration_days' => 3,
            'expected_gathering' => 150,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/temple-bookings/'.$booking->id.'/status', [
            'status' => 'approved',
            'admin_notes' => 'प्रस्तावित तिथि पर मुख्य यज्ञशाला उपलब्ध है। अग्रिम व्यवस्था हेतु कार्यालय पधारें।',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('temple_booking_enquiries', [
            'id' => $booking->id,
            'status' => 'approved',
            'is_read' => true,
        ]);

        Mail::assertSent(TempleNotificationMailable::class, function ($mail) {
            return $mail->hasTo('shrikant@example.com');
        });
    }

    public function test_admin_can_delete_temple_booking_enquiry(): void
    {
        $booking = TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-DEL-0001',
            'devotee_name' => 'डिलीट टेस्ट आयोजक',
            'phone' => '9000000000',
            'email' => 'delbooking@example.com',
            'event_type' => 'bhandara',
            'proposed_date' => now()->addMonths(1),
            'duration_days' => 1,
            'expected_gathering' => 200,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->delete('/admin/temple-bookings/'.$booking->id);

        $response->assertRedirect();
        $this->assertDatabaseMissing('temple_booking_enquiries', ['id' => $booking->id]);
    }

    public function test_admin_dashboard_displays_all_services_metrics_and_hub_panels(): void
    {
        // Create sample records for all services
        $service = PoojaService::first() ?? PoojaService::create([
            'title_hi' => 'कामदगिरि परिक्रमा संकल्प',
            'title_en' => 'Kamadgiri Parikrama Sankalp',
            'slug' => 'kamadgiri-parikrama-sankalp-test',
            'suggested_donation_amount' => 501,
            'duration_minutes' => 60,
            'is_active' => true,
        ]);

        PoojaBooking::create([
            'booking_number' => 'PB-DASH-001',
            'service_id' => $service->id,
            'devotee_name' => 'डैशबोर्ड पूजा भक्त',
            'phone' => '9111111111',
            'email' => 'dashpooja@example.com',
            'booking_date' => now()->addDays(3),
            'time_slot' => '07:00 AM',
            'amount' => 501,
            'status' => 'pending',
            'payment_status' => 'paid',
        ]);

        AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-DASH-001',
            'devotee_name' => 'डैशबोर्ड विश्राम भक्त',
            'phone' => '9222222222',
            'email' => 'dashatithi@example.com',
            'check_in_date' => now()->addDays(4),
            'check_out_date' => now()->addDays(6),
            'number_of_devotees' => 2,
            'number_of_rooms' => 1,
            'room_preference' => 'standard',
            'status' => 'new',
        ]);

        TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-DASH-001',
            'devotee_name' => 'डैशबोर्ड परिसर आयोजक',
            'phone' => '9333333333',
            'email' => 'dashtemple@example.com',
            'event_type' => 'shadi',
            'proposed_date' => now()->addMonths(2),
            'duration_days' => 2,
            'expected_gathering' => 300,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('All Temple Services, Bookings');
        $response->assertSee('Stay Enquiries Hub');
        $response->assertSee('Pooja Bookings');
        $response->assertSee('Atithi Vishram');
        $response->assertSee('Hall Bookings');
        $response->assertSee('#PB-DASH-001');
        $response->assertSee('#AVE-DASH-001');
        $response->assertSee('#TBE-DASH-001');
    }

    public function test_admin_notifications_center_polls_atithi_and_temple_enquiries(): void
    {
        AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-NOTIF-01',
            'devotee_name' => 'अधिसूचना विश्राम भक्त',
            'phone' => '9888888888',
            'email' => 'notifstay@example.com',
            'check_in_date' => now()->addDays(5),
            'check_out_date' => now()->addDays(7),
            'number_of_devotees' => 3,
            'number_of_rooms' => 1,
            'room_preference' => 'standard',
            'status' => 'new',
        ]);

        TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-NOTIF-01',
            'devotee_name' => 'अधिसूचना परिसर आयोजक',
            'phone' => '9777777777',
            'email' => 'notifevent@example.com',
            'event_type' => 'katha',
            'proposed_date' => now()->addMonths(1),
            'duration_days' => 5,
            'expected_gathering' => 400,
            'status' => 'new',
        ]);

        $response = $this->actingAs($this->adminUser)->getJson('/admin/api/notifications/poll');

        $response->assertStatus(200);
        $json = $response->json();

        $this->assertArrayHasKey('notifications', $json);
        $types = collect($json['notifications'])->pluck('type')->all();

        $this->assertContains('atithi_vishram', $types);
        $this->assertContains('temple_booking', $types);
    }

    public function test_user_dashboard_and_user_notifications_include_services_enquiries(): void
    {
        $ave = AtithiVishramEnquiry::create([
            'enquiry_number' => 'AVE-USER-01',
            'user_id' => $this->devoteeUser->id,
            'devotee_name' => $this->devoteeUser->name,
            'phone' => '9999900000',
            'email' => $this->devoteeUser->email,
            'check_in_date' => now()->addDays(6),
            'check_out_date' => now()->addDays(8),
            'number_of_devotees' => 2,
            'number_of_rooms' => 1,
            'room_preference' => 'ac_room',
            'status' => 'confirmed',
            'admin_notes' => 'पुष्ट प्रवास व्यवस्था।',
        ]);

        $tbe = TempleBookingEnquiry::create([
            'enquiry_number' => 'TBE-USER-01',
            'user_id' => $this->devoteeUser->id,
            'devotee_name' => $this->devoteeUser->name,
            'phone' => '9999900000',
            'email' => $this->devoteeUser->email,
            'event_type' => 'hawan',
            'proposed_date' => now()->addMonths(2),
            'duration_days' => 1,
            'expected_gathering' => 100,
            'status' => 'approved',
            'admin_notes' => 'तिथि स्वीकृत है।',
        ]);

        // 1. Devotee Dashboard renders the enquiries
        $response = $this->actingAs($this->devoteeUser)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('#AVE-USER-01');
        $response->assertSee('#TBE-USER-01');

        // 2. Devotee Notifications API returns status updates
        $notifResponse = $this->actingAs($this->devoteeUser)->getJson('/api/user/notifications/poll');
        $notifResponse->assertStatus(200);
        $notifJson = $notifResponse->json();

        $userNotifTypes = collect($notifJson['notifications'])->pluck('type')->all();
        $this->assertContains('atithi_status', $userNotifTypes);
        $this->assertContains('temple_status', $userNotifTypes);
    }
}
