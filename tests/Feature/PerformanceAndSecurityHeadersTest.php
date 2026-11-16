<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceAndSecurityHeadersTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_attached_to_public_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_sitemap_and_robots_serve_valid_seo_content(): void
    {
        $sitemap = $this->get('/sitemap.xml');
        $sitemap->assertStatus(200);
        $this->assertStringContainsString('xml', $sitemap->headers->get('Content-Type'));
        $this->assertStringContainsString('<urlset', $sitemap->getContent());

        $robots = $this->get('/robots.txt');
        $robots->assertStatus(200);
        $this->assertStringContainsString('text/plain', $robots->headers->get('Content-Type'));
        $this->assertStringContainsString('Sitemap:', $robots->getContent());
        $this->assertStringContainsString('Disallow: /admin/', $robots->getContent());
    }

    public function test_core_public_routes_render_fast_under_threshold(): void
    {
        $routes = [
            '/',
            '/about',
            '/seva',
            '/blog',
            '/donate',
            '/contact',
            '/pooja/book',
            '/volunteer',
            '/leadership',
            '/privacy-policy',
            '/sitemap.xml',
            '/robots.txt',
        ];

        foreach ($routes as $route) {
            $start = microtime(true);
            $response = $this->get($route);
            $elapsedMs = (microtime(true) - $start) * 1000;

            $response->assertStatus(200);
            // Verify SSR response executes within 500ms even in un-warmed test environment
            $this->assertLessThan(500, $elapsedMs, "Route {$route} took {$elapsedMs}ms, exceeding threshold.");
        }
    }
}
