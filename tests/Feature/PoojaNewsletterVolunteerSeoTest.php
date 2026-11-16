<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\PoojaTimeSlot;
use App\Models\User;
use App\Services\ShortcodeParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PoojaNewsletterVolunteerSeoTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        HomepageSetting::clearCache();
        $this->seed();

        $this->adminUser = User::where('role', 'super_admin')->first() ?? User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        HomepageSetting::clearCache();
        parent::tearDown();
    }

    public function test_shortcode_parser_supports_images_galleries_and_videos(): void
    {
        $content = "Introductory text\n\n"
            .'[image src="/uploads/kamadgiri.webp" caption="पवित्र कामदगिरि पर्वत" width="70%" align="center"]'."\n\n"
            .'[gallery images="/uploads/g1.webp, /uploads/g2.webp, /uploads/g3.webp" columns="3" caption="चित्रकूट तीर्थ दर्शन"]'."\n\n"
            .'[gallery images="/uploads/gal1.webp, /uploads/gal2.webp" cols="2" caption=""]'."\n\n"
            .'[gallery images="/uploads/single1.webp, /uploads/single2.webp" cols="2"]'."\n\n"
            .'[video src="https://www.youtube.com/watch?v=dQw4w9WgXcQ" caption="संध्या महाआरती दर्शन"]'."\n\n"
            .'[quote speaker="स्वामी जी" designation="पीठाधीश्वर"]तुलसीदास चंदन घिसें तिलक देत रघुबीर[/quote]';

        $rendered = ShortcodeParser::parse($content);

        $this->assertStringContainsString('article-figure', $rendered);
        $this->assertStringContainsString('पवित्र कामदगिरि पर्वत', $rendered);
        $this->assertStringContainsString('fb-mosaic-wrapper', $rendered);
        $this->assertStringContainsString('fb-mosaic-container count-3', $rendered);
        $this->assertStringContainsString('item-1-of-3', $rendered);
        $this->assertStringContainsString('item-2-of-3', $rendered);
        $this->assertStringContainsString('item-3-of-3', $rendered);
        $this->assertStringContainsString('g3.webp', $rendered);
        $this->assertStringContainsString('gal1.webp', $rendered);
        $this->assertStringContainsString('single1.webp', $rendered);
        $this->assertStringContainsString('article-video-wrap', $rendered);
        $this->assertStringContainsString('dQw4w9WgXcQ', $rendered);
        $this->assertStringContainsString('editorial-pull-quote', $rendered);
        $this->assertStringContainsString('स्वामी जी', $rendered);
    }

    public function test_mosaic_gallery_five_images_overlay_and_hero_navbar_links(): void
    {
        // 1. Verify 6 images renders +2 overlay badge
        $sixImages = '[gallery images="/u/1.jpg,/u/2.jpg,/u/3.jpg,/u/4.jpg,/u/5.jpg,/u/6.jpg" caption="Holy Gallery"]';
        $rendered = ShortcodeParser::parse($sixImages);
        $this->assertStringContainsString('fb-mosaic-container count-5', $rendered);
        $this->assertStringContainsString('fb-mosaic-overlay', $rendered);
        $this->assertStringContainsString('+2', $rendered);

        // 2. Verify Home page links Pooja button directly to /pooja/book
        $home = $this->get('/');
        $home->assertStatus(200);
        $home->assertSee('/pooja/book');
        $home->assertDontSee('नित्य प्रातः कामदगिरि व मंदाकिनी मंगल दर्शन सीधे अपने फोन पर');
        $home->assertDontSee('पवित्र प्रसाद आपके द्वार');

        // 3. Verify public booking form does not show prasad delivery address
        $booking = $this->get('/pooja/book');
        $booking->assertStatus(200);
        $booking->assertDontSee('Prasad Postal Delivery Address');
    }

    public function test_pooja_index_and_detail_pages_render_successfully(): void
    {
        // 1. When packages are disabled, /pooja redirects to /pooja/book
        $redirectResponse = $this->get('/pooja');
        $redirectResponse->assertRedirect(route('pooja.bookingForm'));

        // 2. When enabled, /pooja and /pooja/{slug} render successfully
        OrganizationSetting::firstOrCreate([])->update(['is_pooja_packages_enabled' => true]);

        $response = $this->get('/pooja');
        $response->assertStatus(200);
        $response->assertSee('ऑनलाइन पूजा, अर्चना एवं पावन संकल्प सेवा');

        $pooja = PoojaService::first();
        $this->assertNotNull($pooja);

        $detailResponse = $this->get('/pooja/'.$pooja->slug);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($pooja->title_hi);
        $detailResponse->assertSee('यजमान / भक्त का नाम');
        $detailResponse->assertSee('गोत्र (Gotra)');
    }

    public function test_devotee_can_book_pooja_service_and_view_voucher(): void
    {
        $pooja = PoojaService::first();
        $this->assertNotNull($pooja);

        $payload = [
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'आलोक कुमार शर्मा',
            'gotra' => 'भारद्वाज',
            'nakshatra' => 'रोहिणी',
            'booking_date' => now()->addDays(5)->format('Y-m-d'),
            'time_slot' => 'प्रातःकाल (06:00 AM – 08:00 AM)',
            'phone' => '+91 9876543210',
            'email' => 'alok.sharma@example.com',
            'prasad_address' => '124, जानकी कुण्ड मार्ग, चित्रकूट धाम, उत्तर प्रदेश 210204',
            'sankalp_purpose' => 'परिवार के स्वास्थ्य एवं सुख-शांति हेतु विशेष संकल्प।',
        ];

        $response = $this->post('/pooja/book', $payload);

        $booking = PoojaBooking::where('email', 'alok.sharma@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('आलोक कुमार शर्मा', $booking->devotee_name);
        $this->assertEquals('pending', $booking->status);
        $this->assertEquals(0, (float) $booking->amount);

        $response->assertRedirect('/pooja/confirmation/'.$booking->booking_number);

        $voucherResponse = $this->get('/pooja/confirmation/'.$booking->booking_number);
        $voucherResponse->assertStatus(200);
        $voucherResponse->assertSee($booking->booking_number);
        $voucherResponse->assertSee('भारद्वाज');
        $voucherResponse->assertSee('आलोक कुमार शर्मा');
    }

    public function test_devotee_can_subscribe_to_newsletter(): void
    {
        $response = $this->post('/newsletter/subscribe', [
            'email' => 'rambhakt@example.com',
            'phone' => '+91 9415000000',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'rambhakt@example.com',
            'phone' => '+91 9415000000',
            'is_active' => true,
        ]);

        // Duplicate subscription should be handled gracefully
        $dupResponse = $this->post('/newsletter/subscribe', [
            'email' => 'rambhakt@example.com',
        ]);
        $dupResponse->assertSessionHas('success');
    }

    public function test_volunteer_application_flow_and_admin_toggle(): void
    {
        // 1. Portal is enabled by default
        $response = $this->get('/volunteer');
        $response->assertStatus(200);
        $response->assertSee('सेवादार / स्वयंसेवक पंजीकरण');

        // 2. Submit volunteer application
        $applyResponse = $this->post('/volunteer/apply', [
            'name' => 'राघवेंद्र प्रताप सिंह',
            'email' => 'raghavendra@example.com',
            'phone' => '+91 9898989898',
            'city' => 'लखनऊ',
            'state' => 'उत्तर प्रदेश',
            'seva_interests' => ['गुरुकुल शिक्षण व बाल संस्कार'],
            'message' => 'पिछले 3 वर्षों से संस्कृत शिक्षण एवं अन्न क्षेत्र सेवा में कार्यरत।',
        ]);

        $applyResponse->assertSessionHas('success');
        $this->assertDatabaseHas('volunteer_applications', [
            'email' => 'raghavendra@example.com',
            'name' => 'राघवेंद्र प्रताप सिंह',
            'status' => 'pending',
        ]);

        // 3. Admin toggles volunteer registration OFF
        $toggleResponse = $this->actingAs($this->adminUser)->post('/admin/volunteers/toggle');
        $toggleResponse->assertRedirect();

        // 4. Verification that registration is paused on public view
        $closedResponse = $this->get('/volunteer');
        $closedResponse->assertStatus(200);
        $closedResponse->assertSee('वर्तमान में नए पंजीकरण स्थगित हैं');
    }

    public function test_admin_can_update_page_seo_and_view_renders_on_frontend(): void
    {
        $adminView = $this->actingAs($this->adminUser)->get('/admin/seo-settings');
        $adminView->assertStatus(200);
        $adminView->assertSee('All Pages SEO & Meta Manager');

        // Update Home page SEO metadata
        $updateResponse = $this->actingAs($this->adminUser)->post('/admin/seo-settings', [
            'pages' => [
                'home' => [
                    'page_title' => 'Home Page',
                    'meta_title' => 'परम पावन चित्रकूट धाम | श्री कामदगिरि पीठम् सेवा ट्रस्ट विशेष',
                    'meta_description' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट चित्रकूट धाम - 5 किमी कामदगिरि परिक्रमा, ऑनलाइन महाआरती संकल्प, संस्कृत गुरुकुल एवं गौशाला सेवा।',
                    'keywords' => 'chitrakoot, kamadgiri, parikrama, aarti, gurukul',
                ],
            ],
        ]);

        $updateResponse->assertSessionHas('success');

        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'home',
            'meta_title' => 'परम पावन चित्रकूट धाम | श्री कामदगिरि पीठम् सेवा ट्रस्ट विशेष',
        ]);

        // Public page reflects new meta tags
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('परम पावन चित्रकूट धाम | श्री कामदगिरि पीठम् सेवा ट्रस्ट विशेष');
        $homeResponse->assertSee('श्री कामदगिरि पीठम् सेवा ट्रस्ट चित्रकूट धाम - 5 किमी कामदगिरि परिक्रमा');
    }

    public function test_homepage_minute_content_controls_in_cms(): void
    {
        $firstPooja = PoojaService::first();
        $this->assertNotNull($firstPooja);

        $payload = [
            'hero_title' => 'चित्राकूट धाम पावन दर्शन',
            'selected_pooja_ids' => [$firstPooja->id],
            'selected_destination_ids' => [1],
            'selected_seva_ids' => [1],
            'selected_cause_ids' => [1],
        ];

        $postResponse = $this->actingAs($this->adminUser)->post('/admin/homepage-layout', $payload);
        $postResponse->assertSessionHas('success');

        // Check database settings
        $settings = HomepageSetting::getAll();
        $this->assertArrayHasKey('selected_pooja_ids', $settings);

        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('॥ पावन पूजा एवं हवन संकल्प ॥');
    }

    public function test_dedicated_pooja_booking_form_renders_packages_and_slots(): void
    {
        $response = $this->get('/pooja/book');
        $response->assertStatus(200);
        $response->assertSee('पूजा एवं हवन ऑनलाइन संकल्प आरक्षण');
        $response->assertSee('वैदिक पूजा');
        $response->assertSee('हवन व महायज्ञ');
    }

    public function test_booking_fails_when_date_is_within_24_hours(): void
    {
        $pooja = PoojaService::first();
        $this->assertNotNull($pooja);

        // Attempting to book today (strictly less than 24h)
        $payload = [
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'रमेश चंद्र',
            'gotra' => 'कश्यप',
            'booking_date' => now()->format('Y-m-d'),
            'time_slot' => 'प्रातःकाल (06:00 AM – 08:00 AM)',
            'phone' => '9876543210',
            'email' => 'ramesh@example.com',
        ];

        $response = $this->post('/pooja/book', $payload);
        $response->assertSessionHasErrors('booking_date');
    }

    public function test_booking_succeeds_when_date_is_24_hours_or_more_ahead(): void
    {
        $pooja = PoojaService::first();
        $this->assertNotNull($pooja);

        $payload = [
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'दीपक वर्मा',
            'gotra' => 'वशिष्ठ',
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'time_slot' => 'प्रातःकाल (08:30 AM – 10:30 AM)',
            'phone' => '9876543210',
            'email' => 'deepak@example.com',
            'sankalp_purpose' => 'पारिवारिक शांति एवं उन्नति',
        ];

        $response = $this->post('/pooja/book', $payload);
        $response->assertSessionDoesntHaveErrors();

        $booking = PoojaBooking::where('email', 'deepak@example.com')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('pending', $booking->status);
        $this->assertEquals('9876543210', $booking->phone);
    }

    public function test_donation_validation_requires_phone_number(): void
    {
        $payload = [
            'donation_type' => 'cause_specific',
            'amount' => 501,
            'donor_name' => 'सुनील गुप्ता',
            'donor_email' => 'sunil@example.com',
            // donor_phone omitted
        ];

        $response = $this->postJson('/api/donations/create-order', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('donor_phone');
    }

    public function test_admin_can_manage_slots_and_confirm_booking(): void
    {
        // 1. Admin adds a slot
        $slotPayload = [
            'slot_name' => 'संध्या विशेष हवन सत्र',
            'start_time' => '17:30',
            'end_time' => '19:00',
            'service_type' => 'hawan',
            'max_capacity' => 5,
            'is_active' => '1',
        ];

        $slotResponse = $this->actingAs($this->adminUser)->post('/admin/pooja-slots', $slotPayload);
        $slotResponse->assertSessionHas('success');

        $slot = PoojaTimeSlot::where('slot_name', 'संध्या विशेष हवन सत्र')->first();
        $this->assertNotNull($slot);

        // 2. Devotee books a pooja
        $pooja = PoojaService::first();
        $booking = PoojaBooking::create([
            'booking_number' => 'PB-TEST-'.rand(1000, 9999),
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'राजेश त्रिपाठी',
            'phone' => '9876543210',
            'email' => 'rajesh@example.com',
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'time_slot' => $slot->slot_name.' ('.$slot->formatted_range.')',
            'amount' => $pooja->price_inr,
            'status' => 'pending',
        ]);

        // 3. Admin confirms the booking
        $confirmResponse = $this->actingAs($this->adminUser)->post("/admin/pooja-bookings/{$booking->id}/confirm");
        $confirmResponse->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);
    }

    public function test_admin_sends_quotation_with_shortened_expiry_max_12_hours(): void
    {
        $pooja = PoojaService::first();
        $booking = PoojaBooking::create([
            'booking_number' => 'PB-QUOTE-'.rand(1000, 9999),
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'आलोक शर्मा',
            'phone' => '9876543210',
            'email' => 'alok@example.com',
            'booking_date' => now()->addDays(2)->format('Y-m-d'),
            'time_slot' => 'प्रातः 07:00',
            'amount' => 0,
            'status' => 'pending',
        ]);

        // 1. Sending with invalid expiry (> 12 hours) must be rejected
        $invalidResponse = $this->actingAs($this->adminUser)->post("/admin/pooja-bookings/{$booking->id}/quotation", [
            'quotation_amount' => 2100,
            'quotation_hours' => 24, // Exceeds max 12 hours limit
        ]);
        $invalidResponse->assertSessionHasErrors('quotation_hours');

        // 2. Sending with default (6 hours) must succeed and cap within 12 hours
        $validResponse = $this->actingAs($this->adminUser)->post("/admin/pooja-bookings/{$booking->id}/quotation", [
            'quotation_amount' => 2100,
            'quotation_hours' => 6,
            'quotation_notes' => 'सम्पूर्ण फल-फूल एवं हवन समिधा व्यवस्था',
        ]);
        $validResponse->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('quoted', $booking->status);
        $this->assertEquals(2100, (float) $booking->quotation_amount);
        $this->assertNotNull($booking->quotation_token);
        $this->assertNotNull($booking->quotation_expires_at);
        $this->assertTrue($booking->quotation_expires_at->diffInHours(now()) <= 12);
        $this->assertTrue($booking->isQuotationActive());
    }

    public function test_pooja_quotation_create_order_and_payment_gateway_verification(): void
    {
        $pooja = PoojaService::first();
        $booking = PoojaBooking::create([
            'booking_number' => 'PB-PAY-'.rand(1000, 9999),
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'संजय अग्रवाल',
            'phone' => '9811122233',
            'email' => 'sanjay@example.com',
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'time_slot' => 'मध्याह्न 12:00',
            'amount' => 3100,
            'quotation_amount' => 3100,
            'quotation_token' => 'test-valid-quotation-token-12345',
            'quotation_expires_at' => now()->addHours(6),
            'status' => 'quoted',
            'payment_status' => 'pending',
        ]);

        // 1. Visit quotation payment screen
        $screenResponse = $this->get("/pooja/quotation/{$booking->quotation_token}");
        $screenResponse->assertStatus(200);
        $screenResponse->assertSee('पूजा व अनुष्ठान दक्षिणा कोटेशन');
        $screenResponse->assertSee('3,100.00');
        $screenResponse->assertSee('payDakshinaBtn');
        $screenResponse->assertSee('countdownClock');

        // 2. Call order generation API endpoint
        $orderResponse = $this->postJson("/pooja/quotation/{$booking->quotation_token}/create-order");
        $orderResponse->assertStatus(200);
        $orderResponse->assertJsonStructure([
            'success',
            'order_id',
            'amount',
            'key',
            'currency',
            'devotee_name',
            'booking_number',
        ]);
        $this->assertEquals(310000, $orderResponse->json('amount'));

        // 3. Verify payment via Gateway callback
        $verifyResponse = $this->postJson("/pooja/quotation/{$booking->quotation_token}/verify", [
            'razorpay_payment_id' => 'pay_test_quotation_999',
            'razorpay_order_id' => $orderResponse->json('order_id'),
            'razorpay_signature' => 'mock_verified_signature',
        ]);
        $verifyResponse->assertStatus(200);
        $verifyResponse->assertJson(['success' => true]);

        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals('confirmed', $booking->status);
        $this->assertEquals('pay_test_quotation_999', $booking->payment_id);
    }

    public function test_pooja_quotation_rejects_expired_payment_links(): void
    {
        $pooja = PoojaService::first();
        $expiredBooking = PoojaBooking::create([
            'booking_number' => 'PB-EXP-'.rand(1000, 9999),
            'pooja_service_id' => $pooja->id,
            'devotee_name' => 'मनीष कुमार',
            'phone' => '9811122244',
            'email' => 'manish@example.com',
            'booking_date' => now()->addDays(3)->format('Y-m-d'),
            'time_slot' => 'सायं 06:00',
            'amount' => 1500,
            'quotation_amount' => 1500,
            'quotation_token' => 'test-expired-token-999',
            'quotation_expires_at' => now()->subMinutes(10), // Expired 10 mins ago
            'status' => 'quoted',
            'payment_status' => 'pending',
        ]);

        $this->assertTrue($expiredBooking->isQuotationExpired());

        // 1. Payment screen displays expiration notice
        $screen = $this->get("/pooja/quotation/{$expiredBooking->quotation_token}");
        $screen->assertStatus(200);
        $screen->assertSee('यह कोटेशन लिंक समाप्त (Expired) हो चुका है');

        // 2. Create order API rejects expired link
        $orderRes = $this->postJson("/pooja/quotation/{$expiredBooking->quotation_token}/create-order");
        $orderRes->assertStatus(422);
        $orderRes->assertJson(['success' => false]);

        // 3. Verify API rejects expired link
        $verifyRes = $this->postJson("/pooja/quotation/{$expiredBooking->quotation_token}/verify", [
            'razorpay_payment_id' => 'pay_invalid_expired',
        ]);
        $verifyRes->assertStatus(422);
        $verifyRes->assertJson(['success' => false]);
    }
}
