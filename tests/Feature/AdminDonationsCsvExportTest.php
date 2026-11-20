<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDonationsCsvExportTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('role', 'super_admin')->first() ?? User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_export_donations_csv()
    {
        $response = $this->get('/admin/donations/export-csv');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_export_donations_csv_successfully()
    {
        Donation::create([
            'user_id' => $this->adminUser->id,
            'donor_name' => 'Govind Das',
            'donor_email' => 'govind@example.com',
            'donor_phone' => '9876543210',
            'amount' => 5100.00,
            'currency' => 'INR',
            'status' => 'verified',
            'receipt_number' => 'KPST-TEST-80G-001',
            'donation_type' => 'Gaushala Seva',
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($this->adminUser)->get('/admin/donations/export-csv');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('attachment;', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('donations_80g_export_', $response->headers->get('Content-Disposition'));

        // Streamed content assertions
        $content = $response->streamedContent();
        $this->assertStringContainsString('Receipt Number', $content);
        $this->assertStringContainsString('Donor Name', $content);
        $this->assertStringContainsString('KPST-TEST-80G-001', $content);
        $this->assertStringContainsString('Govind Das', $content);
        $this->assertStringContainsString('5100.00', $content);
    }
}
