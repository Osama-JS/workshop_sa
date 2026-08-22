<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiDesignIdea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AiDesignIdeaController extends Controller
{
    public function index(Request $request): View
    {
        $query = AiDesignIdea::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('wood_type', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $ideas = $query->orderBy('sort_order', 'asc')->latest()->paginate(15);
        $categories = [
            'bedrooms' => 'غرف نوم (Bedrooms)',
            'offices' => 'مكاتب تنفيذية (Offices)',
            'tables' => 'طاولات طعام ومجالس (Tables)',
            'booths' => 'بوثات وأجنحة معارض (Booths)',
            'wall_cladding' => 'تكسيات وديكور شاشات (Wall Paneling)',
            'cabinets' => 'خزائن ودريسنج روم (Cabinets)',
            'decor' => 'ديكورات وتطعيمات خشبية (Decor)',
            'other' => 'أخرى (Other)',
        ];

        return view('admin.ai_ideas.index', compact('ideas', 'categories'));
    }

    public function create(): View
    {
        $categories = [
            'bedrooms' => 'غرف نوم (Bedrooms)',
            'offices' => 'مكاتب تنفيذية (Offices)',
            'tables' => 'طاولات طعام ومجالس (Tables)',
            'booths' => 'بوثات وأجنحة معارض (Booths)',
            'wall_cladding' => 'تكسيات وديكور شاشات (Wall Paneling)',
            'cabinets' => 'خزائن ودريسنج روم (Cabinets)',
            'decor' => 'ديكورات وتطعيمات خشبية (Decor)',
            'other' => 'أخرى (Other)',
        ];

        return view('admin.ai_ideas.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|string|max:50',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'pinterest_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120',
            'wood_type' => 'nullable|string|max:150',
            'dimensions' => 'nullable|string|max:150',
            'estimated_price_range' => 'nullable|string|max:150',
            'tags' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('ai_design_ideas', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        AiDesignIdea::create($validated);

        return redirect()->route('admin.ai-ideas.index')->with('success', 'تمت إضافة فكرة التصميم إلى بنك أفكار الذكاء الاصطناعي بنجاح!');
    }

    public function edit(AiDesignIdea $aiIdea): View
    {
        $categories = [
            'bedrooms' => 'غرف نوم (Bedrooms)',
            'offices' => 'مكاتب تنفيذية (Offices)',
            'tables' => 'طاولات طعام ومجالس (Tables)',
            'booths' => 'بوثات وأجنحة معارض (Booths)',
            'wall_cladding' => 'تكسيات وديكور شاشات (Wall Paneling)',
            'cabinets' => 'خزائن ودريسنج روم (Cabinets)',
            'decor' => 'ديكورات وتطعيمات خشبية (Decor)',
            'other' => 'أخرى (Other)',
        ];

        return view('admin.ai_ideas.edit', compact('aiIdea', 'categories'));
    }

    public function update(Request $request, AiDesignIdea $aiIdea): RedirectResponse
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'category' => 'required|string|max:50',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'pinterest_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120',
            'wood_type' => 'nullable|string|max:150',
            'dimensions' => 'nullable|string|max:150',
            'estimated_price_range' => 'nullable|string|max:150',
            'tags' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($aiIdea->image && Storage::disk('public')->exists($aiIdea->image)) {
                Storage::disk('public')->delete($aiIdea->image);
            }
            $validated['image'] = $request->file('image')->store('ai_design_ideas', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $aiIdea->update($validated);

        return redirect()->route('admin.ai-ideas.index')->with('success', 'تم تحديث فكرة التصميم بنجاح!');
    }

    public function destroy(AiDesignIdea $aiIdea): RedirectResponse
    {
        if ($aiIdea->image && Storage::disk('public')->exists($aiIdea->image)) {
            Storage::disk('public')->delete($aiIdea->image);
        }

        $aiIdea->delete();

        return redirect()->route('admin.ai-ideas.index')->with('success', 'تم حذف فكرة التصميم من بنك المعرفة بنجاح!');
    }
}
