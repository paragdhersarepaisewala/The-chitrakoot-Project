<?php

namespace Tests\Feature;

use App\Models\ArticleCategory;
use App\Models\ContactInquiry;
use App\Models\Donation;
use App\Models\EmailTemplate;
use App\Models\Event;
use App\Models\HomepageSetting;
use App\Models\LeadershipProfile;
use App\Models\NavigationMenuItem;
use App\Models\OrganizationSetting;
use App\Models\PoojaBooking;
use App\Models\PoojaService;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Receipt;
use App\Models\User;
use App\Services\ShortcodeParser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CmsFeaturesTest extends TestCase
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

    public function test_shortcode_parser_renders_magazine_widgets(): void
    {
        $input = 'Intro text [image src="/uploads/pic.webp" width="50%" caption="Test Caption"] and [quote speaker="Swami Ji" designation="Acharya"]Divya Vichar[/quote]';
        $parsed = ShortcodeParser::parse($input);

        $this->assertStringContainsString('article-figure', $parsed);
        $this->assertStringContainsString('Test Caption', $parsed);
        $this->assertStringContainsString('editorial-pull-quote', $parsed);
        $this->assertStringContainsString('Swami Ji', $parsed);
    }

    public function test_admin_can_access_blog_editor(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/blogs/create');
        $response->assertStatus(200);
        $response->assertSee('Write New Article');
        $response->assertSee('In-Article Image', false);
    }

    public function test_admin_can_access_homepage_layout_editor(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/homepage-layout');
        $response->assertStatus(200);
        $response->assertSee('Homepage Layout & Components Editor');
        $response->assertSee('Hero Cinematic Media', false);
    }

    public function test_admin_can_access_email_template_manager(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/email-templates');
        $response->assertStatus(200);
        $response->assertSee('Email SMTP & Template Manager');
        $response->assertSee('REGISTERED NOTIFICATION EMAILS');
    }

    public function test_admin_can_access_media_gallery_manager(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/media-gallery');
        $response->assertStatus(200);
        $response->assertSee('Server Media Gallery Manager');
        $response->assertSee('Upload New Media File');
    }

    public function test_api_media_list_returns_json(): void
    {
        $response = $this->actingAs($this->adminUser)->getJson('/admin/api/media-list');
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'items']);
    }

    public function test_homepage_renders_hero_with_video_and_texture_settings(): void
    {
        HomepageSetting::set('hero_bg_type', 'video');
        HomepageSetting::set('hero_headline', 'चित्तकूट धाम पावन धरा');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('hero-texture-overlay', false);
        $response->assertSee('चित्तकूट धाम पावन धरा');
    }

    public function test_email_template_preview_endpoint(): void
    {
        $template = EmailTemplate::first();
        if ($template) {
            $response = $this->actingAs($this->adminUser)->getJson("/admin/email-templates/{$template->id}/preview");
            $response->assertStatus(200);
            $response->assertJsonStructure(['success', 'template', 'rendered_subject', 'rendered_html']);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_admin_dashboard_renders_with_cms_sidebar(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('CMS Admin');
        $response->assertSee('Site Settings');
        $response->assertSee('Articles & News', false);
    }

    public function test_admin_settings_update_persists_branding_and_seo(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'org_name' => 'Updated Trust Name',
            'site_tagline' => 'Sanatan Dharma & Seva',
            'meta_description' => 'Official Trust Portal for Chitrakoot Dham devotees.',
            'ga4_measurement_id' => 'G-ABC1234567',
            'google_site_verification' => 'google-test-token-777',
            'razorpay_key_id' => 'rzp_test_testkey123',
            'razorpay_key_secret' => 'test_secret_456',
        ]);

        $response->assertStatus(302);

        $settings = OrganizationSetting::getSettings();
        $this->assertEquals('Updated Trust Name', $settings->org_name);
        $this->assertEquals('Sanatan Dharma & Seva', $settings->site_tagline);
        $this->assertEquals('G-ABC1234567', $settings->ga4_measurement_id);
        $this->assertEquals('google-test-token-777', $settings->google_site_verification);
    }

    public function test_admin_can_upload_media_file(): void
    {
        $file = UploadedFile::fake()->create('temple-test-photo.webp', 150, 'image/webp');

        $response = $this->actingAs($this->adminUser)->postJson('/admin/media-gallery/upload', [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'media' => [
                'id',
                'filename',
                'file_path',
                'file_type',
            ],
            'url',
            'relative_path',
        ]);
        $this->assertDatabaseHas('media_files', [
            'file_type' => 'image',
        ]);
    }

    public function test_admin_can_access_and_manage_navigation_menu(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/navigation-menu');
        $response->assertStatus(200);
        $response->assertSee('Navigation Menu Editor');

        // Create new item
        $createResponse = $this->actingAs($this->adminUser)->post('/admin/navigation-menu', [
            'label_en' => 'Test Link',
            'label_hi' => 'परीक्षण लिंक',
            'url' => '/test-link',
            'target' => '_self',
            'sort_order' => 99,
            'is_active' => '1',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('navigation_menu_items', [
            'label_en' => 'Test Link',
            'url' => '/test-link',
        ]);

        $item = NavigationMenuItem::where('label_en', 'Test Link')->first();

        // Update item
        $updateResponse = $this->actingAs($this->adminUser)->put('/admin/navigation-menu/'.$item->id, [
            'label_en' => 'Updated Test Link',
            'label_hi' => 'अपडेटेड लिंक',
            'url' => '/updated-link',
            'target' => '_blank',
            'sort_order' => 100,
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('navigation_menu_items', [
            'id' => $item->id,
            'label_en' => 'Updated Test Link',
            'target' => '_blank',
        ]);

        // Delete item
        $deleteResponse = $this->actingAs($this->adminUser)->delete('/admin/navigation-menu/'.$item->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('navigation_menu_items', [
            'id' => $item->id,
        ]);
    }

    public function test_homepage_renders_pooja_booking_and_attractions(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ऑनलाइन पूजा व दिव्य संकल्प');
        $response->assertSee('श्री कामदगिरि संध्या महाआरती');
        $response->assertSee('चित्रकूट के पावन तीर्थ एवं दिव्य आकर्षण');
        $response->assertSee('कामदगिरि पर्वत एवं 5 किमी परिक्रमा');
        $response->assertSee('ऋषिकुल संस्कृत गुरुकुल');
        $response->assertSee('brand-title', false);
        $response->assertSee('brand-sub', false);
    }

    public function test_admin_can_access_comments_moderation_and_approve_reject(): void
    {
        $post = Post::first() ?? Post::create([
            'title_hi' => 'कामदगिरि परिक्रमा माहात्म्य',
            'slug' => 'kamadgiri-test-slug',
            'category' => 'Spiritual',
            'content_hi' => 'पवित्र चित्रकूट धाम...',
            'author_name' => 'श्री कामदगिरि पीठम्',
            'status' => 'published',
        ]);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'name' => 'Devotee Rahul',
            'email' => 'rahul@example.com',
            'comment' => 'अति पावन और सुंदर लेख। जय श्री कामदगिरि!',
            'is_approved' => false,
        ]);

        // List comments in CMS
        $response = $this->actingAs($this->adminUser)->get('/admin/comments');
        $response->assertStatus(200);
        $response->assertSee('Comments', false);
        $response->assertSee('Discussion Moderation', false);
        $response->assertSee('Devotee Rahul');

        // Approve comment
        $approveResponse = $this->actingAs($this->adminUser)->post('/admin/comments/'.$comment->id.'/approve');
        $approveResponse->assertRedirect();
        $this->assertTrue($comment->fresh()->is_approved);

        // Reject / Unapprove comment
        $rejectResponse = $this->actingAs($this->adminUser)->post('/admin/comments/'.$comment->id.'/reject');
        $rejectResponse->assertRedirect();
        $this->assertFalse($comment->fresh()->is_approved);

        // Delete comment
        $deleteResponse = $this->actingAs($this->adminUser)->delete('/admin/comments/'.$comment->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('post_comments', ['id' => $comment->id]);
    }

    public function test_admin_blogs_table_lists_articles_without_embedded_create_form(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/blogs');
        $response->assertStatus(200);
        $response->assertSee('Articles & News Manager');
        $response->assertSee('Create New Article');
        $response->assertDontSee('Publish New Article');
    }

    public function test_login_and_register_pages_render_simplified_luxury_forms(): void
    {
        $loginRes = $this->get('/login');
        $loginRes->assertStatus(200);
        $loginRes->assertSee('भक्त व सदस्य प्रवेश');
        $loginRes->assertSee('Sign In', false);

        $regRes = $this->get('/register');
        $regRes->assertStatus(200);
        $regRes->assertSee('भक्त खाता पंजीकरण');
        $regRes->assertSee('सनातन संस्कृति एवं तपोभूमि सेवा का पावन संकल्प');
    }

    public function test_donation_receipt_renders_official_80g_details(): void
    {
        OrganizationSetting::getSettings()->update(['is_80g_enabled' => true]);

        $donation = Donation::create([
            'amount' => 5100.00,
            'status' => 'verified',
            'receipt_number' => 'TEST-REC-8899',
            'donation_type' => 'cause_specific',
            'gateway_payment_id' => 'pay_test_8899',
        ]);

        $receipt = Receipt::create([
            'donation_id' => $donation->id,
            'receipt_number' => 'TEST-REC-8899',
            'donor_name' => 'Shri Devotee Tester',
            'amount' => 5100.00,
            'cause_title' => 'कामदगिरि गौशाला नित्य सेवा',
            'donation_type' => 'Online Seva',
            'issued_at' => now(),
        ]);

        $response = $this->get('/receipt/'.$receipt->receipt_number);
        $response->assertStatus(200);
        $response->assertSee('TEST-REC-8899');
        $response->assertSee('Shri Devotee Tester');
        $response->assertSee('5,100.00');
        $response->assertSee('80G');
        $response->assertSee('कामदगिरि गौशाला नित्य सेवा');
    }

    public function test_site_wide_80g_toggle_hides_all_80g_content_cleanly(): void
    {
        // 1. Turn off 80G site-wide
        OrganizationSetting::getSettings()->update([
            'is_80g_enabled' => false,
            'receipt_legal_note' => 'भगवान कामतानाथ जी की पावन छत्रछाया में आपके सहयोग हेतु धन्यवाद। यह अधिकृत रसीद है।',
        ]);

        $donation = Donation::create([
            'amount' => 2100.00,
            'status' => 'verified',
            'receipt_number' => 'TEST-REC-CLEAN-101',
            'donation_type' => 'cause_specific',
            'gateway_payment_id' => 'pay_clean_101',
        ]);

        $receipt = Receipt::create([
            'donation_id' => $donation->id,
            'receipt_number' => 'TEST-REC-CLEAN-101',
            'donor_name' => 'Devotee Without PAN',
            'amount' => 2100.00,
            'cause_title' => 'अन्नक्षेत्र सेवा',
            'donation_type' => 'Online Seva',
            'issued_at' => now(),
        ]);

        // Receipt page: No 80G claims, only clean official donation receipt
        $receiptResp = $this->get('/receipt/'.$receipt->receipt_number);
        $receiptResp->assertStatus(200);
        $receiptResp->assertSee('आधिकारिक दान पावती');
        $receiptResp->assertDontSee('आधिकारिक 80G पावती');
        $receiptResp->assertDontSee('आयकर अधिनियम धारा 80G कर छूट प्रमाण');
        $receiptResp->assertSee('आधिकारिक पावती एवं ट्रस्ट आभार संदेश');

        // Donation checkout page: Asks for email for donation receipt, no 80G forced
        $donateResp = $this->get('/donate');
        $donateResp->assertStatus(200);
        $donateResp->assertSee('Email for Donation Receipt');
        $donateResp->assertDontSee('Email for 80G Receipt');

        // About page: Renders transparency badge instead of 80G
        $aboutResp = $this->get('/about');
        $aboutResp->assertStatus(200);
        $aboutResp->assertSee('१००% पारदर्शी सेवा संकल्प');
        $aboutResp->assertDontSee('80G प्रमाणित');

        // Leadership page: No 80G claim
        $leadResp = $this->get('/leadership');
        $leadResp->assertStatus(200);
        $leadResp->assertDontSee('80G आयकर छूट स्वीकृत');

        // Admin can toggle 80G back on with 1 click in settings
        $toggleResp = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'is_80g_enabled' => '1',
            'org_name' => 'Shri Kamadgiri Peetham Sewa Trust',
        ]);
        $toggleResp->assertSessionHas('success');

        $this->assertTrue(OrganizationSetting::getSettings()->fresh()->is_80g_enabled);
    }

    public function test_admin_sidebar_displays_clean_site_settings_link(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Site Settings & Favicon', false);
        $response->assertDontSee('1z" >', false);
    }

    public function test_admin_can_update_site_settings_with_logo_and_favicon(): void
    {
        $logoFile = UploadedFile::fake()->create('custom-logo.png', 50, 'image/png');
        $faviconFile = UploadedFile::fake()->create('custom-favicon.ico', 10, 'image/x-icon');

        $response = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'org_name' => 'Shri Kamadgiri Peetham Sewa Trust',
            'site_tagline' => 'Sanatan Dharma & Sewa',
            'meta_description' => 'Official website of Shri Kamadgiri Peetham Sewa Trust',
            'logo_file' => $logoFile,
            'favicon_file' => $faviconFile,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $settings = OrganizationSetting::getSettings();
        $this->assertStringContainsString('site-logo-', $settings->logo_path);
        $this->assertStringContainsString('site-favicon-', $settings->favicon_path);
    }

    public function test_admin_can_access_poojas_without_query_exception(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/poojas');
        $response->assertStatus(200);
        $response->assertSee('Pooja Services & Timetables');
        $response->assertDontSee('Column not found');
    }

    public function test_admin_can_access_pooja_bookings_without_query_exception(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/pooja-bookings');
        $response->assertStatus(200);
        $response->assertSee('Devotee Pooja Bookings & Sankalps');
        $response->assertDontSee('Column not found');
    }

    public function test_admin_can_access_sevas_cms_with_redesigned_ui(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/sevas');
        $response->assertStatus(200);
        $response->assertSee('Seva Projects & Initiatives CMS');
        $response->assertSee('Existing Seva Initiatives Directory');
        $response->assertSee('ADD NEW SEVA PROJECT');
    }

    public function test_admin_can_access_categories_and_topics_manager(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Editorial Categories');
        $response->assertSee('TOTAL CATEGORIES');
        $response->assertSee('UNIQUE TOPICS');
    }

    public function test_admin_can_create_update_and_delete_category(): void
    {
        // 1. Create
        $response = $this->actingAs($this->adminUser)->post('/admin/categories', [
            'name_hi' => 'वेद एवं उपनिषद',
            'name_en' => 'Vedas & Upanishads',
            'slug' => 'vedas-upanishads',
            'description' => 'Vedic wisdom and ancient scriptures',
            'color' => '#8f1719',
            'sort_order' => 10,
            'is_active' => '1',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('article_categories', ['slug' => 'vedas-upanishads']);

        $category = ArticleCategory::where('slug', 'vedas-upanishads')->first();

        // 2. Update
        $response = $this->actingAs($this->adminUser)->put('/admin/categories/'.$category->id, [
            'name_hi' => 'वेद, उपनिषद एवं दर्शन',
            'name_en' => 'Vedas, Upanishads & Darshan',
            'slug' => 'vedas-darshan',
            'description' => 'Updated description',
            'color' => '#086c60',
            'sort_order' => 12,
            'is_active' => '1',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('article_categories', ['slug' => 'vedas-darshan']);

        // 3. Delete
        $response = $this->actingAs($this->adminUser)->delete('/admin/categories/'.$category->id);
        $response->assertRedirect();
        $this->assertDatabaseMissing('article_categories', ['id' => $category->id]);
    }

    public function test_admin_can_rename_and_delete_topic_tags(): void
    {
        $post = Post::create([
            'title_hi' => 'रामघाट आरती दर्शन',
            'title_en' => 'Ramghat Aarti',
            'slug' => 'ramghat-aarti-'.time(),
            'category' => 'Spiritual',
            'content_hi' => 'विवरण',
            'tags' => 'रामघाट, आरती, चित्रकूट',
            'status' => 'published',
        ]);

        // Rename tag
        $response = $this->actingAs($this->adminUser)->post('/admin/topics/rename', [
            'old_tag' => 'आरती',
            'new_tag' => 'महाआरती',
        ]);
        $response->assertRedirect();
        $post->refresh();
        $this->assertStringContainsString('महाआरती', $post->tags);

        // Delete tag
        $response = $this->actingAs($this->adminUser)->post('/admin/topics/delete', [
            'tag' => 'महाआरती',
        ]);
        $response->assertRedirect();
        $post->refresh();
        $this->assertStringNotContainsString('महाआरती', $post->tags);
    }

    public function test_admin_can_access_and_update_seva_destinations_causes(): void
    {
        // 1. Seva
        $response = $this->actingAs($this->adminUser)->get('/admin/sevas');
        $response->assertStatus(200);
        $response->assertSee('Seva Projects & Initiatives CMS');

        // 2. Destinations
        $response = $this->actingAs($this->adminUser)->get('/admin/destinations');
        $response->assertStatus(200);
        $response->assertSee('Sacred Places Showcase & Heritage CMS');

        // 3. Causes
        $response = $this->actingAs($this->adminUser)->get('/admin/causes');
        $response->assertStatus(200);
        $response->assertSee('Donation Campaigns & Temple Funds Manager');
    }

    public function test_admin_can_manage_events_and_utsavs(): void
    {
        // 1. Access Events Manager
        $response = $this->actingAs($this->adminUser)->get('/admin/events');
        $response->assertStatus(200);
        $response->assertSee('Upcoming Events & Religious Utsavs');

        // 2. Create Event
        $createResponse = $this->actingAs($this->adminUser)->post('/admin/events', [
            'title_hi' => 'शरद पूर्णिमा रासोत्सव',
            'title_en' => 'Sharad Purnima Mahotsav',
            'event_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'location_hi' => 'रामघाट, मंदाकिनी तट',
            'summary_hi' => 'अमृतमयी खीर महाप्रसाद वितरण एवं दीपदान।',
            'is_published' => '1',
        ]);
        $createResponse->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title_hi' => 'शरद पूर्णिमा रासोत्सव',
            'location_hi' => 'रामघाट, मंदाकिनी तट',
        ]);

        $event = Event::where('title_hi', 'शरद पूर्णिमा रासोत्सव')->first();

        // 3. Update Event
        $updateResponse = $this->actingAs($this->adminUser)->put('/admin/events/'.$event->id, [
            'title_hi' => 'शरद पूर्णिमा रासोत्सव एवं महाआरती',
            'event_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'location_hi' => 'कामदगिरि गर्भगृह',
            'is_published' => '1',
        ]);
        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title_hi' => 'शरद पूर्णिमा रासोत्सव एवं महाआरती',
            'location_hi' => 'कामदगिरि गर्भगृह',
        ]);

        // 4. Delete Event
        $deleteResponse = $this->actingAs($this->adminUser)->delete('/admin/events/'.$event->id);
        $deleteResponse->assertRedirect();
        $this->assertDatabaseMissing('events', ['id' => $event->id]);

        // 5. Test creation with browser empty strings (such as empty event_end_date)
        $browserEmptyResponse = $this->actingAs($this->adminUser)->post('/admin/events', [
            'title_hi' => 'श्री राम नवमी जन्मोत्सव',
            'title_en' => '',
            'event_date' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'event_end_date' => '',
            'location_hi' => '',
            'location_en' => '',
            'summary_hi' => '',
            'description_hi' => '',
            'featured_image' => '',
            'registration_url' => '',
            'is_published' => '1',
        ]);
        $browserEmptyResponse->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title_hi' => 'श्री राम नवमी जन्मोत्सव',
            'event_end_date' => null,
        ]);

        // 6. Test creation with English-only title fallback
        $englishOnlyResponse = $this->actingAs($this->adminUser)->post('/admin/events', [
            'title_hi' => '',
            'title_en' => 'Deepawali Maha Deepotsav',
            'event_date' => now()->addDays(14)->format('Y-m-d\TH:i'),
        ]);
        $englishOnlyResponse->assertRedirect();
        $this->assertDatabaseHas('events', [
            'title_en' => 'Deepawali Maha Deepotsav',
            'title_hi' => 'Deepawali Maha Deepotsav',
        ]);
    }

    public function test_admin_can_manage_dynamic_aarti_slots_in_homepage_layout(): void
    {
        $payload = [
            'is_layout_form' => '1',
            'section_aarti_enabled' => '1',
            'aarti_strip_title' => 'नित्य आरती चक्र',
            'aarti_slots' => [
                ['name' => 'मंगला आरती', 'time' => '05:00 AM', 'desc' => 'गर्भगृह', 'is_live_pulse' => '0'],
                ['name' => 'श्रृंगार आरती', 'time' => '08:30 AM', 'desc' => 'मंदिर परिसर', 'is_live_pulse' => '0'],
                ['name' => 'राजभोग आरती', 'time' => '12:00 PM', 'desc' => 'अन्नकूट', 'is_live_pulse' => '0'],
                ['name' => 'संध्या महाआरती', 'time' => '06:30 PM', 'desc' => 'रामघाट', 'is_live_pulse' => '1'],
                ['name' => 'शयन आरती', 'time' => '09:00 PM', 'desc' => 'कपाट विश्राम', 'is_live_pulse' => '0'],
                ['name' => 'विशेष महाहवन', 'time' => '10:00 PM', 'desc' => 'यज्ञशाला', 'is_live_pulse' => '0'],
                ['name' => 'मध्यरात्रि ध्यान', 'time' => '11:30 PM', 'desc' => 'तपोभूमि', 'is_live_pulse' => '0'], // 7th dynamic slot!
            ],
        ];

        $response = $this->actingAs($this->adminUser)->post('/admin/homepage-layout', $payload);
        $response->assertRedirect();

        $savedJson = HomepageSetting::get('aarti_slots_json');
        $this->assertNotNull($savedJson);
        $decoded = json_decode($savedJson, true);
        $this->assertCount(7, $decoded);
        $this->assertEquals('मध्यरात्रि ध्यान', $decoded[6]['name']);
        $this->assertEquals('11:30 PM', $decoded[6]['time']);

        // Verify homepage renders all 7 slots and prevents text breakage
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('मध्यरात्रि ध्यान');
        $homeResponse->assertSee('11:30 PM');
    }

    public function test_homepage_events_section_hides_when_no_upcoming_events(): void
    {
        // Delete any existing events
        Event::query()->delete();

        $response = $this->get('/');
        $response->assertStatus(200);
        // The events section should be completely hidden
        $response->assertDontSee('id="events"', false);

        // Now create 1 upcoming event
        Event::create([
            'title_hi' => 'दीपावली चित्रकूट महामहोत्सव',
            'title_en' => 'Diwali Mahotsav',
            'slug' => 'diwali-mahotsav-'.time(),
            'event_date' => now()->addDays(5),
            'location_hi' => 'रामघाट मंदाकिनी',
            'is_published' => true,
        ]);

        $responseWithEvent = $this->get('/');
        $responseWithEvent->assertStatus(200);
        $responseWithEvent->assertSee('id="events"', false);
        $responseWithEvent->assertSee('दीपावली चित्रकूट महामहोत्सव');
    }

    public function test_homepage_volunteer_cta_visibility_controlled_by_cms_setting(): void
    {
        // 1. When volunteer_registration_enabled is 1
        HomepageSetting::set('volunteer_registration_enabled', '1');
        HomepageSetting::clearCache();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="volunteer-cta"', false);
        $response->assertSee('चित्रकूट धाम के पावन सेवा प्रकल्पों एवं उत्सवों में स्वयंसेवक बनें');
        $response->assertSee(route('volunteer'));

        // 2. When volunteer_registration_enabled is 0
        HomepageSetting::set('volunteer_registration_enabled', '0');
        HomepageSetting::clearCache();

        $responseHidden = $this->get('/');
        $responseHidden->assertStatus(200);
        $responseHidden->assertDontSee('id="volunteer-cta"', false);

        // Reset to default 1 so other tests have expected state
        HomepageSetting::set('volunteer_registration_enabled', '1');
        HomepageSetting::clearCache();
    }

    public function test_contact_inquiry_submission_and_admin_management(): void
    {
        // 1. Submit contact inquiry via public form
        $response = $this->post('/contact', [
            'name' => 'आदित्य मिश्र (Devotee)',
            'email' => 'aditya.devotee@example.com',
            'phone' => '+91 98765 43210',
            'subject' => 'गौसेवा एवं परिक्रमा दर्शन',
            'message' => 'चित्रकूट कामदगिरि परिक्रमा एवं गौशाला दर्शन हेतु समय और प्रक्रिया की जानकारी दें।',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_inquiries', [
            'email' => 'aditya.devotee@example.com',
            'status' => 'new',
        ]);

        $inquiry = ContactInquiry::where('email', 'aditya.devotee@example.com')->first();
        $this->assertNotNull($inquiry);

        // 2. Admin can view inquiry list
        $adminView = $this->actingAs($this->adminUser)->get('/admin/contact-inquiries');
        $adminView->assertStatus(200);
        $adminView->assertSee('आदित्य मिश्र');
        $adminView->assertSee('aditya.devotee@example.com');

        // 3. Admin toggles inquiry status
        $toggleResp = $this->actingAs($this->adminUser)->post("/admin/contact-inquiries/{$inquiry->id}/status");
        $toggleResp->assertSessionHas('success');
        $this->assertEquals('read', $inquiry->fresh()->status);
        $this->assertTrue($inquiry->fresh()->is_read);

        // 4. Admin deletes inquiry
        $deleteResp = $this->actingAs($this->adminUser)->delete("/admin/contact-inquiries/{$inquiry->id}");
        $deleteResp->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_inquiries', ['id' => $inquiry->id]);
    }

    public function test_leadership_profiles_rendering_and_admin_crud(): void
    {
        // 1. Create a leadership profile
        $patron = LeadershipProfile::create([
            'name_hi' => 'पूज्य स्वामी श्री राघवाचार्य जी महाराज',
            'name_en' => 'Swami Raghavacharya Ji Maharaj',
            'title_hi' => 'सर्वोच्च संरक्षक एवं पीठाधीश्वर',
            'title_en' => 'Chief Spiritual Patron',
            'role_category' => 'peethadhishwar',
            'photo_path' => '/uploads/patron.webp',
            'bio_hi' => 'कामदगिरि पीठ के पावन अधिष्ठाता एवं सनातन धर्म ध्वजवाहक।',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 2. Public /leadership page renders profile dynamically
        $pubResp = $this->get('/leadership');
        $pubResp->assertStatus(200);
        $pubResp->assertSee('पूज्य स्वामी श्री राघवाचार्य जी महाराज');
        $pubResp->assertSee('सर्वोच्च संरक्षक एवं पीठाधीश्वर');

        // 3. Admin can view leadership CMS management page
        $adminResp = $this->actingAs($this->adminUser)->get('/admin/leadership');
        $adminResp->assertStatus(200);
        $adminResp->assertSee('पूज्य स्वामी श्री राघवाचार्य जी महाराज');

        // 4. Admin can store a new trustee profile
        $storeResp = $this->actingAs($this->adminUser)->post('/admin/leadership', [
            'name_hi' => 'डॉ. सत्येंद्र मिश्र',
            'name_en' => 'Dr. Satyendra Mishra',
            'title_hi' => 'प्रबंध न्यासी (Managing Trustee)',
            'role_category' => 'trustee',
            'bio_hi' => 'गुरुकुल एवं सेवा प्रकल्प संचालन।',
            'sort_order' => 2,
            'is_active' => 1,
        ]);
        $storeResp->assertSessionHas('success');
        $this->assertDatabaseHas('leadership_profiles', ['name_hi' => 'डॉ. सत्येंद्र मिश्र']);

        $newTrustee = LeadershipProfile::where('name_hi', 'डॉ. सत्येंद्र मिश्र')->first();

        // 5. Admin can update profile
        $updateResp = $this->actingAs($this->adminUser)->put("/admin/leadership/{$newTrustee->id}", [
            'name_hi' => 'डॉ. सत्येंद्र मिश्र (वरिष्ठ न्यासी)',
            'title_hi' => 'वरिष्ठ प्रबंध न्यासी',
            'role_category' => 'trustee',
            'sort_order' => 2,
            'is_active' => 1,
        ]);
        $updateResp->assertSessionHas('success');
        $this->assertEquals('डॉ. सत्येंद्र मिश्र (वरिष्ठ न्यासी)', $newTrustee->fresh()->name_hi);

        // 6. Admin can delete profile
        $delResp = $this->actingAs($this->adminUser)->delete("/admin/leadership/{$newTrustee->id}");
        $delResp->assertSessionHas('success');
        $this->assertDatabaseMissing('leadership_profiles', ['id' => $newTrustee->id]);
    }

    public function test_donation_preset_amounts_and_social_links_configurable_via_cms(): void
    {
        // 1. Admin updates donation preset amounts and social URLs in settings
        $settingsResp = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'donation_preset_amounts' => '251,501,1501,2501',
            'instagram_url' => 'https://instagram.com/chitrakoot_dham_trust',
            'whatsapp_url' => 'https://whatsapp.com/channel/chitrakoot_official',
            'phone' => '+91 94150 99999',
            'email' => 'seva@kamadgiripeetham.org',
            'address' => 'परिक्रमा मार्ग, कामदगिरि प्रथम द्वार, चित्रकूट',
        ]);
        $settingsResp->assertSessionHas('success');

        $org = OrganizationSetting::getSettings();
        $this->assertEquals('251,501,1501,2501', $org->donation_preset_amounts);
        $this->assertEquals('https://instagram.com/chitrakoot_dham_trust', $org->instagram_url);
        $this->assertEquals('https://whatsapp.com/channel/chitrakoot_official', $org->whatsapp_url);

        // 2. Public donate page renders new preset buttons dynamically
        $donateResp = $this->get('/donate');
        $donateResp->assertStatus(200);
        $donateResp->assertSee('₹251');
        $donateResp->assertSee('₹1,501');
        $donateResp->assertSee('₹2,501');

        // 3. Public footer renders updated social channels and contact details
        $homeResp = $this->get('/');
        $homeResp->assertStatus(200);
        $homeResp->assertSee('https://instagram.com/chitrakoot_dham_trust');
        $homeResp->assertSee('https://whatsapp.com/channel/chitrakoot_official');
        $homeResp->assertSee('seva@kamadgiripeetham.org');
    }

    public function test_privacy_policy_cms_override_and_pooja_booking_status(): void
    {
        // 1. Admin updates privacy policy content in settings
        $settingsResp = $this->actingAs($this->adminUser)->post('/admin/settings', [
            'privacy_policy_content' => '<div id="custom-legal-terms">विशेष कानूनी गोपनीयता एवं 80G नियम २०२६</div>',
        ]);
        $settingsResp->assertSessionHas('success');

        $org = OrganizationSetting::getSettings();
        $this->assertEquals('<div id="custom-legal-terms">विशेष कानूनी गोपनीयता एवं 80G नियम २०२६</div>', $org->privacy_policy_content);

        // 2. Public /privacy-policy renders the custom CMS content
        $privacyResp = $this->get('/privacy-policy');
        $privacyResp->assertStatus(200);
        $privacyResp->assertSee('विशेष कानूनी गोपनीयता एवं 80G नियम २०२६', false);

        // 3. Pooja booking does not auto-confirm without payment
        $pooja = PoojaService::first();
        if ($pooja) {
            $bookResp = $this->post('/pooja/book', [
                'pooja_service_id' => $pooja->id,
                'devotee_name' => 'राघवेंद्र शर्मा',
                'phone' => '+91 94150 11223',
                'email' => 'raghavendra@example.com',
                'booking_date' => now()->addDays(2)->format('Y-m-d'),
                'time_slot' => 'संध्या आरती (5:30-7 PM)',
                'amount' => 1100,
            ]);
            $bookResp->assertSessionHas('success');

            $booking = PoojaBooking::where('phone', '+91 94150 11223')->first();
            $this->assertNotNull($booking);
            $this->assertEquals('pending', $booking->payment_status);
            $this->assertEquals('pending', $booking->status);
        }
    }

    public function test_past_events_auto_expire_and_move_to_archive_both_programmatically_and_via_artisan_command(): void
    {
        // 1. Create a past event (yesterday)
        $pastEvent = Event::create([
            'title_hi' => 'शरद पूर्णिमा उत्सव एवं खीर प्रसाद',
            'title_en' => 'Sharad Purnima Utsav',
            'slug' => 'sharad-purnima-past-'.time(),
            'event_date' => now()->subDays(2),
            'location_hi' => 'कामदगिरि परिक्रमा',
            'is_published' => true,
            'is_archived' => false,
        ]);

        // 2. Create an upcoming event (next week)
        $futureEvent = Event::create([
            'title_hi' => 'हनुमान जयंती महामहोत्सव',
            'title_en' => 'Hanuman Jayanti Mahotsav',
            'slug' => 'hanuman-jayanti-future-'.time(),
            'event_date' => now()->addDays(7),
            'location_hi' => 'हनुमान धारा, चित्रकूट',
            'is_published' => true,
            'is_archived' => false,
        ]);

        // Run artisan command
        $this->artisan('events:archive-expired')
            ->assertSuccessful();

        $pastEvent->refresh();
        $futureEvent->refresh();

        $this->assertTrue($pastEvent->is_archived);
        $this->assertNotNull($pastEvent->archived_at);
        $this->assertFalse($futureEvent->is_archived);

        // Upcoming scope should exclude the past event
        $upcomingSlugs = Event::upcoming()->pluck('slug')->toArray();
        $this->assertContains($futureEvent->slug, $upcomingSlugs);
        $this->assertNotContains($pastEvent->slug, $upcomingSlugs);

        // Archived scope should contain the past event
        $archivedSlugs = Event::archived()->pluck('slug')->toArray();
        $this->assertContains($pastEvent->slug, $archivedSlugs);
    }

    public function test_public_events_page_shows_upcoming_and_archive_tabs(): void
    {
        $pastEvent = Event::create([
            'title_hi' => 'मकर संक्रांति स्नान पर्व',
            'title_en' => 'Makar Sankranti Snan',
            'slug' => 'makar-sankranti-'.time(),
            'event_date' => now()->subMonth(),
            'location_hi' => 'रामघाट तट',
            'is_published' => true,
            'is_archived' => true,
        ]);

        $futureEvent = Event::create([
            'title_hi' => 'गुरु पूर्णिमा व्यास पूजन',
            'title_en' => 'Guru Purnima Vyas Pujan',
            'slug' => 'guru-purnima-'.time(),
            'event_date' => now()->addDays(15),
            'location_hi' => 'श्री कामदगिरि पीठम् सभागार',
            'is_published' => true,
            'is_archived' => false,
        ]);

        // Default upcoming tab
        $upcomingResp = $this->get('/events');
        $upcomingResp->assertStatus(200);
        $upcomingResp->assertSee('गुरु पूर्णिमा व्यास पूजन');
        $upcomingResp->assertDontSee('मकर संक्रांति स्नान पर्व');
        $upcomingResp->assertSee('उत्सव अभिलेख (Archive)');

        // Archive tab
        $archiveResp = $this->get('/events?tab=archive');
        $archiveResp->assertStatus(200);
        $archiveResp->assertSee('मकर संक्रांति स्नान पर्व');
        $archiveResp->assertSee('संपन्न एवं संगृहीत (Archived)');
    }

    public function test_admin_can_toggle_archive_and_trigger_auto_archive_events(): void
    {
        $event = Event::create([
            'title_hi' => 'तुलसी जयंती महोत्सव',
            'title_en' => 'Tulsi Jayanti Mahotsav',
            'slug' => 'tulsi-jayanti-'.time(),
            'event_date' => now()->addDays(20),
            'location_hi' => 'तुलसी गुफा, राजापुर',
            'is_published' => true,
            'is_archived' => false,
        ]);

        // Toggle to archive
        $toggleResp = $this->actingAs($this->adminUser)->post("/admin/events/{$event->id}/toggle-archive");
        $toggleResp->assertRedirect();
        $toggleResp->assertSessionHas('success');

        $event->refresh();
        $this->assertTrue($event->is_archived);

        // Toggle back to active
        $unarchiveResp = $this->actingAs($this->adminUser)->post("/admin/events/{$event->id}/toggle-archive");
        $unarchiveResp->assertRedirect();

        $event->refresh();
        $this->assertFalse($event->is_archived);

        // Trigger auto archive
        $autoResp = $this->actingAs($this->adminUser)->post('/admin/events/auto-archive');
        $autoResp->assertRedirect();
        $autoResp->assertSessionHas('success');
    }
}
