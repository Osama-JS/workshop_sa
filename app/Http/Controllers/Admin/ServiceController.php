<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('short_desc_ar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $services = $query->orderBy('sort_order')->latest()->paginate(12)->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug'],
            'short_desc_ar' => ['nullable', 'string', 'max:500'],
            'short_desc_en' => ['nullable', 'string', 'max:500'],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $slug = !empty($validated['slug']) 
            ? Str::slug($validated['slug']) 
            : Str::slug($validated['title_en']);

        $originalSlug = $slug;
        $count = 1;
        while (Service::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'slug' => $slug,
            'short_desc_ar' => $validated['short_desc_ar'] ?? null,
            'short_desc_en' => $validated['short_desc_en'] ?? null,
            'content_ar' => $validated['content_ar'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'icon' => $validated['icon'] ?? 'tree',
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = $path;
        }

        Service::create($data);

        return redirect()->route('admin.services.index')->with('success', 'تمت إضافة الخدمة بنجاح.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:services,slug,' . $service->id],
            'short_desc_ar' => ['nullable', 'string', 'max:500'],
            'short_desc_en' => ['nullable', 'string', 'max:500'],
            'content_ar' => ['nullable', 'string'],
            'content_en' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'title_ar' => $validated['title_ar'],
            'title_en' => $validated['title_en'],
            'short_desc_ar' => $validated['short_desc_ar'] ?? null,
            'short_desc_en' => $validated['short_desc_en'] ?? null,
            'content_ar' => $validated['content_ar'] ?? null,
            'content_en' => $validated['content_en'] ?? null,
            'icon' => $validated['icon'] ?? $service->icon,
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if (!empty($validated['slug']) && $validated['slug'] !== $service->slug) {
            $data['slug'] = Str::slug($validated['slug']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $data['image'] = $path;
        }

        $service->update($data);

        return redirect()->route('admin.services.index')->with('success', 'تم تحديث بيانات الخدمة بنجاح.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'تم حذف الخدمة بنجاح.');
    }
}
