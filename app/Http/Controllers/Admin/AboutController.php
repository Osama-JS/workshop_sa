<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $story = AboutSection::where('section_key', 'story')->first();
        $vision = AboutSection::where('section_key', 'vision_mission')->first();
        $stats = AboutSection::where('section_key', 'stats')->first();

        return view('admin.about.index', compact('story', 'vision', 'stats'));
    }

    public function update(Request $request)
    {
        // 1. Story section
        if ($request->has('story')) {
            $storyData = $request->input('story');
            $story = AboutSection::firstOrNew(['section_key' => 'story']);
            $story->title_ar = $storyData['title_ar'] ?? '';
            $story->title_en = $storyData['title_en'] ?? '';
            $story->subtitle_ar = $storyData['subtitle_ar'] ?? '';
            $story->subtitle_en = $storyData['subtitle_en'] ?? '';
            $story->content_ar = $storyData['content_ar'] ?? '';
            $story->content_en = $storyData['content_en'] ?? '';

            if ($request->hasFile('story.image')) {
                $path = $request->file('story.image')->store('about', 'public');
                $story->image = $path;
            }
            $story->save();
        }

        // 2. Vision & Mission section
        if ($request->has('vision')) {
            $visionData = $request->input('vision');
            $vision = AboutSection::firstOrNew(['section_key' => 'vision_mission']);
            $vision->title_ar = $visionData['title_ar'] ?? '';
            $vision->title_en = $visionData['title_en'] ?? '';
            $vision->subtitle_ar = $visionData['subtitle_ar'] ?? '';
            $vision->subtitle_en = $visionData['subtitle_en'] ?? '';
            $vision->content_ar = $visionData['content_ar'] ?? '';
            $vision->content_en = $visionData['content_en'] ?? '';
            $vision->save();
        }

        // 3. Stats / Counters section
        if ($request->has('stats')) {
            $statsData = $request->input('stats');
            $stats = AboutSection::firstOrNew(['section_key' => 'stats']);
            $stats->title_ar = $statsData['title_ar'] ?? '';
            $stats->title_en = $statsData['title_en'] ?? '';
            $stats->subtitle_ar = $statsData['subtitle_ar'] ?? '';
            $stats->subtitle_en = $statsData['subtitle_en'] ?? '';

            $counters = [];
            if (!empty($statsData['counters']) && is_array($statsData['counters'])) {
                foreach ($statsData['counters'] as $counter) {
                    if (!empty($counter['number']) && !empty($counter['label_ar'])) {
                        $counters[] = [
                            'number' => $counter['number'],
                            'label_ar' => $counter['label_ar'],
                            'label_en' => $counter['label_en'] ?? $counter['label_ar'],
                        ];
                    }
                }
            }
            $stats->meta_data = $counters;
            $stats->save();
        }

        return back()->with('success', 'تم حفظ وتحديث بيانات صفحة من نحن بنجاح.');
    }
}
