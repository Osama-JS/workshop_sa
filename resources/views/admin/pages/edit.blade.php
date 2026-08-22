@extends('admin.layouts.master')

@section('title', 'تعديل الصفحة')

@section('page_icon')
    <i class="fa-solid fa-pen-to-square text-wood-600"></i>
@endsection

@section('page_title', 'تعديل الصفحة: ' . $page->title_ar)
@section('page_subtitle', 'تحديث محتوى وموضع ظهور الصفحة في الموقع')

@section('page_actions')
    <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>قائمة الصفحات</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.pages.update', $page->id) }}" class="space-y-6" id="pageForm">
    @csrf
    @method('PUT')

    <!-- Card 1: Page Basic Info -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-file-lines text-wood-600"></i>
            <span>بيانات الصفحة والموضع</span>
        </h2>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_ar">
                        عنوان الصفحة (بالعربي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_ar', 'title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_ar" name="title_ar" value="{{ old('title_ar', $page->title_ar) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="title_en">
                        عنوان الصفحة (بالإنجليزي) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="autoTranslate('title_en', 'title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="title_en" name="title_en" value="{{ old('title_en', $page->title_en) }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
            </div>
        </div>

        <!-- Placement & Sort Order -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="placement">
                    مكان ظهور رابط الصفحة في الموقع <span class="text-rose-500">*</span>
                </label>
                <select name="placement" id="placement" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    <option value="both" {{ old('placement', $page->placement) == 'both' ? 'selected' : '' }}>الهيدر والفوتر معاً (Header & Footer)</option>
                    <option value="navbar" {{ old('placement', $page->placement) == 'navbar' ? 'selected' : '' }}>في القائمة العلوية فقط (Navbar Only)</option>
                    <option value="footer" {{ old('placement', $page->placement) == 'footer' ? 'selected' : '' }}>في أسفل الموقع فقط (Footer Only)</option>
                    <option value="none" {{ old('placement', $page->placement) == 'none' ? 'selected' : '' }}>صفحة مباشرة بدون رابط (Direct URL Only)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="sort_order">
                    الترتيب (Sort Order)
                </label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Rich Text Contents AR & EN -->
        <div class="space-y-6 pt-4 border-t border-slate-100">
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">
                    محتوى الصفحة بالكامل (بالعربي)
                </label>
                <div id="quill_content_ar" class="bg-white">{!! old('content_ar', $page->content_ar) !!}</div>
                <input type="hidden" name="content_ar" id="content_ar" value="{{ old('content_ar', $page->content_ar) }}">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">
                    محتوى الصفحة بالكامل (بالإنجليزي)
                </label>
                <div id="quill_content_en" class="bg-white">{!! old('content_en', $page->content_en) !!}</div>
                <input type="hidden" name="content_en" id="content_en" value="{{ old('content_en', $page->content_en) }}">
            </div>
        </div>

        <!-- Checkbox Active -->
        <div class="pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">تفعيل ونشر الصفحة</span>
            </label>
        </div>
    </div>

    <!-- Submit Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.pages.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
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
    let quillAr, quillEn;
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Quill !== 'undefined') {
            quillAr = new Quill('#quill_content_ar', {
                theme: 'snow',
                placeholder: 'اكتب محتوى الصفحة بالعربي...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            quillEn = new Quill('#quill_content_en', {
                theme: 'snow',
                placeholder: 'Write page content in English...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            document.getElementById('pageForm').addEventListener('submit', function() {
                document.getElementById('content_ar').value = quillAr.root.innerHTML;
                document.getElementById('content_en').value = quillEn.root.innerHTML;
            });
        }
    });
</script>
@endpush
