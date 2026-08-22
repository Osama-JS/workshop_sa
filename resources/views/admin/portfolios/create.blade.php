@extends('admin.layouts.master')

@section('title', 'إضافة مشروع جديد')

@section('page_icon')
    <i class="fa-solid fa-plus text-wood-600"></i>
@endsection

@section('page_title', 'إضافة عمل / مشروع لمعرض الأعمال')
@section('page_subtitle', 'رفع صور الألبوم، الفيديوهات، والكتالوجات والمخططات الهندسية PDF')

@section('page_actions')
    <a href="{{ route('admin.portfolios.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>معرض الأعمال</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.portfolios.store') }}" enctype="multipart/form-data" class="space-y-6" id="portfolioForm">
    @csrf

    <!-- Card 1: Main Info -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-file-signature text-wood-600"></i>
            <span>بيانات المشروع الأساسية</span>
        </h2>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_ar">
                        عنوان المشروع (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_ar', 'title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="مثال: تنفيذ ديكورات فيلا فاخرة - حي حطين">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_en">
                        عنوان المشروع (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_en', 'title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="e.g. Luxury Villa Joinery - Hittin District">
            </div>
        </div>

        <!-- Service Link & Client Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Linked Service -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="service_id">
                    الخدمة المرتبطة (لتسهيل الفلترة في الموقع)
                </label>
                <select name="service_id" id="service_id" class="select2 w-full">
                    <option value="">بدون ربط بخدمة</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->title_ar }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Client Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="client_name">
                    اسم العميل / الجهة
                </label>
                <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                    placeholder="مثال: شركة التطوير العقاري">
            </div>

            <!-- Location -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="location">
                    موقع المشروع
                </label>
                <input type="text" id="location" name="location" value="{{ old('location') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                    placeholder="مثال: الرياض، المملكة العربية السعودية">
            </div>
        </div>

        <!-- Completion Date & Video URL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="completion_date">
                    تاريخ الإنجاز والتسليم
                </label>
                <input type="date" id="completion_date" name="completion_date" value="{{ old('completion_date') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="video_url">
                    رابط فيديو للمشروع (YouTube / Vimeo)
                </label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                    placeholder="https://youtube.com/watch?v=...">
            </div>
        </div>

        <!-- Descriptions AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="description_ar">
                        تفاصيل ووصف المشروع (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('description_ar', 'description_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="description_ar" name="description_ar" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_ar') }}</textarea>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="description_en">
                        تفاصيل ووصف المشروع (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('description_en', 'description_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <textarea id="description_en" name="description_en" rows="3"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_en') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Card 2: Multi-Media Uploads (Images & PDFs) -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-photo-film text-wood-600"></i>
            <span>الصور والمرفقات والملفات الهندسية</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Cover -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-slate-700">
                    صورة الغلاف الرئيسية <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="main_image" accept="image/*" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>

            <!-- Gallery Images (Multiple) -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-slate-700">
                    ألبوم صور المشروع (متعدد)
                </label>
                <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
                <p class="text-[11px] text-slate-400">يمكنك اختيار عدة صور في وقت واحد</p>
            </div>

            <!-- PDF Documents (Multiple) -->
            <div class="space-y-2">
                <label class="block text-xs font-semibold text-slate-700">
                    ملفات PDF / مخططات هندسية وكتالوجات
                </label>
                <input type="file" name="pdf_documents[]" accept=".pdf" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
                <p class="text-[11px] text-slate-400">ملفات PDF للتحميل من قِبل الزوار</p>
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="flex flex-wrap items-center gap-6 pt-3 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل المشروع في المعرض</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', false) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تمييز المشروع في الصفحة الرئيسية</span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.portfolios.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection
