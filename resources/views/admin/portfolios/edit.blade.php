@extends('admin.layouts.master')

@section('title', 'تعديل المشروع')

@section('page_icon')
    <i class="fa-solid fa-pen-to-square text-wood-600"></i>
@endsection

@section('page_title', 'تعديل المشروع: ' . $portfolio->title_ar)
@section('page_subtitle', 'تحديث بيانات المشروع ومعرض الصور والملفات الهندسية المرفقة')

@section('page_actions')
    <a href="{{ route('admin.portfolios.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>معرض الأعمال</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.portfolios.update', $portfolio->id) }}" enctype="multipart/form-data" class="space-y-6" id="portfolioForm">
    @csrf
    @method('PUT')

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
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $portfolio->title_ar) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
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
                <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $portfolio->title_en) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
            </div>
        </div>

        <!-- Service Link & Client Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Linked Service -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="service_id">
                    الخدمة المرتبطة
                </label>
                <select name="service_id" id="service_id" class="select2 w-full">
                    <option value="">بدون ربط بخدمة</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id', $portfolio->service_id) == $s->id ? 'selected' : '' }}>
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
                <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $portfolio->client_name) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <!-- Location -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="location">
                    موقع المشروع
                </label>
                <input type="text" id="location" name="location" value="{{ old('location', $portfolio->location) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Completion Date & Video URL -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="completion_date">
                    تاريخ الإنجاز والتسليم
                </label>
                <input type="date" id="completion_date" name="completion_date" value="{{ old('completion_date', $portfolio->completion_date?->format('Y-m-d')) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="video_url">
                    رابط فيديو للمشروع (YouTube / Vimeo)
                </label>
                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $portfolio->video_url) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
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
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_ar', $portfolio->description_ar) }}</textarea>
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
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ old('description_en', $portfolio->description_en) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Card 2: Existing Media Attachments -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-photo-film text-wood-600"></i>
            <span>الوسائط الحالية للمشروع</span>
        </h2>

        <!-- Current Cover -->
        <div class="space-y-3">
            <label class="block text-xs font-semibold text-slate-700">صورة الغلاف الحالية</label>
            <div class="flex items-center gap-4">
                <img src="{{ $portfolio->main_image_url }}" class="w-24 h-24 rounded-2xl object-cover ring-2 ring-slate-100 shadow-xs">
                <div class="space-y-1">
                    <input type="file" name="main_image" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
                    <p class="text-[11px] text-slate-400">اختر صورة جديدة إذا كنت ترغب في استبدال الغلاف</p>
                </div>
            </div>
        </div>

        <!-- Current Gallery Images Grid -->
        @if($portfolio->images->count() > 0)
            <div class="space-y-3 pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-800">صور الألبوم الحالية ({{ $portfolio->images->count() }})</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                    @foreach($portfolio->images as $img)
                        <div class="relative group rounded-xl overflow-hidden aspect-square border border-slate-200 bg-slate-100">
                            <img src="{{ asset('storage/' . $img->file_path) }}" class="w-full h-full object-cover">
                            <button type="button" onclick="deleteAttachment({{ $img->id }})" class="absolute top-1 right-1 w-7 h-7 bg-rose-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-xs shadow-md" title="حذف الصورة">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Current PDFs List -->
        @if($portfolio->pdfs->count() > 0)
            <div class="space-y-3 pt-4 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-800">ملفات الـ PDF المرفقة ({{ $portfolio->pdfs->count() }})</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($portfolio->pdfs as $pdf)
                        <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 bg-slate-50">
                            <a href="{{ asset('storage/' . $pdf->file_path) }}" target="_blank" class="flex items-center gap-2 text-xs font-bold text-slate-800 hover:text-wood-600 truncate">
                                <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                <span class="truncate">{{ $pdf->file_name ?: 'وثيقة PDF' }}</span>
                            </a>
                            <button type="button" onclick="deleteAttachment({{ $pdf->id }})" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition" title="حذف الملف">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Add New Gallery Images & PDFs -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    إضافة المزيد من صور الألبوم
                </label>
                <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    إضافة المزيد من ملفات الـ PDF
                </label>
                <input type="file" name="pdf_documents[]" accept=".pdf" multiple class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>

        <!-- Checkboxes -->
        <div class="flex flex-wrap items-center gap-6 pt-3 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $portfolio->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل المشروع في المعرض</span>
            </label>

            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $portfolio->is_featured) ? 'checked' : '' }}
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

<!-- Hidden form for deleting attachments -->
<form id="deleteAttachmentForm" method="POST" action="" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteAttachment(id) {
        if (confirm("هل أنت متأكد من حذف هذا المرفق نهائياً؟")) {
            const form = document.getElementById('deleteAttachmentForm');
            form.action = "{{ url('admin/portfolios/attachments') }}/" + id;
            form.submit();
        }
    }
</script>
@endpush
