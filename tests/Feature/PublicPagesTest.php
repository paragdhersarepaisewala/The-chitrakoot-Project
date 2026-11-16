<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Shri Kamadgiri Peetham');
        $response->assertSee('गौ सेवा');
        $response->assertSee('id="events"', false);
        $response->assertSee('आगामी कार्यक्रम एवं उत्सव');
    }

    public function test_homepage_events_section_renders_featured_image_or_logo_fallback()
    {
        // 1. Without featured image -> Renders site logo fallback
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="events"', false);
        $response->assertSee('kamadgiri-temple-crest-emblem-2-1792223701.webp');

        // 2. With featured image -> Renders custom event featured image
        $firstEvent = Event::where('is_published', true)->where('is_archived', false)->first();
        $firstEvent->update(['featured_image' => '/uploads/custom-event-photo-2027.webp']);

        $responseWithImage = $this->get('/');
        $responseWithImage->assertStatus(200);
        $responseWithImage->assertSee('/uploads/custom-event-photo-2027.webp');
    }

    public function test_about_page_loads_successfully()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('विज़न');
    }

    public function test_donate_page_loads_successfully()
    {
        $response = $this->get('/donate');
        $response->assertStatus(200);
        $response->assertSee('अपनी सेवा का चयन करें और दान करें');
    }

    public function test_donation_create_order_api()
    {
        $response = $this->postJson('/api/donations/create-order', [
            'donation_type' => 'cause_specific',
            'amount' => 1100,
            'donor_phone' => '9876543210',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'order_id', 'donation_id', 'amount']);
    }

    public function test_donation_verify_payment_and_receipt_generation()
    {
        $orderRes = $this->postJson('/api/donations/create-order', [
            'donation_type' => 'cause_specific',
            'amount' => 2100,
            'donor_phone' => '9876543210',
        ]);

        $donationId = $orderRes->json('donation_id');

        $verifyRes = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donationId,
            'razorpay_payment_id' => 'pay_test_unit_123',
            'donor_name' => 'Unit Tester',
        ]);

        $verifyRes->assertStatus(200);
        $verifyRes->assertJsonStructure(['success', 'receipt_number', 'receipt_url']);

        $receiptNumber = $verifyRes->json('receipt_number');
        $receiptPage = $this->get('/receipt/'.$receiptNumber);
        $receiptPage->assertStatus(200);
        $receiptPage->assertSee('Unit Tester');
        $receiptPage->assertSee('2,100');
    }

    public function test_custom_404_page_loads_with_correct_actions()
    {
        $response = $this->get('/non-existent-page-url');
        $response->assertStatus(404);
        $response->assertSee('Return Home');
        $response->assertSee('Explore Chitrakoot');
    }

    public function test_blog_index_page_loads()
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('चित्रकूट माहात्म्य');
    }

    public function test_gallery_page_loads()
    {
        $response = $this->get('/gallery');
        $response->assertStatus(200);
        $response->assertSee('चित्र दीर्घा');
    }

    public function test_sitemap_xml_returns_valid_xml()
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('urlset');
        $response->assertSee(url('/about'));
    }

    public function test_privacy_policy_page_loads_with_ssr_content()
    {
        $response = $this->get('/privacy-policy');
        $response->assertStatus(200);
        $response->assertSee('गोपनीयता नीति');
        $response->assertSee('Razorpay');
    }

    public function test_robots_txt_references_sitemap()
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));
        $this->assertStringContainsString('Sitemap:', $robotsContent);
        $this->assertStringContainsString('Disallow: /admin/', $robotsContent);
    }

    public function test_about_page_features_clean_chitrakoot_environmental_campaign()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertSee('पर्यावरण बचाओ अभियान');
        $response->assertSee('मंदाकिनी नदी स्वच्छता');
        $response->assertSee('प्लास्टिक-मुक्त कामदगिरि परिक्रमा');
        $response->assertSee('१०,०००+');

        // Strict single <h1> requirement check
        $content = $response->getContent();
        $this->assertEquals(1, substr_count($content, '<h1'), 'About page must contain exactly one <h1> heading.');
    }

    public function test_homepage_pooja_hawan_section_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('॥ पावन पूजा एवं हवन संकल्प ॥');
        $response->assertSee('घर बैठे चित्रकूट धाम में वैदिक पूजा व हवन का पावन संकल्प लें');
        $response->assertSee('/pooja/book');
    }
}
