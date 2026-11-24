<?php

namespace Tests\Feature;

use App\Models\PageSeoSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class PageSocialFeaturedImageTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_all_17_major_pages_have_generated_raea_social_images_on_disk(): void
    {
        $pages = [
            'home',
            'about',
            'leadership',
            'seva',
            'chitrakoot',
            'pooja',
            'events',
            'blog',
            'gallery',
            'contact',
            'donate',
            'volunteer',
            'privacy',
            'services',
            'aarti',
            'atithi-vishram',
            'temple-booking',
        ];

        foreach ($pages as $page) {
            $path = public_path("images/og/og-{$page}.png");
            $this->assertFileExists($path, "Expected 3D RAEA image for {$page} to exist at {$path}");
            $this->assertGreaterThan(10000, filesize($path), "Expected {$path} to be a non-trivial PNG file");
        }
    }

    public function test_admin_can_view_seo_settings_with_all_major_pages(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/seo-settings');

        $response->assertStatus(200);
        $response->assertSee('All Pages SEO & Meta Manager');
        $response->assertSee('Social Share Featured Image');
        $response->assertSee('Curated Brand Card');
        $response->assertSee('Home Page');
        $response->assertSee('About Us Page');
        $response->assertSee('Daily Aarti Schedule');
    }

    public function test_admin_can_update_featured_image_via_bulk_settings(): void
    {
        PageSeoSetting::create([
            'page_key' => 'home',
            'page_title' => 'Home Page',
            'og_image' => '/images/og/og-home.png',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/seo-settings', [
            'pages' => [
                'home' => [
                    'page_title' => 'Home Page',
                    'meta_title' => 'Shri Kamadgiri Peetham Official Portal',
                    'og_image_url' => '/images/custom-og-banner.png',
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'home',
            'og_image' => '/images/custom-og-banner.png',
        ]);
    }

    public function test_admin_can_remove_featured_image_via_bulk_settings(): void
    {
        PageSeoSetting::create([
            'page_key' => 'about',
            'page_title' => 'About Us Page',
            'og_image' => '/images/og/og-about.png',
        ]);

        $response = $this->actingAs($this->adminUser)->post('/admin/seo-settings', [
            'pages' => [
                'about' => [
                    'page_title' => 'About Us Page',
                    'remove_og_image' => '1',
                ],
            ],
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'about',
            'og_image' => null,
        ]);
    }

    public function test_admin_can_update_and_remove_og_image_via_ajax_endpoint(): void
    {
        // 1. Reset to RAEA image
        $resetResponse = $this->actingAs($this->adminUser)->postJson('/admin/seo-settings/update-og-image', [
            'page_key' => 'chitrakoot',
            'action' => 'reset_raea',
        ]);

        $resetResponse->assertStatus(200);
        $resetResponse->assertJson([
            'success' => true,
            'page_key' => 'chitrakoot',
            'og_image' => '/images/og/og-chitrakoot.png',
        ]);

        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'chitrakoot',
            'og_image' => '/images/og/og-chitrakoot.png',
        ]);

        // 2. Set Custom URL
        $setUrlResponse = $this->actingAs($this->adminUser)->postJson('/admin/seo-settings/update-og-image', [
            'page_key' => 'chitrakoot',
            'action' => 'set_url',
            'image_url' => 'https://example.com/custom-chitrakoot.jpg',
        ]);

        $setUrlResponse->assertStatus(200);
        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'chitrakoot',
            'og_image' => 'https://example.com/custom-chitrakoot.jpg',
        ]);

        // 3. Remove Image
        $removeResponse = $this->actingAs($this->adminUser)->postJson('/admin/seo-settings/update-og-image', [
            'page_key' => 'chitrakoot',
            'action' => 'remove',
        ]);

        $removeResponse->assertStatus(200);
        $this->assertDatabaseHas('page_seo_settings', [
            'page_key' => 'chitrakoot',
            'og_image' => null,
        ]);

        // 4. Upload File
        $uploadedFile = UploadedFile::fake()->create('my-sacred-banner.png', 500, 'image/png');
        $uploadResponse = $this->actingAs($this->adminUser)->postJson('/admin/seo-settings/update-og-image', [
            'page_key' => 'chitrakoot',
            'action' => 'upload',
            'image_file' => $uploadedFile,
        ]);

        $uploadResponse->assertStatus(200);
        $uploadedRecord = PageSeoSetting::where('page_key', 'chitrakoot')->first();
        $this->assertNotNull($uploadedRecord->og_image);
        $this->assertStringStartsWith('/uploads/og/', $uploadedRecord->og_image);

        // Clean up uploaded test file
        if (File::exists(public_path(ltrim($uploadedRecord->og_image, '/')))) {
            File::delete(public_path(ltrim($uploadedRecord->og_image, '/')));
        }
    }

    public function test_frontend_major_pages_render_configured_og_image(): void
    {
        PageSeoSetting::updateOrCreate(
            ['page_key' => 'home'],
            [
                'page_title' => 'Home Page',
                'meta_title' => 'Shri Kamadgiri Peetham Sewa Trust | Chitrakoot Dham',
                'og_image' => '/images/og/og-home.png',
            ]
        );

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('/images/og/og-home.png');
        $response->assertSee('property="og:image:width" content="1200"', false);
        $response->assertSee('property="og:image:height" content="630"', false);
    }
}
