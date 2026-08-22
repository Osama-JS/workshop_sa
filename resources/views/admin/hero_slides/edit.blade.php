@extends('admin.layouts.master')

@section('title', 'تعديل شريحة العرض')

@section('page_icon')
    <i class="fa-solid fa-pen-to-square text-wood-600"></i>
@endsection

@section('page_title', 'تعديل شريحة العرض: ' . $slide->title_ar)
@section('page_subtitle', 'تحديث نصوص وأزرار وصورة خلفية الشريحة')

@section('page_actions')
    <a href="{{ route('admin.hero-slides.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>قائمة الشرائح</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.hero-slides.update', $slide->id) }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <!-- Card 1: Texts & Titles -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-heading text-wood-600"></i>
            <span>نصوص وعناوين الشريحة</span>
        </h2>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_ar">
                        العنوان الرئيسي للشريحة (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_ar', 'title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $slide->title_ar) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_en">
                        العنوان الرئيسي للشريحة (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_en', 'title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $slide->title_en) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Subtitles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="subtitle_ar">
                    شارة أو عنوان فرعي علوي (بالعربي)
                </label>
                <input type="text" id="subtitle_ar" name="subtitle_ar" value="{{ old('subtitle_ar', $slide->subtitle_ar) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="subtitle_en">
                    شارة أو عنوان فرعي علوي (بالإنجليزي)
                </label>
                <input type="text" id="subtitle_en" name="subtitle_en" value="{{ old('subtitle_en', $slide->subtitle_en) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Descriptions AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="description_ar">
                    الوصف التفصيلي للشريحة (بالعربي)
                </label>
                <textarea id="description_ar" name="description_ar" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_ar', $slide->description_ar) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="description_en">
                    الوصف التفصيلي للشريحة (بالإنجليزي)
                </label>
                <textarea id="description_en" name="description_en" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_en', $slide->description_en) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Card 2: CTA Buttons & URLs -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-square-arrow-up-right text-wood-600"></i>
            <span>أزرار الإجراء والروابط (CTA Buttons)</span>
        </h2>

        <!-- Primary Button -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="btn_text_ar">
                    نص الزر الأساسي (بالعربي)
                </label>
                <input type="text" id="btn_text_ar" name="btn_text_ar" value="{{ old('btn_text_ar', $slide->btn_text_ar) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="btn_text_en">
                    نص الزر الأساسي (بالإنجليزي)
                </label>
                <input type="text" id="btn_text_en" name="btn_text_en" value="{{ old('btn_text_en', $slide->btn_text_en) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="btn_url">
                    رابط الزر الأساسي
                </label>
                <input type="text" id="btn_url" name="btn_url" value="{{ old('btn_url', $slide->btn_url) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Secondary Button -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-2 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="secondary_btn_text_ar">
                    نص الزر الثانوي (بالعربي)
                </label>
                <input type="text" id="secondary_btn_text_ar" name="secondary_btn_text_ar" value="{{ old('secondary_btn_text_ar', $slide->secondary_btn_text_ar) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="secondary_btn_text_en">
                    نص الزر الثانوي (بالإنجليزي)
                </label>
                <input type="text" id="secondary_btn_text_en" name="secondary_btn_text_en" value="{{ old('secondary_btn_text_en', $slide->secondary_btn_text_en) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="secondary_btn_url">
                    رابط الزر الثانوي
                </label>
                <input type="text" id="secondary_btn_url" name="secondary_btn_url" value="{{ old('secondary_btn_url', $slide->secondary_btn_url) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>
    </div>

    <!-- Card 3: Image & Settings -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-image text-wood-600"></i>
            <span>صورة الخلفية والترتيب</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    صورة خلفية الشريحة (تغيير اختياري)
                </label>
                <div class="flex items-center gap-3">
                    <img src="{{ $slide->image_url }}" class="w-16 h-12 rounded-lg object-cover ring-1 ring-slate-200">
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="sort_order">
                    ترتيب ظهور الشريحة
                </label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Checkbox Active -->
        <div class="pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل هذه الشريحة في العرض</span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.hero-slides.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection
