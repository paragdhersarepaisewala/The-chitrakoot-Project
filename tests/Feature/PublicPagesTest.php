<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Cause;
use App\Models\Donation;

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
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'order_id', 'donation_id', 'amount']);
    }

    public function test_donation_verify_payment_and_receipt_generation()
    {
        $orderRes = $this->postJson('/api/donations/create-order', [
            'donation_type' => 'cause_specific',
            'amount' => 2100,
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
        $receiptPage = $this->get('/receipt/' . $receiptNumber);
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
}
