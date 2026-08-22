<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use App\Models\CustomPage;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $story = AboutSection::where('section_key', 'story')->first();
        $vision = AboutSection::where('section_key', 'vision_mission')->first();
        $stats = AboutSection::where('section_key', 'stats')->first();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.about', compact('story', 'vision', 'stats', 'testimonials'));
    }

    public function show($slug)
    {
        $page = CustomPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
