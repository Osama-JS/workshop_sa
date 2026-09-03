<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = AboutSection::where('section_key', 'about')->first();
        $story = AboutSection::where('section_key', 'story')->first();
        $vision = AboutSection::where('section_key', 'vision_mission')->first();
        $values = AboutSection::where('section_key', 'values')->first();
        $stats = AboutSection::where('section_key', 'stats')->first();

        return view('admin.about.index', compact('about', 'story', 'vision', 'values', 'stats'));
    }

    public function update(Request $request)
    {
        // 1. About section (من نحن)
        if ($request->has('about')) {
            $aboutData = $request->input('about');
            $about = AboutSection::firstOrNew(['section_key' => 'about']);
            $about->title_ar = $aboutData['title_ar'] ?? '';
            $about->title_en = $aboutData['title_en'] ?? '';
            $about->subtitle_ar = $aboutData['subtitle_ar'] ?? '';
            $about->subtitle_en = $aboutData['subtitle_en'] ?? '';
            $about->content_ar = $aboutData['content_ar'] ?? '';
            $about->content_en = $aboutData['content_en'] ?? '';

            if ($request->hasFile('about.image')) {
                $path = $request->file('about.image')->store('about', 'public');
                $about->image = $path;
            }
            $about->save();
        }

        // 2. Story section (قصتنا)
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

        // 3. Vision & Mission section (رؤيتنا ورسالتنا)
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

        // 4. Our Values section (قيمنا ومبادئنا)
        if ($request->has('values')) {
            $valuesData = $request->input('values');
            $values = AboutSection::firstOrNew(['section_key' => 'values']);
            $values->title_ar = $valuesData['title_ar'] ?? '';
            $values->title_en = $valuesData['title_en'] ?? '';
            $values->subtitle_ar = $valuesData['subtitle_ar'] ?? '';
            $values->subtitle_en = $valuesData['subtitle_en'] ?? '';

            $items = [];
            if (!empty($valuesData['items']) && is_array($valuesData['items'])) {
                foreach ($valuesData['items'] as $item) {
                    if (!empty($item['title_ar'])) {
                        $items[] = [
                            'title_ar' => $item['title_ar'],
                            'title_en' => $item['title_en'] ?? $item['title_ar'],
                            'icon' => $item['icon'] ?? 'fa-solid fa-gem',
                            'desc_ar' => $item['desc_ar'] ?? '',
                            'desc_en' => $item['desc_en'] ?? ($item['desc_ar'] ?? ''),
                        ];
                    }
                }
            }
            $values->meta_data = $items;
            $values->save();
        }

        // 5. Stats / Counters section (الأرقام والإنجازات)
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
