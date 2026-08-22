<?php

namespace Tests\Feature;

use App\Models\PageView;
use App\Models\SiteVisitor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsAndDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_visitor_traffic_middleware_records_visitor_and_pageviews(): void
    {
        $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
        ]);

        $this->assertDatabaseCount('site_visitors', 1);
        $this->assertDatabaseCount('page_views', 1);

        $visitor = SiteVisitor::first();
        $this->assertEquals('desktop', $visitor->device_type);
        $this->assertEquals('Chrome', $visitor->browser);
        $this->assertEquals('Windows', $visitor->platform);

        // Visiting another page in same session increments count
        $this->get(route('services.index'));
        $this->assertDatabaseCount('page_views', 2);
    }

    public function test_visitor_traffic_middleware_detects_mobile_devices(): void
    {
        $this->get('/', [
            'User-Agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148 Safari/604.1',
        ]);

        $visitor = SiteVisitor::latest()->first();
        $this->assertEquals('mobile', $visitor->device_type);
        $this->assertEquals('iOS', $visitor->platform);
    }

    public function test_visitor_traffic_middleware_skips_admin_routes(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();
        $this->actingAs($superAdmin)->get('/admin');

        // Should not record page view for admin dashboard
        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_admin_dashboard_renders_with_analytics_and_charts(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        // Seed a visitor hit
        $this->get('/');

        $res = $this->actingAs($superAdmin)->get(route('admin.dashboard'));
        $res->assertStatus(200);
        $res->assertSee('إجمالي طلبات التفصيل');
        $res->assertSee('حركة الزيارات والمشاهدات اليومية');
        $res->assertSee('trafficTrendsChart');
        $res->assertSee('orderStatusChart');
        $res->assertSee('topServicesChart');
        $res->assertSee('deviceChart');
    }
}
