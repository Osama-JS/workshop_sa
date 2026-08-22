@extends('admin.layouts.master')

@section('title', 'إضافة خدمة جديدة')

@section('page_icon')
    <i class="fa-solid fa-plus text-wood-600"></i>
@endsection

@section('page_title', 'إضافة خدمة خشبية جديدة')
@section('page_subtitle', 'إدخال تفاصيل الخدمة باللغتين العربية والإنجليزية مع محرر النصوص الغني')

@section('page_actions')
    <a href="{{ route('admin.services.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>قائمة الخدمات</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="space-y-6" id="serviceForm">
    @csrf

    <!-- Card 1: Basic Info & Titles -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-heading text-wood-600"></i>
            <span>عناوين ومعلومات الخدمة</span>
        </h2>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_ar">
                        عنوان الخدمة (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_ar', 'title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="مثال: تصنيع غرف النوم الفاخرة">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_en">
                        عنوان الخدمة (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_en', 'title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="e.g. Luxury Bedroom Manufacturing">
            </div>
        </div>

        <!-- Short Descriptions AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="short_desc_ar">
                        وصف مختصر (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('short_desc_ar', 'short_desc_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="short_desc_ar" name="short_desc_ar" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="وصف سريع يظهر في بطاقات الخدمات في الصفحة الرئيسية">{{ old('short_desc_ar') }}</textarea>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="short_desc_en">
                        وصف مختصر (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('short_desc_en', 'short_desc_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="short_desc_en" name="short_desc_en" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="Short summary for service cards on homepage">{{ old('short_desc_en') }}</textarea>
            </div>
        </div>

        <!-- Rich Text Detailed Content AR & EN (Quill Editor) -->
        <div class="space-y-6 pt-4 border-t border-slate-100">
            <!-- Content AR -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-800">
                        محتوى وتفاصيل الخدمة الكاملة (بالعربي)
                    </label>
                </div>
                <div id="quill_content_ar" class="bg-white"></div>
                <input type="hidden" name="content_ar" id="content_ar" value="{{ old('content_ar') }}">
            </div>

            <!-- Content EN -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-800">
                        محتوى وتفاصيل الخدمة الكاملة (بالإنجليزي)
                    </label>
                </div>
                <div id="quill_content_en" class="bg-white"></div>
                <input type="hidden" name="content_en" id="content_en" value="{{ old('content_en') }}">
            </div>
        </div>
    </div>

    <!-- Card 2: Media, Icon & Settings -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-icons text-wood-600"></i>
            <span>الوسائط والأيقونة والحالة</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Icon Selector -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="icon">
                    أيقونة الخدمة (FontAwesome Name)
                </label>
                <select name="icon" id="icon" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    <option value="couch" {{ old('icon') == 'couch' ? 'selected' : '' }}>🛋️ أثاث وديكور (couch)</option>
                    <option value="bed" {{ old('icon') == 'bed' ? 'selected' : '' }}>🛏️ غرف نوم (bed)</option>
                    <option value="briefcase" {{ old('icon') == 'briefcase' ? 'selected' : '' }}>💼 مكاتب وشركات (briefcase)</option>
                    <option value="store" {{ old('icon') == 'store' ? 'selected' : '' }}>🏬 بوثات معارض (store)</option>
                    <option value="layers" {{ old('icon') == 'layers' ? 'selected' : '' }}>🧱 تكسيات جدارية (layers)</option>
                    <option value="door-closed" {{ old('icon') == 'door-closed' ? 'selected' : '' }}>🚪 أبواب ومداخل (door-closed)</option>
                    <option value="tree" {{ old('icon') == 'tree' ? 'selected' : '' }}>🪵 أخشاب طبيعية (tree)</option>
                    <option value="hammer" {{ old('icon') == 'hammer' ? 'selected' : '' }}>🔨 نجارة وتفصيل (hammer)</option>
                    <option value="ruler-combined" {{ old('icon') == 'ruler-combined' ? 'selected' : '' }}>📐 مقاسات وتصاميم (ruler-combined)</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="sort_order">
                    الترتيب (Sort Order)
                </label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    صورة الغلاف الرئيسية
                </label>
                <input type="file" name="image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="flex flex-wrap items-center gap-6 pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل الخدمة في الموقع</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', false) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تمييز الخدمة في الصفحة الرئيسية</span>
            </label>
        </div>
    </div>

    <!-- Submit Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.services.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Initialize Quill Editors
    let quillAr, quillEn;
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Quill !== 'undefined') {
            quillAr = new Quill('#quill_content_ar', {
                theme: 'snow',
                placeholder: 'اكتب محتوى الخدمة بالعربي...',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            quillEn = new Quill('#quill_content_en', {
                theme: 'snow',
                placeholder: 'Write service detailed content in English...',
                modules: {
                    toolbar: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            // Sync hidden inputs on form submit
            document.getElementById('serviceForm').addEventListener('submit', function() {
                document.getElementById('content_ar').value = quillAr.root.innerHTML;
                document.getElementById('content_en').value = quillEn.root.innerHTML;
            });
        }
    });
</script>
@endpush
