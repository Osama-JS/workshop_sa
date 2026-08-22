<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $services = Service::where('is_active', true)->get();
        $portfolios = Portfolio::where('is_active', true)->get();
        $pages = CustomPage::where('is_active', true)->get();

        $content = view('frontend.seo.sitemap', compact('services', 'portfolios', 'pages'))->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $siteUrl = url('/');
        $robots = "User-agent: *\n";
        $robots .= "Disallow: /admin/\n";
        $robots .= "Disallow: /api/\n";
        $robots .= "Allow: /\n\n";
        $robots .= "Sitemap: {$siteUrl}/sitemap.xml\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }
}
