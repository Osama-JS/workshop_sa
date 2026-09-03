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
        $about = AboutSection::where('section_key', 'about')->first();
        $story = AboutSection::where('section_key', 'story')->first();
        $vision = AboutSection::where('section_key', 'vision_mission')->first();
        $values = AboutSection::where('section_key', 'values')->first();
        $whyUs = AboutSection::where('section_key', 'why_us')->first();
        $process = AboutSection::where('section_key', 'process')->first();
        $stats = AboutSection::where('section_key', 'stats')->first();
        $testimonials = Testimonial::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.about', compact('about', 'story', 'vision', 'values', 'whyUs', 'process', 'stats', 'testimonials'));
    }

    public function show($slug)
    {
        $page = CustomPage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('frontend.page', compact('page'));
    }
}
