<?php

namespace Tests\Feature;

use App\Models\Donation;
use App\Models\OrganizationSetting;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_non_admin_authenticated_user_cannot_access_admin_portal(): void
    {
        // A regular registered donor
        $donor = User::factory()->create([
            'role' => 'donor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($donor)->get('/admin');
        $response->assertStatus(403);

        $postAttempt = $this->actingAs($donor)->post('/admin/flush-cache');
        $postAttempt->assertStatus(403);
    }

    public function test_guest_user_cannot_access_admin_portal(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_user_can_access_admin_portal(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_homepage_layout_upload_rejects_executable_php_files(): void
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $maliciousPhpFile = UploadedFile::fake()->create('shell.php', 10, 'application/x-php');

        $response = $this->actingAs($admin)->post('/admin/homepage-layout', [
            'hero_video_file' => $maliciousPhpFile,
        ]);

        $response->assertSessionHasErrors(['hero_video_file']);
    }

    public function test_father_name_validation_does_not_throw_syntax_exception(): void
    {
        $user = User::factory()->create([
            'role' => 'donor',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post('/register/complete-profile', [
            'father_name' => 'Shri Ramswaroop Ji',
            'phone' => '+919876543210',
            'gender' => 'Male',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('supporter_profiles', [
            'user_id' => $user->id,
            'father_name' => 'Shri Ramswaroop Ji',
        ]);
    }

    public function test_submitted_comments_are_unapproved_by_default_and_hidden_from_public(): void
    {
        $post = Post::where('status', 'published')->first();

        $response = $this->post("/blog/{$post->id}/comment", [
            'name' => 'Anonymous Devotee',
            'comment' => 'This is a pending comment needing approval.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('post_comments', [
            'post_id' => $post->id,
            'name' => 'Anonymous Devotee',
            'is_approved' => false,
        ]);

        // Verify unapproved comment is not shown on public post view
        $publicView = $this->get("/blog/{$post->slug}");
        $publicView->assertStatus(200);
        $publicView->assertDontSee('This is a pending comment needing approval.');

        // Approve the comment in database
        PostComment::where('name', 'Anonymous Devotee')->update(['is_approved' => true]);

        // Verify now visible on public view
        $updatedView = $this->get("/blog/{$post->slug}");
        $updatedView->assertStatus(200);
        $updatedView->assertSee('This is a pending comment needing approval.');
    }

    public function test_blog_likes_prevent_duplicate_inflation_in_same_session(): void
    {
        $post = Post::where('status', 'published')->first();
        $initialLikes = $post->likes_count;

        // First like
        $res1 = $this->postJson("/blog/{$post->id}/like");
        $res1->assertStatus(200);
        $res1->assertJson(['success' => true, 'likes_count' => $initialLikes + 1]);

        // Second like in same session
        $res2 = $this->postJson("/blog/{$post->id}/like");
        $res2->assertStatus(200);
        $res2->assertJson(['success' => true, 'already_liked' => true, 'likes_count' => $initialLikes + 1]);

        $this->assertEquals($initialLikes + 1, $post->fresh()->likes_count);
    }

    public function test_donation_payment_verification_rejects_invalid_hmac_signature(): void
    {
        // Set secret key in settings
        $settings = OrganizationSetting::getSettings();
        $settings->update(['razorpay_key_secret' => 'test_secret_key_12345']);

        $donation = Donation::create([
            'donation_type' => 'cause_specific',
            'amount' => 500,
            'currency' => 'INR',
            'payment_gateway' => 'razorpay',
            'gateway_order_id' => 'order_unit_test_999',
            'status' => 'pending',
        ]);

        // Test with invalid signature
        $verifyRes = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donation->id,
            'razorpay_payment_id' => 'pay_unit_999',
            'razorpay_order_id' => 'order_unit_test_999',
            'razorpay_signature' => 'tampered_or_invalid_signature',
        ]);

        $verifyRes->assertStatus(400);
        $verifyRes->assertJson(['success' => false]);
        $this->assertEquals('pending', $donation->fresh()->status);

        // Test with valid calculated HMAC signature
        $validSignature = hash_hmac('sha256', 'order_unit_test_999|pay_unit_999', 'test_secret_key_12345');

        $successRes = $this->postJson('/api/donations/verify-payment', [
            'donation_id' => $donation->id,
            'razorpay_payment_id' => 'pay_unit_999',
            'razorpay_order_id' => 'order_unit_test_999',
            'razorpay_signature' => $validSignature,
        ]);

        $successRes->assertStatus(200);
        $successRes->assertJson(['success' => true]);
        $this->assertEquals('verified', $donation->fresh()->status);
    }
}
