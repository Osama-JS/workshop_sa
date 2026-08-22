<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $query = Portfolio::where('is_active', true)->with(['service', 'attachments']);

        if ($request->filled('service')) {
            $serviceSlug = $request->service;
            $query->whereHas('service', function ($q) use ($serviceSlug) {
                $q->where('slug', $serviceSlug);
            });
        }

        $portfolios = $query->orderBy('sort_order')->latest()->paginate(12)->withQueryString();
        $services = Service::where('is_active', true)->get();

        return view('frontend.portfolio.index', compact('portfolios', 'services'));
    }

    public function show($slug)
    {
        $portfolio = Portfolio::where('slug', $slug)
            ->where('is_active', true)
            ->with(['service', 'attachments'])
            ->firstOrFail();

        $relatedPortfolios = Portfolio::where('is_active', true)
            ->where('id', '!=', $portfolio->id)
            ->when($portfolio->service_id, function ($q) use ($portfolio) {
                $q->where('service_id', $portfolio->service_id);
            })
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.portfolio.show', compact('portfolio', 'relatedPortfolios'));
    }
}
