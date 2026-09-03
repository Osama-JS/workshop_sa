<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiFaq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiFaqController extends Controller
{
    public function index(Request $request): View
    {
        $query = AiFaq::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question_ar', 'like', "%{$search}%")
                  ->orWhere('question_en', 'like', "%{$search}%")
                  ->orWhere('answer_ar', 'like', "%{$search}%")
                  ->orWhere('keywords', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('sort_order', 'asc')->latest()->paginate(15);
        $categories = [
            'general' => 'عام واستفسارات عامة',
            'services' => 'خدمات وتفصيل',
            'bedrooms' => 'غرف نوم ودريسنج',
            'offices' => 'مكاتب وطاولات اجتماعات',
            'booths' => 'بوثات ومعارض',
            'materials' => 'أنواع الأخشاب والمواد',
            'pricing' => 'الأسعار والدفعات',
            'warranty' => 'الضمان والجودة والصيانة',
            'orders' => 'تتبع الطلبات ومواعيد التسليم',
        ];

        return view('admin.ai_faqs.index', compact('faqs', 'categories'));
    }

    public function create(): View
    {
        $categories = [
            'general' => 'عام واستفسارات عامة',
            'services' => 'خدمات وتفصيل',
            'bedrooms' => 'غرف نوم ودريسنج',
            'offices' => 'مكاتب وطاولات اجتماعات',
            'booths' => 'بوثات ومعارض',
            'materials' => 'أنواع الأخشاب والمواد',
            'pricing' => 'الأسعار والدفعات',
            'warranty' => 'الضمان والجودة والصيانة',
            'orders' => 'تتبع الطلبات ومواعيد التسليم',
        ];

        return view('admin.ai_faqs.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'question_ar' => 'required|string',
            'question_en' => 'nullable|string',
            'answer_ar' => 'required|string',
            'answer_en' => 'nullable|string',
            'category' => 'required|string|max:50',
            'keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        AiFaq::create($validated);

        return redirect()->route('admin.ai-faqs.index')->with('success', 'تمت إضافة السؤال والإجابة إلى بنك معلومات المساعد الذكي بنجاح!');
    }

    public function edit(AiFaq $aiFaq): View
    {
        $categories = [
            'general' => 'عام واستفسارات عامة',
            'services' => 'خدمات وتفصيل',
            'bedrooms' => 'غرف نوم ودريسنج',
            'offices' => 'مكاتب وطاولات اجتماعات',
            'booths' => 'بوثات ومعارض',
            'materials' => 'أنواع الأخشاب والمواد',
            'pricing' => 'الأسعار والدفعات',
            'warranty' => 'الضمان والجودة والصيانة',
            'orders' => 'تتبع الطلبات ومواعيد التسليم',
        ];

        return view('admin.ai_faqs.edit', compact('aiFaq', 'categories'));
    }

    public function update(Request $request, AiFaq $aiFaq): RedirectResponse
    {
        $validated = $request->validate([
            'question_ar' => 'required|string',
            'question_en' => 'nullable|string',
            'answer_ar' => 'required|string',
            'answer_en' => 'nullable|string',
            'category' => 'required|string|max:50',
            'keywords' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $aiFaq->update($validated);

        return redirect()->route('admin.ai-faqs.index')->with('success', 'تم تحديث السؤال والإجابة في بنك معلومات الذكاء الاصطناعي بنجاح!');
    }

    public function destroy(AiFaq $aiFaq): RedirectResponse
    {
        $aiFaq->delete();
        return redirect()->route('admin.ai-faqs.index')->with('success', 'تم حذف السؤال والإجابة بنجاح!');
    }
}
