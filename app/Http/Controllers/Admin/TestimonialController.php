<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name_ar', 'like', "%{$search}%")
                  ->orWhere('client_name_en', 'like', "%{$search}%")
                  ->orWhere('company_ar', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $testimonials = $query->orderBy('sort_order')->latest()->paginate(12)->withQueryString();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name_ar' => ['required', 'string', 'max:255'],
            'client_name_en' => ['required', 'string', 'max:255'],
            'position_ar' => ['nullable', 'string', 'max:255'],
            'position_en' => ['nullable', 'string', 'max:255'],
            'company_ar' => ['nullable', 'string', 'max:255'],
            'company_en' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment_ar' => ['required', 'string'],
            'comment_en' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'client_name_ar' => $validated['client_name_ar'],
            'client_name_en' => $validated['client_name_en'],
            'position_ar' => $validated['position_ar'] ?? null,
            'position_en' => $validated['position_en'] ?? null,
            'company_ar' => $validated['company_ar'] ?? null,
            'company_en' => $validated['company_en'] ?? null,
            'rating' => $validated['rating'],
            'comment_ar' => $validated['comment_ar'],
            'comment_en' => $validated['comment_en'],
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('testimonials', 'public');
            $data['avatar'] = $path;
        }

        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تمت إضافة رأي العميل بنجاح.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'client_name_ar' => ['required', 'string', 'max:255'],
            'client_name_en' => ['required', 'string', 'max:255'],
            'position_ar' => ['nullable', 'string', 'max:255'],
            'position_en' => ['nullable', 'string', 'max:255'],
            'company_ar' => ['nullable', 'string', 'max:255'],
            'company_en' => ['nullable', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment_ar' => ['required', 'string'],
            'comment_en' => ['required', 'string'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $data = [
            'client_name_ar' => $validated['client_name_ar'],
            'client_name_en' => $validated['client_name_en'],
            'position_ar' => $validated['position_ar'] ?? null,
            'position_en' => $validated['position_en'] ?? null,
            'company_ar' => $validated['company_ar'] ?? null,
            'company_en' => $validated['company_en'] ?? null,
            'rating' => $validated['rating'],
            'comment_ar' => $validated['comment_ar'],
            'comment_en' => $validated['comment_en'],
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('testimonials', 'public');
            $data['avatar'] = $path;
        }

        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('success', 'تم تحديث رأي العميل بنجاح.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'تم حذف رأي العميل بنجاح.');
    }
}
