@extends('admin.layouts.master')

@section('title', 'إضافة رأي عميل')

@section('page_icon')
    <i class="fa-solid fa-plus text-wood-600"></i>
@endsection

@section('page_title', 'إضافة رأي / توصية عميل جديدة')
@section('page_subtitle', 'إدخال بيانات العميل والتقييم والنص باللغتين العربية والإنجليزية')

@section('page_actions')
    <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>قائمة الآراء</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-user-check text-wood-600"></i>
            <span>بيانات العميل والجهة</span>
        </h2>

        <!-- Names AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="client_name_ar">
                        اسم العميل (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('client_name_ar', 'client_name_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="client_name_ar" name="client_name_ar" value="{{ old('client_name_ar') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                    placeholder="مثال: م. فهد الشمري">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="client_name_en">
                        اسم العميل (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('client_name_en', 'client_name_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="client_name_en" name="client_name_en" value="{{ old('client_name_en') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                    placeholder="e.g. Eng. Fahad Al-Shammari">
            </div>
        </div>

        <!-- Position & Company AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="position_ar" name="position_ar" value="{{ old('position_ar') }}" placeholder="المسمى الوظيفي بالعربي (مثال: مدير مشاريع)"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="position_en" name="position_en" value="{{ old('position_en') }}" placeholder="Position in English (e.g. Project Director)"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="company_ar" name="company_ar" value="{{ old('company_ar') }}" placeholder="اسم الشركة أو الجهة بالعربي (مثال: شركة إعمار نجد)"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="company_en" name="company_en" value="{{ old('company_en') }}" placeholder="Company name in English (e.g. Emaar Najd Co.)"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Rating & Avatar -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="rating">
                    التقييم (عدد النجوم) <span class="text-rose-500">*</span>
                </label>
                <select name="rating" id="rating" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    <option value="5" {{ old('rating', 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 نجوم (ممتاز جداً)</option>
                    <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4 نجوم (جيد جداً)</option>
                    <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>⭐⭐⭐ 3 نجوم (جيد)</option>
                    <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>⭐⭐ 2 نجمتان</option>
                    <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>⭐ 1 نجمة</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    صورة / أفاتار العميل
                </label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>

        <!-- Comments AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="comment_ar">
                        نص رأي / تقييم العميل (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('comment_ar', 'comment_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="comment_ar" name="comment_ar" rows="3" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('comment_ar') }}</textarea>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="comment_en">
                        نص رأي / تقييم العميل (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('comment_en', 'comment_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="comment_en" name="comment_en" rows="3" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('comment_en') }}</textarea>
            </div>
        </div>

        <!-- Checkbox Active -->
        <div class="pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل ونشر الرأي في الموقع</span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.testimonials.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection
