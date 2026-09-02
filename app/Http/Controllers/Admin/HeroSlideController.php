<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    public function index(Request $request)
    {
        $slides = HeroSlide::orderBy('sort_order')->latest()->paginate(10);
        return view('admin.hero_slides.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero_slides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:255'],
            'subtitle_en' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'btn_text_ar' => ['nullable', 'string', 'max:100'],
            'btn_text_en' => ['nullable', 'string', 'max:100'],
            'btn_url' => ['nullable', 'string', 'max:255'],
            'secondary_btn_text_ar' => ['nullable', 'string', 'max:100'],
            'secondary_btn_text_en' => ['nullable', 'string', 'max:100'],
            'secondary_btn_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'subtitle_ar' => $validated['subtitle_ar'] ?? null,
            'subtitle_en' => $validated['subtitle_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'btn_text_ar' => $validated['btn_text_ar'] ?? null,
            'btn_text_en' => $validated['btn_text_en'] ?? null,
            'btn_url' => $validated['btn_url'] ?? null,
            'secondary_btn_text_ar' => $validated['secondary_btn_text_ar'] ?? null,
            'secondary_btn_text_en' => $validated['secondary_btn_text_en'] ?? null,
            'secondary_btn_url' => $validated['secondary_btn_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero_slides', 'public');
            $data['image'] = $path;
        }

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')->with('success', 'تمت إضافة شريحة العرض بنجاح.');
    }

    public function edit(HeroSlide $hero_slide)
    {
        return view('admin.hero_slides.edit', ['slide' => $hero_slide]);
    }

    public function update(Request $request, HeroSlide $hero_slide)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'subtitle_ar' => ['nullable', 'string', 'max:255'],
            'subtitle_en' => ['nullable', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'btn_text_ar' => ['nullable', 'string', 'max:100'],
            'btn_text_en' => ['nullable', 'string', 'max:100'],
            'btn_url' => ['nullable', 'string', 'max:255'],
            'secondary_btn_text_ar' => ['nullable', 'string', 'max:100'],
            'secondary_btn_text_en' => ['nullable', 'string', 'max:100'],
            'secondary_btn_url' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:6144'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'subtitle_ar' => $validated['subtitle_ar'] ?? null,
            'subtitle_en' => $validated['subtitle_en'] ?? null,
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'btn_text_ar' => $validated['btn_text_ar'] ?? null,
            'btn_text_en' => $validated['btn_text_en'] ?? null,
            'btn_url' => $validated['btn_url'] ?? null,
            'secondary_btn_text_ar' => $validated['secondary_btn_text_ar'] ?? null,
            'secondary_btn_text_en' => $validated['secondary_btn_text_en'] ?? null,
            'secondary_btn_url' => $validated['secondary_btn_url'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('hero_slides', 'public');
            $data['image'] = $path;
        }

        $hero_slide->update($data);

        return redirect()->route('admin.hero-slides.index')->with('success', 'تم تحديث شريحة العرض بنجاح.');
    }

    public function destroy(HeroSlide $hero_slide)
    {
        $hero_slide->delete();
        return redirect()->route('admin.hero-slides.index')->with('success', 'تم حذف شريحة العرض بنجاح.');
    }
}
