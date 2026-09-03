@extends('admin.layouts.master')

@section('title', 'تعديل السؤال والجواب')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-gold-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>{{ __('admin.menu_ai_hub') }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">تعديل السؤال والجواب #{{ $aiFaq->id }}</h1>
            <p class="text-xs text-slate-500 mt-1">تحديث الإجابة المعتمدة في بنك معلومات الذكاء الاصطناعي</p>
        </div>
        <a href="{{ route('admin.ai-faqs.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة للبنك</span>
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.ai-faqs.update', $aiFaq) }}" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- Question AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-800" for="question_ar">
                        نص السؤال (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('question_ar', 'question_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea name="question_ar" id="question_ar" rows="3" required class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600 transition">{{ old('question_ar', $aiFaq->question_ar) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800" for="question_en">
                    نص السؤال (بالإنجليزي)
                </label>
                <textarea name="question_en" id="question_en" rows="3" dir="ltr" class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600 transition">{{ old('question_en', $aiFaq->question_en) }}</textarea>
            </div>
        </div>

        <!-- Answer AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-800" for="answer_ar">
                        الإجابة المعتمدة (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('answer_ar', 'answer_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea name="answer_ar" id="answer_ar" rows="6" required class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600 transition leading-relaxed">{{ old('answer_ar', $aiFaq->answer_ar) }}</textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800" for="answer_en">
                    الإجابة المعتمدة (بالإنجليزي)
                </label>
                <textarea name="answer_en" id="answer_en" rows="6" dir="ltr" class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600 transition leading-relaxed">{{ old('answer_en', $aiFaq->answer_en) }}</textarea>
            </div>
        </div>

        <!-- Category & Keywords -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800" for="category">
                    التصنيف <span class="text-rose-500">*</span>
                </label>
                <select name="category" id="category" required class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $aiFaq->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-800" for="keywords">
                    الكلمات المفتاحية للمطابقة الذكية (مفصولة بفاصلة)
                </label>
                <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $aiFaq->keywords) }}" placeholder="مثال: غرف نوم, مدة, وقت, متى يجهز, استلام" class="w-full text-xs border border-slate-200 rounded-2xl p-3.5 focus:outline-none focus:border-wood-600">
            </div>
        </div>

        <!-- Sort Order & Active Checkbox -->
        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $aiFaq->is_active) ? 'checked' : '' }} class="w-5 h-5 text-wood-600 rounded-lg focus:ring-wood-500 border-slate-300">
                <label for="is_active" class="text-xs font-bold text-slate-800 cursor-pointer">
                    تفعيل السؤال والإجابة في قاعدة معرفة الذكاء الاصطناعي
                </label>
            </div>

            <div class="flex items-center gap-2">
                <label for="sort_order" class="text-xs font-semibold text-slate-600">الترتيب:</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $aiFaq->sort_order) }}" class="w-20 text-xs border border-slate-200 rounded-xl p-2 text-center font-mono">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-8 py-3 rounded-2xl bg-wood-600 hover:bg-wood-700 text-white font-bold text-xs shadow-lg shadow-wood-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>حفظ التعديلات</span>
            </button>
        </div>
    </form>

</div>
@endsection
