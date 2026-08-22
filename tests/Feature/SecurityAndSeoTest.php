<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityAndSeoTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_dynamic_sitemap_xml_generation(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $response->assertSee('<loc>', false);
        $response->assertSee('/services', false);
        $response->assertSee('/portfolio', false);
    }

    public function test_dynamic_robots_txt_generation(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Sitemap:');
    }

    public function test_homepage_contains_opengraph_and_schema_markup(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('og:title', false);
        $response->assertSee('og:description', false);
        $response->assertSee('twitter:card', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('HomeAndConstructionBusiness', false);
    }
}
