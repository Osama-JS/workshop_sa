<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomPageController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomPage::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('placement')) {
            $query->where('placement', $request->placement);
        }

        $pages = $query->orderBy('sort_order')->latest()->paginate(15)->withQueryString();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:custom_pages,slug'],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'placement' => ['required', 'in:navbar,footer,both,none'],
            'meta_title_ar' => ['nullable', 'string', 'max:255'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_desc_ar' => ['nullable', 'string'],
            'meta_desc_en' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['title_en']);

        $originalSlug = $slug;
        $count = 1;
        while (CustomPage::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        CustomPage::create([
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'slug' => $slug,
            'content_ar' => $validated['content_ar'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'placement' => $validated['placement'],
            'meta_title_ar' => $validated['meta_title_ar'] ?? null,
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_desc_ar' => $validated['meta_desc_ar'] ?? null,
            'meta_desc_en' => $validated['meta_desc_en'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'تم إنشاء الصفحة بنجاح.');
    }

    public function edit(CustomPage $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, CustomPage $page)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:custom_pages,slug,' . $page->id],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'placement' => ['required', 'in:navbar,footer,both,none'],
            'meta_title_ar' => ['nullable', 'string', 'max:255'],
            'meta_title_en' => ['nullable', 'string', 'max:255'],
            'meta_desc_ar' => ['nullable', 'string'],
            'meta_desc_en' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : $page->slug;

        $page->update([
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'slug' => $slug,
            'content_ar' => $validated['content_ar'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'placement' => $validated['placement'],
            'meta_title_ar' => $validated['meta_title_ar'] ?? null,
            'meta_title_en' => $validated['meta_title_en'] ?? null,
            'meta_desc_ar' => $validated['meta_desc_ar'] ?? null,
            'meta_desc_en' => $validated['meta_desc_en'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'تم تحديث الصفحة بنجاح.');
    }

    public function destroy(CustomPage $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'تم حذف الصفحة بنجاح.');
    }
}
