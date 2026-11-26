<?php

namespace Tests\Feature;

use App\Models\NavigationMenuItem;
use App\Models\User;
use Database\Seeders\NavigationMenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobileNavigationMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(NavigationMenuSeeder::class);
    }

    public function test_mobile_hamburger_menu_uses_svg_icons_without_emojis_or_duplicate_links(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();
        $drawerStart = strpos($html, 'id="mobileMenu"');
        $drawerEnd = strpos($html, '<main id="main-content"');
        $this->assertNotFalse($drawerStart);
        $this->assertNotFalse($drawerEnd);

        $drawerHtml = substr($html, $drawerStart, $drawerEnd - $drawerStart);

        // No quick-bar duplicates, no dead anchors, no star symbols or emojis
        $this->assertStringNotContainsString('menu-quick-grid', $drawerHtml);
        $this->assertStringNotContainsString('/#aarti-timings', $drawerHtml);
        $this->assertStringNotContainsString('/#about-deity', $drawerHtml);
        $this->assertStringNotContainsString('★', $drawerHtml);
        $this->assertDoesNotMatchRegularExpression('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $drawerHtml);

        // SVG icons used for menu items
        $this->assertStringContainsString('class="mobile-nav-svg"', $drawerHtml);
        $this->assertStringContainsString('<svg', $drawerHtml);

        // Each anchor href inside the mobile drawer is unique (no duplicate buttons to same destination)
        preg_match_all('/<a\s[^>]*href="([^"]+)"/i', $drawerHtml, $matches);
        $hrefs = $matches[1] ?? [];
        $this->assertNotEmpty($hrefs);
        $this->assertCount(count(array_unique($hrefs)), $hrefs, 'Mobile drawer should not contain duplicate link destinations.');
    }

    public function test_mobile_hamburger_menu_syncs_with_cms_navigation_menu_items(): void
    {
        NavigationMenuItem::create([
            'label_en' => 'Pilgrim Helpdesk',
            'label_hi' => 'यात्री सहायता',
            'url' => '/volunteer',
            'sort_order' => 20,
            'is_active' => true,
            'location' => 'header',
        ]);

        $response = $this->get('/services/aarti');
        $response->assertStatus(200);

        $html = $response->getContent();
        $drawerStart = strpos($html, 'id="mobileMenu"');
        $drawerEnd = strpos($html, '<main id="main-content"');
        $drawerHtml = substr($html, $drawerStart, $drawerEnd - $drawerStart);

        $this->assertStringContainsString('Pilgrim Helpdesk', $drawerHtml);
        $this->assertStringContainsString('यात्री सहायता', $drawerHtml);
        $this->assertStringContainsString(url('/volunteer'), $drawerHtml);
        $this->assertStringContainsString('aria-current="page"', $drawerHtml);
    }

    public function test_mobile_hamburger_menu_renders_fallback_links_when_cms_menu_is_empty(): void
    {
        NavigationMenuItem::query()->delete();

        $response = $this->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();
        $drawerStart = strpos($html, 'id="mobileMenu"');
        $drawerEnd = strpos($html, '<main id="main-content"');
        $drawerHtml = substr($html, $drawerStart, $drawerEnd - $drawerStart);

        $this->assertStringContainsString(route('services.aarti'), $drawerHtml);
        $this->assertStringContainsString(route('pooja.bookingForm'), $drawerHtml);
        $this->assertStringContainsString(url('/contact'), $drawerHtml);
    }

    public function test_mobile_hamburger_menu_shows_account_actions_without_duplicates_for_authenticated_user(): void
    {
        $user = User::factory()->create([
            'role' => 'donor',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);

        $html = $response->getContent();
        $drawerStart = strpos($html, 'id="mobileMenu"');
        $drawerEnd = strpos($html, '<main id="main-content"');
        $drawerHtml = substr($html, $drawerStart, $drawerEnd - $drawerStart);

        $this->assertStringContainsString(url('/dashboard'), $drawerHtml);
        $this->assertStringContainsString(url('/logout'), $drawerHtml);
        $this->assertStringNotContainsString(url('/login'), $drawerHtml);
        $this->assertEquals(1, substr_count($drawerHtml, 'href="'.url('/dashboard').'"'));
    }
}
