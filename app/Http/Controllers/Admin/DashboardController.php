<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\CustomOrder;
use App\Models\PageView;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteVisitor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. High-Level KPI Metric Counters
        $totalOrders = CustomOrder::count();
        $pendingOrders = CustomOrder::where('status', 'pending')->count();
        $inProgressOrders = CustomOrder::where('status', 'in_progress')->count();
        $completedOrders = CustomOrder::where('status', 'completed')->count();

        $totalServices = Service::where('is_active', true)->count();
        $totalPortfolios = Portfolio::where('is_active', true)->count();
        $unreadMessages = ContactMessage::where('is_read', false)->count();

        $today = Carbon::today();
        $visitorsToday = SiteVisitor::where('last_visited_at', '>=', $today)->count();
        $visitorsWeek = SiteVisitor::where('last_visited_at', '>=', Carbon::now()->subDays(7))->count();
        $totalVisitors = SiteVisitor::count();
        $totalPageViews = PageView::count();

        // 2. Traffic Trends Chart (Last 14 Days)
        $trafficLabels = [];
        $trafficPageViews = [];
        $trafficUniqueVisitors = [];

        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $label = $date->format('d M');

            $trafficLabels[] = $label;

            // Page views count for this day
            $pvCount = PageView::whereDate('viewed_at', $dateStr)->count();
            $trafficPageViews[] = $pvCount;

            // Visitors count for this day
            $vCount = SiteVisitor::whereDate('first_visited_at', $dateStr)
                ->orWhereDate('last_visited_at', $dateStr)
                ->count();
            $trafficUniqueVisitors[] = $vCount;
        }

        // 3. Orders Status Breakdown (Doughnut)
        $orderStatuses = [
            'pending' => CustomOrder::where('status', 'pending')->count(),
            'in_review' => CustomOrder::where('status', 'in_review')->count(),
            'contacted' => CustomOrder::where('status', 'contacted')->count(),
            'in_progress' => CustomOrder::where('status', 'in_progress')->count(),
            'completed' => CustomOrder::where('status', 'completed')->count(),
            'cancelled' => CustomOrder::where('status', 'cancelled')->count(),
        ];

        // 4. Device Distribution Breakdown (Pie)
        $devices = SiteVisitor::select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->toArray();

        $deviceLabels = ['Desktop', 'Mobile', 'Tablet'];
        $deviceData = [
            $devices['desktop'] ?? 0,
            $devices['mobile'] ?? 0,
            $devices['tablet'] ?? 0,
        ];

        // 5. Top Requested Services (Bar Chart)
        $topServices = Service::withCount('customOrders')
            ->orderBy('custom_orders_count', 'desc')
            ->take(5)
            ->get();

        $topServiceLabels = $topServices->pluck('title_ar')->toArray();
        $topServiceCounts = $topServices->pluck('custom_orders_count')->toArray();

        // 6. Recent Activity Streams
        $latestOrders = CustomOrder::with('service')->latest()->take(5)->get();
        $latestMessages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'inProgressOrders',
            'completedOrders',
            'totalServices',
            'totalPortfolios',
            'unreadMessages',
            'visitorsToday',
            'visitorsWeek',
            'totalVisitors',
            'totalPageViews',
            'trafficLabels',
            'trafficPageViews',
            'trafficUniqueVisitors',
            'orderStatuses',
            'deviceLabels',
            'deviceData',
            'topServiceLabels',
            'topServiceCounts',
            'latestOrders',
            'latestMessages'
        ));
    }
}
