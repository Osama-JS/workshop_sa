<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\CustomPage;
use App\Models\HeroSlide;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $heroType = Setting::get('hero_type', 'slider');
        $heroSlides = HeroSlide::where('is_active', true)->orderBy('sort_order')->get();
        $heroVideoUrl = Setting::get('hero_video_url', '');
        $heroStaticImage = Setting::get('hero_static_image', '');
        $heroOverlayOpacity = Setting::get('hero_overlay_opacity', '0.7');

        $services = Service::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();

        $featuredPortfolios = Portfolio::with(['service', 'attachments'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->latest()
            ->take(8)
            ->get();

        $story = AboutSection::where('section_key', 'story')->first();
        $vision = AboutSection::where('section_key', 'vision_mission')->first();
        $stats = AboutSection::where('section_key', 'stats')->first();

        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.home', compact(
            'heroType',
            'heroSlides',
            'heroVideoUrl',
            'heroStaticImage',
            'heroOverlayOpacity',
            'services',
            'featuredPortfolios',
            'story',
            'vision',
            'stats',
            'testimonials'
        ));
    }
}
