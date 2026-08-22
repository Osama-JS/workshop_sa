<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use App\Models\SiteVisitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorTraffic
{
    /**
     * Handle an incoming request and track visitor analytics.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests on public frontend pages
        if ($request->isMethod('GET') && $response->getStatusCode() === 200 && !$this->shouldSkipTracking($request)) {
            try {
                $this->recordTraffic($request);
            } catch (\Throwable $e) {
                // Silently avoid breaking visitor response if analytics recording fails
            }
        }

        return $response;
    }

    protected function shouldSkipTracking(Request $request): bool
    {
        $path = $request->path();

        // Skip admin backend, api, livewire, debug, assets, or webhooks
        if ($request->is('admin*') || $request->is('api*') || $request->is('up') || $request->is('sanctum*')) {
            return true;
        }

        $userAgent = $request->userAgent() ?: '';

        // Skip known bots and search crawlers from polluting human visitor metrics
        $botKeywords = ['bot', 'crawler', 'spider', 'slurp', 'mediapartners', 'lighthouse', 'postman'];
        foreach ($botKeywords as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function recordTraffic(Request $request): void
    {
        $sessionId = session()->getId();
        $ip = $request->ip();
        $userAgent = $request->userAgent() ?: '';

        // Device Type Detection
        $deviceType = 'desktop';
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            $deviceType = 'tablet';
        } elseif (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)) {
            $deviceType = 'mobile';
        }

        // Browser Detection
        $browser = 'Other';
        if (stripos($userAgent, 'Edge') !== false || stripos($userAgent, 'Edg') !== false) {
            $browser = 'Edge';
        } elseif (stripos($userAgent, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (stripos($userAgent, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (stripos($userAgent, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (stripos($userAgent, 'Opera') !== false || stripos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        }

        // Platform Detection
        $platform = 'Other';
        if (stripos($userAgent, 'Windows') !== false) {
            $platform = 'Windows';
        } elseif (stripos($userAgent, 'Android') !== false) {
            $platform = 'Android';
        } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
            $platform = 'iOS';
        } elseif (stripos($userAgent, 'Macintosh') !== false || stripos($userAgent, 'Mac OS') !== false) {
            $platform = 'MacOS';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $platform = 'Linux';
        }

        // Find or create SiteVisitor
        $visitor = SiteVisitor::firstOrCreate(
            ['session_id' => $sessionId],
            [
                'ip_address' => $ip,
                'device_type' => $deviceType,
                'browser' => $browser,
                'platform' => $platform,
                'landing_page' => $request->fullUrl(),
                'referrer' => $request->header('referer'),
                'utm_source' => $request->query('utm_source'),
                'utm_medium' => $request->query('utm_medium'),
                'utm_campaign' => $request->query('utm_campaign'),
                'page_views_count' => 0,
                'first_visited_at' => now(),
                'last_visited_at' => now(),
            ]
        );

        $visitor->increment('page_views_count');
        $visitor->update(['last_visited_at' => now()]);

        // Record Page View
        PageView::create([
            'site_visitor_id' => $visitor->id,
            'session_id' => $sessionId,
            'url' => $request->fullUrl(),
            'route_name' => $request->route()?->getName(),
            'page_title' => $request->route()?->getName(),
            'referrer' => $request->header('referer'),
            'ip_address' => $ip,
            'viewed_at' => now(),
        ]);
    }
}
