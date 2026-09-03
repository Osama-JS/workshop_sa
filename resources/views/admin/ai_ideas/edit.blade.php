@extends('admin.layouts.master')

@section('title', 'تعديل فكرة تصميم الذكاء الاصطناعي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-wood-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>قاعدة المعرفة الذكية</span>
            </div>
            <h1 class="text-xl font-black text-slate-900">تعديل فكرة التصميم: {{ $aiIdea->title_ar }}</h1>
        </div>
        <a href="{{ route('admin.ai-ideas.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة للقائمة</span>
        </a>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('admin.ai-ideas.update', $aiIdea->id) }}" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Category -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2">تصنيف العمل الخشبي <span class="text-rose-500">*</span></label>
                <select name="category" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $aiIdea->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Title AR -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">عنوان التصميم (بالعربية) <span class="text-rose-500">*</span></label>
                <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $aiIdea->title_ar) }}" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Title EN -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-slate-700">عنوان التصميم (بالإنجليزية)</label>
                    <button type="button" onclick="autoTranslate('title_ar', 'title_en')" class="text-[11px] text-wood-600 hover:underline flex items-center gap-1 font-bold">
                        <i class="fa-solid fa-language"></i> ترجمة آلية
                    </button>
                </div>
                <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $aiIdea->title_en) }}" dir="ltr" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Pinterest URL -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2 flex items-center gap-2">
                    <i class="fa-brands fa-pinterest text-red-600"></i>
                    <span>رابط التصميم في بنترست (Pinterest URL)</span>
                </label>
                <input type="url" name="pinterest_url" value="{{ old('pinterest_url', $aiIdea->pinterest_url) }}" dir="ltr" placeholder="https://www.pinterest.com/pin/..." class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Wood Type -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">نوع الخشب المقترح</label>
                <input type="text" name="wood_type" value="{{ old('wood_type', $aiIdea->wood_type) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Dimensions -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">المقاسات والأبعاد التقديرية</label>
                <input type="text" name="dimensions" value="{{ old('dimensions', $aiIdea->dimensions) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Estimated Price Range -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">النطاق السعري التقديري</label>
                <input type="text" name="estimated_price_range" value="{{ old('estimated_price_range', $aiIdea->estimated_price_range) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">الكلمات المفتاحية والوسوم</label>
                <input type="text" name="tags" value="{{ old('tags', $aiIdea->tags) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>

            <!-- Description AR -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-bold text-slate-700 mb-2">تفاصيل ومواصفات التصميم (بالعربية)</label>
                <textarea name="description_ar" id="desc_ar" rows="3" class="w-full border border-slate-200 rounded-xl p-4 text-xs focus:outline-none focus:border-wood-600">{{ old('description_ar', $aiIdea->description_ar) }}</textarea>
            </div>

            <!-- Description EN -->
            <div class="sm:col-span-2">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-slate-700">تفاصيل ومواصفات التصميم (بالإنجليزية)</label>
                    <button type="button" onclick="autoTranslate('desc_ar', 'desc_en')" class="text-[11px] text-wood-600 hover:underline flex items-center gap-1 font-bold">
                        <i class="fa-solid fa-language"></i> ترجمة آلية
                    </button>
                </div>
                <textarea name="description_en" id="desc_en" rows="3" dir="ltr" class="w-full border border-slate-200 rounded-xl p-4 text-xs focus:outline-none focus:border-wood-600">{{ old('description_en', $aiIdea->description_en) }}</textarea>
            </div>

            <!-- Image Upload & Preview -->
            <div class="sm:col-span-2 space-y-2">
                <label class="block text-xs font-bold text-slate-700 mb-2">صورة التصميم أو المخطط</label>
                @if($aiIdea->image)
                    <div class="mb-3">
                        <img src="{{ storage_asset($aiIdea->image) }}" class="w-32 h-24 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-wood-50 file:text-wood-700 hover:file:bg-wood-100">
            </div>

            <!-- Active & Sort Order -->
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $aiIdea->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500">
                    <span class="text-xs font-bold text-slate-700">تفعيل اقتراح هذا التصميم في الشات</span>
                </label>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">الترتيب في الظهور</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $aiIdea->sort_order) }}" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-wood-600">
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.ai-ideas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition">إلغاء</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                <span>حفظ التعديلات</span>
            </button>
        </div>
    </form>
</div>
@endsection
