@extends('admin.layouts.master')

@section('title', __('admin.menu_about'))

@section('page_icon')
    <i class="fa-solid fa-address-card text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_about'))
@section('page_subtitle', 'تخصيص قصة الورشة، الرؤية والرسالة، والإحصائيات والأرقام القياسية')

@section('content')
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" class="space-y-8" id="aboutForm">
    @csrf
    @method('PUT')

    <!-- Section 1: Our Story -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-book-open text-wood-600"></i>
                <span>قصة البداية والشغف (Our Story)</span>
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الأول</span>
        </div>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="story_title_ar">
                        عنوان القصة (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('story_title_ar', 'story_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="story_title_ar" name="story[title_ar]" value="{{ old('story.title_ar', $story?->title_ar) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="story_title_en">
                        عنوان القصة (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('story_title_en', 'story_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="story_title_en" name="story[title_en]" value="{{ old('story.title_en', $story?->title_en) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Subtitles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="story_subtitle_ar" name="story[subtitle_ar]" value="{{ old('story.subtitle_ar', $story?->subtitle_ar) }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="story_subtitle_en" name="story[subtitle_en]" value="{{ old('story.subtitle_en', $story?->subtitle_en) }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Rich Text Contents AR & EN -->
        <div class="space-y-6 pt-2">
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى القصة وتاريخ الورشة (بالعربي)</label>
                <div id="quill_story_ar" class="bg-white">{!! old('story.content_ar', $story?->content_ar) !!}</div>
                <input type="hidden" name="story[content_ar]" id="story_content_ar" value="{{ old('story.content_ar', $story?->content_ar) }}">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى القصة وتاريخ الورشة (بالإنجليزي)</label>
                <div id="quill_story_en" class="bg-white">{!! old('story.content_en', $story?->content_en) !!}</div>
                <input type="hidden" name="story[content_en]" id="story_content_en" value="{{ old('story.content_en', $story?->content_en) }}">
            </div>
        </div>

        <!-- Story Image -->
        <div class="pt-4 border-t border-slate-100 flex items-center gap-5">
            @if($story?->image)
                <img src="{{ asset('storage/' . $story->image) }}" class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200">
            @endif
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-700">صورة جانبية لقسم القصة</label>
                <input type="file" name="story[image]" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>
    </div>

    <!-- Section 2: Vision & Mission -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bullseye text-wood-600"></i>
                <span>الرؤية والرسالة والقيم (Vision & Mission)</span>
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الثاني</span>
        </div>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="vision_title_ar">
                        عنوان الرؤية والرسالة (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('vision_title_ar', 'vision_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="vision_title_ar" name="vision[title_ar]" value="{{ old('vision.title_ar', $vision?->title_ar) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="vision_title_en">
                        عنوان الرؤية والرسالة (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('vision_title_en', 'vision_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="vision_title_en" name="vision[title_en]" value="{{ old('vision.title_en', $vision?->title_en) }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Rich Text Contents AR & EN -->
        <div class="space-y-6 pt-2">
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى الرؤية والرسالة (بالعربي)</label>
                <div id="quill_vision_ar" class="bg-white">{!! old('vision.content_ar', $vision?->content_ar) !!}</div>
                <input type="hidden" name="vision[content_ar]" id="vision_content_ar" value="{{ old('vision.content_ar', $vision?->content_ar) }}">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى الرؤية والرسالة (بالإنجليزي)</label>
                <div id="quill_vision_en" class="bg-white">{!! old('vision.content_en', $vision?->content_en) !!}</div>
                <input type="hidden" name="vision[content_en]" id="vision_content_en" value="{{ old('vision.content_en', $vision?->content_en) }}">
            </div>
        </div>
    </div>

    <!-- Section 3: Numbers & Statistics (Milestones) -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-wood-600"></i>
                <span>الأرقام والإنجازات القياسية (Counters & Statistics)</span>
            </h2>
            <button type="button" onclick="addCounterRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-wood-50 hover:bg-wood-100 text-wood-800 text-xs font-bold rounded-lg transition">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>إضافة رقم قياسي</span>
            </button>
        </div>

        <div id="countersContainer" class="space-y-3">
            @php
                $counters = $stats?->meta_data ?: [
                    ['number' => '15+', 'label_ar' => 'سنوات من الخبرة والإتقان', 'label_en' => 'Years of Master Craftsmanship'],
                    ['number' => '450+', 'label_ar' => 'مشروع فاخر تم تسليمه', 'label_en' => 'Luxury Projects Delivered'],
                    ['number' => '98%', 'label_ar' => 'نسبة رضا العملاء والشركات', 'label_en' => 'Customer Satisfaction Rate'],
                    ['number' => '30+', 'label_ar' => 'حرفي وفني نجارة محترف', 'label_en' => 'Master Wood Artisans'],
                ];
            @endphp

            @foreach($counters as $idx => $c)
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center p-3 rounded-xl border border-slate-200 bg-slate-50 counter-row">
                    <div class="sm:col-span-3">
                        <label class="block text-[10px] font-bold text-slate-500 mb-1">الرقم / النسبة</label>
                        <input type="text" name="stats[counters][{{ $idx }}][number]" value="{{ $c['number'] ?? '' }}" placeholder="مثال: 15+ أو 98%" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-[10px] font-bold text-slate-500 mb-1">الوصف (بالعربي)</label>
                        <input type="text" name="stats[counters][{{ $idx }}][label_ar]" value="{{ $c['label_ar'] ?? '' }}" placeholder="سنوات من الخبرة" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                    </div>
                    <div class="sm:col-span-4">
                        <label class="block text-[10px] font-bold text-slate-500 mb-1">الوصف (بالإنجليزي)</label>
                        <input type="text" name="stats[counters][{{ $idx }}][label_en]" value="{{ $c['label_en'] ?? '' }}" placeholder="Years of Experience"
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                    </div>
                    <div class="sm:col-span-1 text-center pt-3 sm:pt-4">
                        <button type="button" onclick="this.closest('.counter-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition" title="حذف">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Submit Action -->
    <div class="flex items-center justify-end">
        <button type="submit" class="px-8 py-3 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition flex items-center gap-2">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>حفظ وتحديث صفحة من نحن</span>
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    let quillStoryAr, quillStoryEn, quillVisionAr, quillVisionEn;
    let counterIndex = {{ count($counters) }};

    function addCounterRow() {
        const container = document.getElementById('countersContainer');
        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-3 items-center p-3 rounded-xl border border-slate-200 bg-slate-50 counter-row';
        row.innerHTML = `
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">الرقم / النسبة</label>
                <input type="text" name="stats[counters][${counterIndex}][number]" placeholder="مثال: 50+" required
                    class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
            </div>
            <div class="sm:col-span-4">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">الوصف (بالعربي)</label>
                <input type="text" name="stats[counters][${counterIndex}][label_ar]" placeholder="الوصف بالعربي" required
                    class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
            </div>
            <div class="sm:col-span-4">
                <label class="block text-[10px] font-bold text-slate-500 mb-1">الوصف (بالإنجليزي)</label>
                <input type="text" name="stats[counters][${counterIndex}][label_en]" placeholder="Description in English"
                    class="w-full bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
            </div>
            <div class="sm:col-span-1 text-center pt-3 sm:pt-4">
                <button type="button" onclick="this.closest('.counter-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition" title="حذف">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        counterIndex++;
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Quill !== 'undefined') {
            const modules = {
                toolbar: [
                    [{ 'header': [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['clean']
                ]
            };

            quillStoryAr = new Quill('#quill_story_ar', { theme: 'snow', placeholder: 'اكتب قصة الورشة...', modules });
            quillStoryEn = new Quill('#quill_story_en', { theme: 'snow', placeholder: 'Write workshop story...', modules });
            quillVisionAr = new Quill('#quill_vision_ar', { theme: 'snow', placeholder: 'اكتب الرؤية والرسالة...', modules });
            quillVisionEn = new Quill('#quill_vision_en', { theme: 'snow', placeholder: 'Write vision & mission...', modules });

            document.getElementById('aboutForm').addEventListener('submit', function() {
                document.getElementById('story_content_ar').value = quillStoryAr.root.innerHTML;
                document.getElementById('story_content_en').value = quillStoryEn.root.innerHTML;
                document.getElementById('vision_content_ar').value = quillVisionAr.root.innerHTML;
                document.getElementById('vision_content_en').value = quillVisionEn.root.innerHTML;
            });
        }
    });
</script>
@endpush
