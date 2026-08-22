<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();
        return view('frontend.services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedPortfolios = Portfolio::where('service_id', $service->id)
            ->where('is_active', true)
            ->with('attachments')
            ->latest()
            ->take(6)
            ->get();

        $allServices = Service::where('is_active', true)->where('id', '!=', $service->id)->get();

        return view('frontend.services.show', compact('service', 'relatedPortfolios', 'allServices'));
    }
}
