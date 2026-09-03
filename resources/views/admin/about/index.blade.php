@extends('admin.layouts.master')

@section('title', __('admin.menu_about'))

@section('page_icon')
    <i class="fa-solid fa-address-card text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_about'))
@section('page_subtitle', 'تخصيص قسم من نحن، قصة الورشة، الرؤية والرسالة، قيمنا، والإحصائيات القياسية')

@section('content')
<form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data" class="space-y-8" id="aboutForm">
    @csrf
    @method('PUT')

    <!-- =========================================================================
         SECTION 1: ABOUT US (من نحن والتعريف بالورشة)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-building text-wood-600"></i>
                <span>قسم من نحن (About Us Overview)</span>
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الأول (أعلى الصفحة)</span>
        </div>

        <!-- Titles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="about_title_ar">
                        عنوان قسم من نحن (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('about_title_ar', 'about_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="about_title_ar" name="about[title_ar]" value="{{ old('about.title_ar', $about?->title_ar ?: 'من نحن - ورشة أرتيزان للأعمال الخشبية الفاخرة') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="about_title_en">
                        عنوان قسم من نحن (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('about_title_en', 'about_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="about_title_en" name="about[title_en]" value="{{ old('about.title_en', $about?->title_en ?: 'About Us - Artisan Luxury Woodworking Workshop') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Subtitles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="about_subtitle_ar">العنوان الفرعي (بالعربي)</label>
                <input type="text" id="about_subtitle_ar" name="about[subtitle_ar]" value="{{ old('about.subtitle_ar', $about?->subtitle_ar ?: 'صرح سعودي رائد في هندسة وتفصيل الخشب الطبيعي والديكورات الراقية') }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="about_subtitle_en">العنوان الفرعي (بالإنجليزي)</label>
                <input type="text" id="about_subtitle_en" name="about[subtitle_en]" value="{{ old('about.subtitle_en', $about?->subtitle_en ?: 'A leading Saudi powerhouse in bespoke timber engineering and luxury interior decor') }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Rich Text Contents AR & EN -->
        <div class="space-y-6 pt-2">
            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى وتفاصيل من نحن (بالعربي)</label>
                <div id="quill_about_ar" class="bg-white">{!! old('about.content_ar', $about?->content_ar ?: '<p>نحن في ورشة أرتيزان نفخر بكوننا أحد أبرز الصروح المتخصصة في النجارة المعمارية وتفصيل الأثاث الخشبي الفاخر في المملكة العربية السعودية. نعتمد على كوادر فنية وحرفيين ذوي خبرات عريقة، ونوظف أحدث ما توصلت إليه التكنولوجيا لنلبي تطلعات القصور والمكاتب والمعارض والمشاريع التجارية الكبرى بدقة متناهية وجودة تفوق التوقعات.</p>') !!}</div>
                <input type="hidden" name="about[content_ar]" id="about_content_ar" value="{{ old('about.content_ar', $about?->content_ar) }}">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-800 mb-2">محتوى وتفاصيل من نحن (بالإنجليزي)</label>
                <div id="quill_about_en" class="bg-white">{!! old('about.content_en', $about?->content_en ?: '<p>At Artisan Workshop, we take immense pride in being one of the premier workshops dedicated to architectural joinery and luxury custom woodwork in Saudi Arabia. Combining master artisan hands with state-of-the-art CNC technology, we craft bespoke furniture and timber interiors that exceed expectations for residences, commercial spaces, and exhibition stands.</p>') !!}</div>
                <input type="hidden" name="about[content_en]" id="about_content_en" value="{{ old('about.content_en', $about?->content_en) }}">
            </div>
        </div>

        <!-- Image for About Us Section -->
        <div class="pt-4 border-t border-slate-100 flex items-center gap-5">
            @if($about?->image)
                <img src="{{ storage_asset($about->image) }}" class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200">
            @endif
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-700">صورة جانبية لقسم من نحن</label>
                <input type="file" name="about[image]" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 2: OUR STORY (قصة البداية والشغف)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-book-open text-wood-600"></i>
                <span>قصة البداية والشغف (Our Story)</span>
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الثاني</span>
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
                <img src="{{ storage_asset($story->image) }}" class="w-16 h-16 rounded-xl object-cover ring-2 ring-slate-200">
            @endif
            <div class="space-y-1">
                <label class="block text-xs font-semibold text-slate-700">صورة جانبية لقسم القصة</label>
                <input type="file" name="story[image]" accept="image/*" class="text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 3: VISION & MISSION (الرؤية والرسالة)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-bullseye text-wood-600"></i>
                <span>الرؤية والرسالة (Vision & Mission)</span>
            </h2>
            <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الثالث</span>
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

        <!-- Subtitles AR & EN -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="vision_subtitle_ar" name="vision[subtitle_ar]" value="{{ old('vision.subtitle_ar', $vision?->subtitle_ar) }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="vision_subtitle_en" name="vision[subtitle_en]" value="{{ old('vision.subtitle_en', $vision?->subtitle_en) }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
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

    <!-- =========================================================================
         SECTION 4: OUR CORE VALUES (قيمنا ومبادئ عملنا)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-gem text-wood-600"></i>
                    <span>قيمنا ومبادئنا (Our Core Values)</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">تظهر كبطاقات فاخرة أنيقة أسفل قسم الرؤية والرسالة مباشرة في صفحة من نحن</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الرابع</span>
                <button type="button" onclick="addValueRow()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>إضافة قيمة جديدة</span>
                </button>
            </div>
        </div>

        <!-- Section Title & Subtitle -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="values_title_ar">
                        عنوان قسم القيم (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('values_title_ar', 'values_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="values_title_ar" name="values[title_ar]" value="{{ old('values.title_ar', $values?->title_ar ?: 'قيمنا ومبادئ عملنا الراسخة') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="values_title_en">
                        عنوان قسم القيم (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('values_title_en', 'values_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="values_title_en" name="values[title_en]" value="{{ old('values.title_en', $values?->title_en ?: 'Our Enduring Core Values') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="values_subtitle_ar" name="values[subtitle_ar]" value="{{ old('values.subtitle_ar', $values?->subtitle_ar ?: 'المبادئ السامية التي تحكم كل مرحلة في ورشتنا') }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="values_subtitle_en" name="values[subtitle_en]" value="{{ old('values.subtitle_en', $values?->subtitle_en ?: 'The guiding principles behind every creation in our workshop') }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Dynamic Values Cards Repeater -->
        <div id="valuesContainer" class="space-y-4 pt-2">
            @php
                $valueItems = $values?->meta_data ?: [
                    [
                        'title_ar' => 'الإتقان والجودة المطلقة',
                        'title_en' => 'Uncompromising Quality',
                        'icon' => 'fa-solid fa-gem',
                        'desc_ar' => 'اختيار أرقى أخشاب الزان، البلوط، والجوز المعالج بأعلى المعايير العالمية لضمان الديمومة والفخامة.',
                        'desc_en' => 'Selecting the finest seasoned oak, walnut, and beech wood adhering to international luxury standards.'
                    ],
                    [
                        'title_ar' => 'الحرفية والابتكار',
                        'title_en' => 'Craftsmanship & Innovation',
                        'icon' => 'fa-solid fa-wand-magic-sparkles',
                        'desc_ar' => 'المزج الخلاق بين المهارة اليدوية التراثية الأصيلة ودقة الماكينات الرقمية الحديثة لابتكار تفاصيل فريدة.',
                        'desc_en' => 'Seamlessly combining traditional artisanal woodwork with state-of-the-art CNC machining precision.'
                    ],
                    [
                        'title_ar' => 'الالتزام والشفافية',
                        'title_en' => 'Commitment & Trust',
                        'icon' => 'fa-solid fa-handshake-simple',
                        'desc_ar' => 'احترام المواعيد المحددة للتسليم والوضوح الكامل في كل مرحلة من مراحل التصميم والتركيب.',
                        'desc_en' => 'Strict adherence to project timelines and absolute transparency throughout design, manufacturing, and installation.'
                    ],
                    [
                        'title_ar' => 'الاستدامة والأصالة',
                        'title_en' => 'Sustainability & Heritage',
                        'icon' => 'fa-solid fa-tree',
                        'desc_ar' => 'الاعتماد على مصادر أخشاب مستدامة ومعتمدة بيئياً، والحفاظ على أصالة الحرفة السعودية العريقة.',
                        'desc_en' => 'Sourcing certified eco-friendly timber and preserving the authentic Saudi craft heritage with timeless appeal.'
                    ]
                ];
            @endphp

            @foreach($valueItems as $vIdx => $val)
                <div class="p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 value-row relative group">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-wood-100 text-wood-800 flex items-center justify-center text-xs font-bold font-mono">{{ $vIdx + 1 }}</span>
                            <span class="text-xs font-bold text-slate-800">بطاقة القيمة</span>
                        </div>
                        <button type="button" onclick="this.closest('.value-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف القيمة">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Icon Picker Field -->
                        <div class="sm:col-span-3 space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-700">الأيقونة المحددة (FA)</label>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="values[items][{{ $vIdx }}][icon]" id="val_icon_input_{{ $vIdx }}" value="{{ $val['icon'] ?? 'fa-solid fa-gem' }}">
                                <button type="button" onclick="openIconPicker('val_icon_input_{{ $vIdx }}', 'val_icon_preview_{{ $vIdx }}')"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <div id="val_icon_preview_{{ $vIdx }}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                            <i class="{{ $val['icon'] ?? 'fa-solid fa-gem' }}"></i>
                                        </div>
                                        <span class="text-xs font-mono text-slate-600 font-semibold" id="val_icon_text_{{ $vIdx }}">اختر أيقونة</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Title AR & EN -->
                        <div class="sm:col-span-4 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">اسم القيمة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('val_title_ar_{{ $vIdx }}', 'val_title_en_{{ $vIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="val_title_ar_{{ $vIdx }}" name="values[items][{{ $vIdx }}][title_ar]" value="{{ $val['title_ar'] ?? '' }}" placeholder="مثال: الإتقان والجودة" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                        </div>

                        <div class="sm:col-span-5 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">اسم القيمة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('val_title_en_{{ $vIdx }}', 'val_title_ar_{{ $vIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="val_title_en_{{ $vIdx }}" name="values[items][{{ $vIdx }}][title_en]" value="{{ $val['title_en'] ?? '' }}" placeholder="e.g. Uncompromising Quality"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                        </div>
                    </div>

                    <!-- Description AR & EN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">وصف وشرح القيمة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('val_desc_ar_{{ $vIdx }}', 'val_desc_en_{{ $vIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="val_desc_ar_{{ $vIdx }}" name="values[items][{{ $vIdx }}][desc_ar]" rows="2" placeholder="اكتب نبذة أو شرحاً موجزاً لهذه القيمة..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $val['desc_ar'] ?? '' }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">وصف وشرح القيمة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('val_desc_en_{{ $vIdx }}', 'val_desc_ar_{{ $vIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="val_desc_en_{{ $vIdx }}" name="values[items][{{ $vIdx }}][desc_en]" rows="2" placeholder="Write a brief explanation for this core value..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $val['desc_en'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 5: WHY CHOOSE US (لماذا تختارنا)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-crown text-wood-600"></i>
                <span>قسم لماذا تختارنا (Why Choose Us)</span>
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم الخامس</span>
                <button type="button" onclick="addWhyUsRow()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>إضافة ميزة جديدة</span>
                </button>
            </div>
        </div>

        <!-- Section Title & Subtitle -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="why_us_title_ar">
                        عنوان قسم لماذا تختارنا (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('why_us_title_ar', 'why_us_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="why_us_title_ar" name="why_us[title_ar]" value="{{ old('why_us.title_ar', $whyUs?->title_ar ?: 'لماذا تختار ورشة أرتيزان للأعمال الخشبية؟') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="why_us_title_en">
                        عنوان قسم لماذا تختارنا (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('why_us_title_en', 'why_us_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="why_us_title_en" name="why_us[title_en]" value="{{ old('why_us.title_en', $whyUs?->title_en ?: 'Why Choose Artisan Woodworking Workshop?') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="why_us_subtitle_ar" name="why_us[subtitle_ar]" value="{{ old('why_us.subtitle_ar', $whyUs?->subtitle_ar ?: 'معايير ملكية تفوق التوقعات وتمنحك راحة البال التامة') }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="why_us_subtitle_en" name="why_us[subtitle_en]" value="{{ old('why_us.subtitle_en', $whyUs?->subtitle_en ?: 'Royal standards that exceed expectations and ensure total peace of mind') }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Dynamic Why Us Cards Repeater -->
        <div id="whyUsContainer" class="space-y-4 pt-2">
            @php
                $whyItems = $whyUs?->meta_data ?: [
                    [
                        'title_ar' => 'أخشاب طبيعية فاخرة 100%',
                        'title_en' => '100% Premium Solid Hardwood',
                        'icon' => 'fa-solid fa-tree',
                        'desc_ar' => 'نستورد أفخر أنواع خشب الجوز الأمريكي، البلوط، والزان المجفف حرارياً لمقاومة الرطوبة والتمدد.',
                        'desc_en' => 'We source finest kiln-dried American walnut, oak, and beech resistant to warping and humidity.'
                    ],
                    [
                        'title_ar' => 'دقة تصنيع متناهية بالـ CNC',
                        'title_en' => 'Sub-Millimeter CNC Precision',
                        'icon' => 'fa-solid fa-microchip',
                        'desc_ar' => 'استخدام مكائن الحفر والقص الرقمي الأحدث عالمياً لضمان تعشيق مثالي وتفاصيل غاية في الدقة.',
                        'desc_en' => 'Employing cutting-edge 5-axis CNC machining for seamless joinery and intricate detailing.'
                    ],
                    [
                        'title_ar' => 'ضمان ذهبي شامل حتى 10 سنوات',
                        'title_en' => '10-Year Comprehensive Warranty',
                        'icon' => 'fa-solid fa-shield-halved',
                        'desc_ar' => 'نمنح عملاءنا ضماناً حقيقياً يغطي جودة الأخشاب، الهيكل الداخلي، والمفصلات والإكسسوارات الألمانية.',
                        'desc_en' => 'True warranty covering wood structural integrity, finishes, and premium German hardware.'
                    ],
                    [
                        'title_ar' => 'التزام صارم بجدول التسليم',
                        'title_en' => 'Guaranteed Delivery Timelines',
                        'icon' => 'fa-solid fa-clock-rotate-left',
                        'desc_ar' => 'إدارة مشاريع احترافية تضمن تسليم وتركيب أعمالك في الموعد المحدد دون أي تأخير.',
                        'desc_en' => 'Rigorous project management ensuring on-time manufacturing and turnkey installation.'
                    ]
                ];
            @endphp

            @foreach($whyItems as $wIdx => $item)
                <div class="p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 why-us-row relative group">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-wood-100 text-wood-800 flex items-center justify-center text-xs font-bold font-mono">{{ $wIdx + 1 }}</span>
                            <span class="text-xs font-bold text-slate-800">ميزة لماذا تختارنا</span>
                        </div>
                        <button type="button" onclick="this.closest('.why-us-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Icon Picker Field -->
                        <div class="sm:col-span-3 space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-700">الأيقونة (FA)</label>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="why_us[items][{{ $wIdx }}][icon]" id="why_icon_input_{{ $wIdx }}" value="{{ $item['icon'] ?? 'fa-solid fa-crown' }}">
                                <button type="button" onclick="openIconPicker('why_icon_input_{{ $wIdx }}', 'why_icon_preview_{{ $wIdx }}')"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <div id="why_icon_preview_{{ $wIdx }}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                            <i class="{{ $item['icon'] ?? 'fa-solid fa-crown' }}"></i>
                                        </div>
                                        <span class="text-xs font-mono text-slate-600 font-semibold" id="why_icon_text_{{ $wIdx }}">اختر أيقونة</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Title AR & EN -->
                        <div class="sm:col-span-4 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">عنوان الميزة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('why_title_ar_{{ $wIdx }}', 'why_title_en_{{ $wIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="why_title_ar_{{ $wIdx }}" name="why_us[items][{{ $wIdx }}][title_ar]" value="{{ $item['title_ar'] ?? '' }}" placeholder="مثال: أخشاب طبيعية 100%" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                        </div>

                        <div class="sm:col-span-5 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">عنوان الميزة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('why_title_en_{{ $wIdx }}', 'why_title_ar_{{ $wIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="why_title_en_{{ $wIdx }}" name="why_us[items][{{ $wIdx }}][title_en]" value="{{ $item['title_en'] ?? '' }}" placeholder="e.g. 100% Premium Solid Hardwood"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                        </div>
                    </div>

                    <!-- Description AR & EN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">شرح وتفاصيل الميزة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('why_desc_ar_{{ $wIdx }}', 'why_desc_en_{{ $wIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="why_desc_ar_{{ $wIdx }}" name="why_us[items][{{ $wIdx }}][desc_ar]" rows="2" placeholder="اكتب تفاصيل وشرحاً لهذه الميزة..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $item['desc_ar'] ?? '' }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">شرح وتفاصيل الميزة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('why_desc_en_{{ $wIdx }}', 'why_desc_ar_{{ $wIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="why_desc_en_{{ $wIdx }}" name="why_us[items][{{ $wIdx }}][desc_en]" rows="2" placeholder="Write details for this feature..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $item['desc_en'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 6: HOW WE WORK / PROCESS (كيف نعمل ومراحل التصنيع)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-diagram-project text-wood-600"></i>
                <span>قسم كيف نعمل (How We Work / Workflow)</span>
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم السادس</span>
                <button type="button" onclick="addProcessRow()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                    <i class="fa-solid fa-plus text-[11px]"></i>
                    <span>إضافة خطوة عمل جديدة</span>
                </button>
            </div>
        </div>

        <!-- Section Title & Subtitle -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="process_title_ar">
                        عنوان قسم كيف نعمل (بالعربي)
                    </label>
                    <button type="button" onclick="autoTranslate('process_title_ar', 'process_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="process_title_ar" name="process[title_ar]" value="{{ old('process.title_ar', $process?->title_ar ?: 'كيف نعمل - مراحل تحويل فكرتك إلى تحفة خشبية') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold text-slate-700" for="process_title_en">
                        عنوان قسم كيف نعمل (بالإنجليزي)
                    </label>
                    <button type="button" onclick="autoTranslate('process_title_en', 'process_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                        <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                    </button>
                </div>
                <input type="text" id="process_title_en" name="process[title_en]" value="{{ old('process.title_en', $process?->title_en ?: 'How We Work - The Journey from Vision to Masterpiece') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <input type="text" id="process_subtitle_ar" name="process[subtitle_ar]" value="{{ old('process.subtitle_ar', $process?->subtitle_ar ?: 'منهجية عمل هندسية مدروسة تضمن أرقى مستويات الجودة والإتقان') }}" placeholder="العنوان الفرعي بالعربي"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
            <div>
                <input type="text" id="process_subtitle_en" name="process[subtitle_en]" value="{{ old('process.subtitle_en', $process?->subtitle_en ?: 'A structured engineering workflow delivering exquisite craftsmanship and perfection') }}" placeholder="Subtitle in English"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
            </div>
        </div>

        <!-- Dynamic Process Steps Repeater -->
        <div id="processContainer" class="space-y-4 pt-2">
            @php
                $processSteps = $process?->meta_data ?: [
                    [
                        'step_number' => '01',
                        'title_ar' => 'الاستشارة والرفع المساحي',
                        'title_en' => 'Consultation & Site Survey',
                        'icon' => 'fa-solid fa-compass-drafting',
                        'desc_ar' => 'جلسة استشارية لفهم تطلعاتك مع زيارة ميدانية لرفع المقاسات الهندسية بدقة تامة.',
                        'desc_en' => 'Initial consultation to align on vision followed by laser-accurate site dimension surveys.'
                    ],
                    [
                        'step_number' => '02',
                        'title_ar' => 'التصميم الهندسي والـ 3D',
                        'title_en' => '3D Architectural Modeling',
                        'icon' => 'fa-solid fa-cubes',
                        'desc_ar' => 'إعداد مخططات تفصيلية ورسومات ثلاثية الأبعاد واقعية تمكّنك من رؤية النتيجة قبل بدء التصنيع.',
                        'desc_en' => 'Developing realistic 3D renders and shop drawings so you preview every detail prior to fabrication.'
                    ],
                    [
                        'step_number' => '03',
                        'title_ar' => 'اختيار وتجهيز الأخشاب',
                        'title_en' => 'Timber Selection & Prep',
                        'icon' => 'fa-solid fa-tree',
                        'desc_ar' => 'فرز ألواح الخشب الطبيعي بعناية ومعالجتها بالحرارة والزيوت لضمان استقرارها الدائم.',
                        'desc_en' => 'Hand-selecting prime timber slabs and conditioning them for maximum longevity.'
                    ],
                    [
                        'step_number' => '04',
                        'title_ar' => 'التصنيع والحرفية اليدوية',
                        'title_en' => 'CNC Machining & Handcraft',
                        'icon' => 'fa-solid fa-hammer',
                        'desc_ar' => 'التنفيذ الدقيق بمكائن CNC المتطورة مع لمسات الحفر والتعشيق اليدوي التراثي بأيدي أمهر المعلمين.',
                        'desc_en' => 'High-precision CNC cutting harmonized with master artisanal hand carving and traditional joinery.'
                    ],
                    [
                        'step_number' => '05',
                        'title_ar' => 'الدهان والتشطيب الإيطالي',
                        'title_en' => 'Italian Finishing & Coating',
                        'icon' => 'fa-solid fa-paint-roller',
                        'desc_ar' => 'غرف دهان معزولة حرارياً لتطبيق طبقات البولي يوريثان والدهانات الإيطالية المقاومة للخدش والحرارة.',
                        'desc_en' => 'Dust-free spray booths applying premium Italian polyurethane coatings resistant to scratches.'
                    ],
                    [
                        'step_number' => '06',
                        'title_ar' => 'التوصيل والتركيب المتقن',
                        'title_en' => 'Delivery & Installation',
                        'icon' => 'fa-solid fa-truck-ramp-box',
                        'desc_ar' => 'تغليف احترافي ونقل آمن، مع تركيب هندسي محكم في موقعك بإشراف مهندسي الجودة والتسليم النهائي.',
                        'desc_en' => 'Protective packaging, secure logistics, and meticulous on-site installation overseen by QA engineers.'
                    ]
                ];
            @endphp

            @foreach($processSteps as $pIdx => $step)
                <div class="p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 process-row relative group">
                    <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-wood-600 text-white flex items-center justify-center text-xs font-bold font-mono">{{ $step['step_number'] ?? sprintf('%02d', $pIdx + 1) }}</span>
                            <span class="text-xs font-bold text-slate-800">خطوة العمل والمرحلة</span>
                        </div>
                        <button type="button" onclick="this.closest('.process-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف الخطوة">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                        <!-- Step Number & Icon Picker Field -->
                        <div class="sm:col-span-2 space-y-1">
                            <label class="block text-[11px] font-bold text-slate-700">رقم الخطوة</label>
                            <input type="text" name="process[steps][{{ $pIdx }}][step_number]" value="{{ $step['step_number'] ?? sprintf('%02d', $pIdx + 1) }}" placeholder="01" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono font-bold text-center focus:outline-none focus:border-wood-500">
                        </div>

                        <div class="sm:col-span-3 space-y-1.5">
                            <label class="block text-[11px] font-bold text-slate-700">الأيقونة (FA)</label>
                            <div class="flex items-center gap-2">
                                <input type="hidden" name="process[steps][{{ $pIdx }}][icon]" id="process_icon_input_{{ $pIdx }}" value="{{ $step['icon'] ?? 'fa-solid fa-compass-drafting' }}">
                                <button type="button" onclick="openIconPicker('process_icon_input_{{ $pIdx }}', 'process_icon_preview_{{ $pIdx }}')"
                                    class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <div id="process_icon_preview_{{ $pIdx }}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                            <i class="{{ $step['icon'] ?? 'fa-solid fa-compass-drafting' }}"></i>
                                        </div>
                                        <span class="text-xs font-mono text-slate-600 font-semibold" id="process_icon_text_{{ $pIdx }}">اختر أيقونة</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Title AR & EN -->
                        <div class="sm:col-span-3 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">اسم المرحلة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('process_title_ar_{{ $pIdx }}', 'process_title_en_{{ $pIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="process_title_ar_{{ $pIdx }}" name="process[steps][{{ $pIdx }}][title_ar]" value="{{ $step['title_ar'] ?? '' }}" placeholder="مثال: الاستشارة والرفع المساحي" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                        </div>

                        <div class="sm:col-span-4 space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">اسم المرحلة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('process_title_en_{{ $pIdx }}', 'process_title_ar_{{ $pIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="process_title_en_{{ $pIdx }}" name="process[steps][{{ $pIdx }}][title_en]" value="{{ $step['title_en'] ?? '' }}" placeholder="e.g. Consultation & Site Survey"
                                class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                        </div>
                    </div>

                    <!-- Description AR & EN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">شرح خطوات المرحلة (بالعربي)</label>
                                <button type="button" onclick="autoTranslate('process_desc_ar_{{ $pIdx }}', 'process_desc_en_{{ $pIdx }}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="process_desc_ar_{{ $pIdx }}" name="process[steps][{{ $pIdx }}][desc_ar]" rows="2" placeholder="اكتب تفاصيل هذه المرحلة..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $step['desc_ar'] ?? '' }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <label class="block text-[11px] font-bold text-slate-700">شرح خطوات المرحلة (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('process_desc_en_{{ $pIdx }}', 'process_desc_ar_{{ $pIdx }}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="process_desc_en_{{ $pIdx }}" name="process[steps][{{ $pIdx }}][desc_en]" rows="2" placeholder="Write details for this workflow step..."
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed">{{ $step['desc_en'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 7: NUMBERS & STATISTICS (الأرقام والإنجازات القياسية)
         ========================================================================= -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-wood-600"></i>
                <span>الأرقام والإنجازات القياسية (Counters & Statistics)</span>
            </h2>
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold px-2.5 py-1 bg-wood-50 text-wood-800 rounded-lg">القسم السابع</span>
                <button type="button" onclick="addCounterRow()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-wood-50 hover:bg-wood-100 text-wood-800 text-xs font-bold rounded-lg transition cursor-pointer">
                    <i class="fa-solid fa-plus text-[10px]"></i>
                    <span>إضافة رقم قياسي</span>
                </button>
            </div>
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
                        <button type="button" onclick="this.closest('.counter-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Submit Action -->
    <div class="flex items-center justify-end">
        <button type="submit" class="px-8 py-3 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition flex items-center gap-2 cursor-pointer">
            <i class="fa-solid fa-floppy-disk"></i>
            <span>حفظ وتحديث صفحة من نحن</span>
        </button>
    </div>
</form>

<!-- =========================================================================
     FONTAWESOME ICON PICKER MODAL (Visual Selection with Live Filter)
     ========================================================================= -->
<div id="iconPickerModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl w-full max-w-2xl max-h-[85vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden animate-in fade-in zoom-in duration-200">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-wood-100 text-wood-700 flex items-center justify-center text-base">
                    <i class="fa-solid fa-icons"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">مكتبة أيقونات FontAwesome</h3>
                    <p class="text-[11px] text-slate-500">اختر الأيقونة المناسبة بالضغط المباشر عليها</p>
                </div>
            </div>
            <button type="button" onclick="closeIconPicker()" class="w-8 h-8 rounded-full bg-slate-200/70 hover:bg-slate-300 text-slate-600 flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Search & Category Filters -->
        <div class="p-5 border-b border-slate-100 space-y-3 bg-white">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute top-3 right-3 text-slate-400 text-xs"></i>
                <input type="text" id="iconSearchInput" onkeyup="filterIcons()" placeholder="ابحث باسم الأيقونة (مثال: gem, star, tree, handshake, hammer)..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pr-9 pl-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500">
            </div>

            <!-- Category Pills -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
                <button type="button" onclick="filterCategory('all', this)" class="category-pill px-3 py-1 rounded-lg bg-wood-600 text-white font-bold whitespace-nowrap cursor-pointer">الكل</button>
                <button type="button" onclick="filterCategory('quality', this)" class="category-pill px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap cursor-pointer">الجودة والتميز</button>
                <button type="button" onclick="filterCategory('wood', this)" class="category-pill px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap cursor-pointer">الخشب والطبيعة</button>
                <button type="button" onclick="filterCategory('craft', this)" class="category-pill px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap cursor-pointer">الحرفية والأدوات</button>
                <button type="button" onclick="filterCategory('trust', this)" class="category-pill px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap cursor-pointer">الشراكة والأمانة</button>
                <button type="button" onclick="filterCategory('precision', this)" class="category-pill px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold whitespace-nowrap cursor-pointer">الدقة والالتزام</button>
            </div>
        </div>

        <!-- Icons Grid (Scrollable) -->
        <div class="p-6 overflow-y-auto max-h-[50vh]">
            <div id="iconGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                <!-- Icon Items populated by JavaScript -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span id="iconsCountLabel">أكثر من 70 أيقونة مميزة</span>
            <button type="button" onclick="closeIconPicker()" class="px-4 py-1.5 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-200 transition cursor-pointer">إغلاق</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let quillAboutAr, quillAboutEn, quillStoryAr, quillStoryEn, quillVisionAr, quillVisionEn;
    let counterIndex = {{ count($counters) }};
    let valueIndex = {{ count($valueItems) }};
    let whyUsIndex = {{ count($whyItems) }};
    let processIndex = {{ count($processSteps) }};

    // Curated Library of FontAwesome 6 Icons for Values & Joinery
    const FA_ICONS_LIBRARY = [
        // Quality & Excellence
        { code: 'fa-solid fa-gem', name: 'Gem / الجوهرة', category: 'quality' },
        { code: 'fa-solid fa-award', name: 'Award / وسام الجودة', category: 'quality' },
        { code: 'fa-solid fa-crown', name: 'Crown / التميز والريادة', category: 'quality' },
        { code: 'fa-solid fa-medal', name: 'Medal / الميدالية', category: 'quality' },
        { code: 'fa-solid fa-star', name: 'Star / النجمة', category: 'quality' },
        { code: 'fa-solid fa-certificate', name: 'Certificate / الشهادة والاعتماد', category: 'quality' },
        { code: 'fa-solid fa-shield-halved', name: 'Shield / الضمان والأمان', category: 'quality' },
        { code: 'fa-solid fa-trophy', name: 'Trophy / الصدارة والريادة', category: 'quality' },
        { code: 'fa-solid fa-ribbon', name: 'Ribbon / وسام الشرف', category: 'quality' },
        { code: 'fa-solid fa-circle-check', name: 'Check / الإتقان التام', category: 'quality' },
        
        // Wood & Nature
        { code: 'fa-solid fa-tree', name: 'Tree / الخشب الطبيعي', category: 'wood' },
        { code: 'fa-solid fa-seedling', name: 'Seedling / الاستدامة والنمو', category: 'wood' },
        { code: 'fa-solid fa-leaf', name: 'Leaf / أوراق الشجر والبيئة', category: 'wood' },
        { code: 'fa-solid fa-spa', name: 'Spa / الأصالة والجمال', category: 'wood' },
        { code: 'fa-solid fa-clover', name: 'Clover / النقاء', category: 'wood' },
        { code: 'fa-solid fa-mountain-sun', name: 'Nature / الطبيعة الخام', category: 'wood' },
        { code: 'fa-solid fa-earth-americas', name: 'Earth / المعايير العالمية', category: 'wood' },

        // Craftsmanship & Tools
        { code: 'fa-solid fa-wand-magic-sparkles', name: 'Magic Sparkles / اللمسة الإبداعية', category: 'craft' },
        { code: 'fa-solid fa-hammer', name: 'Hammer / المطرقة والحرفية', category: 'craft' },
        { code: 'fa-solid fa-screwdriver-wrench', name: 'Tools / أدوات النجارة', category: 'craft' },
        { code: 'fa-solid fa-wrench', name: 'Wrench / الصيانة والتفصيل', category: 'craft' },
        { code: 'fa-solid fa-compass-drafting', name: 'Compass / الهندسة والتصميم', category: 'craft' },
        { code: 'fa-solid fa-ruler-combined', name: 'Ruler / المقاييس الدقيقة', category: 'craft' },
        { code: 'fa-solid fa-pen-ruler', name: 'Pen & Ruler / المخططات والرسومات', category: 'craft' },
        { code: 'fa-solid fa-palette', name: 'Palette / تناسق الألوان والدهان', category: 'craft' },
        { code: 'fa-solid fa-lightbulb', name: 'Lightbulb / الأفكار والابتكار', category: 'craft' },
        { code: 'fa-solid fa-cubes', name: 'Cubes / الكتل والتراكيب', category: 'craft' },
        { code: 'fa-solid fa-layer-group', name: 'Layers / الطبقات والدمج', category: 'craft' },
        { code: 'fa-solid fa-paintbrush', name: 'Paintbrush / التشطيب الفاخر', category: 'craft' },
        { code: 'fa-solid fa-couch', name: 'Couch / الأثاث والديكور', category: 'craft' },
        { code: 'fa-solid fa-door-open', name: 'Door / الأبواب والكسوات', category: 'craft' },

        // Trust & Partnership
        { code: 'fa-solid fa-handshake-simple', name: 'Handshake / الثقة والالتزام', category: 'trust' },
        { code: 'fa-solid fa-handshake', name: 'Partnership / الشراكة الناجحة', category: 'trust' },
        { code: 'fa-solid fa-users', name: 'Team / فريق العمل المحترف', category: 'trust' },
        { code: 'fa-solid fa-user-tie', name: 'Expert / الخبرة والاستشارة', category: 'trust' },
        { code: 'fa-solid fa-heart', name: 'Heart / الشغف والعناية', category: 'trust' },
        { code: 'fa-solid fa-shield-heart', name: 'Care / حماية العميل', category: 'trust' },
        { code: 'fa-solid fa-headset', name: 'Support / خدمة العملاء', category: 'trust' },
        { code: 'fa-solid fa-comments', name: 'Communication / الشفافية والتواصل', category: 'trust' },
        { code: 'fa-solid fa-building-shield', name: 'Enterprise / الثقة المؤسسية', category: 'trust' },
        { code: 'fa-solid fa-hand-holding-heart', name: 'Dedication / الإخلاص في العمل', category: 'trust' },

        // Precision & Speed
        { code: 'fa-solid fa-bullseye', name: 'Bullseye / الدقة المتناهية', category: 'precision' },
        { code: 'fa-solid fa-crosshairs', name: 'Target / تحقيق الأهداف', category: 'precision' },
        { code: 'fa-solid fa-clock', name: 'Clock / الالتزام بالمواعيد', category: 'precision' },
        { code: 'fa-solid fa-stopwatch', name: 'Stopwatch / سرعة الإنجاز', category: 'precision' },
        { code: 'fa-solid fa-truck-fast', name: 'Delivery / التوصيل والتركيب السريع', category: 'precision' },
        { code: 'fa-solid fa-boxes-packing', name: 'Packing / التغليف الآمن', category: 'precision' },
        { code: 'fa-solid fa-bolt', name: 'Bolt / الاستجابة الفورية', category: 'precision' },
        { code: 'fa-solid fa-rocket', name: 'Rocket / التطور المستمر', category: 'precision' },
        { code: 'fa-solid fa-gauge-high', name: 'Gauge / الأداء الفائق', category: 'precision' },
        { code: 'fa-solid fa-chart-line', name: 'Growth / النمو المستمر', category: 'precision' },
        { code: 'fa-solid fa-landmark', name: 'Landmark / الفخامة المعمارية', category: 'quality' },
        { code: 'fa-solid fa-fingerprint', name: 'Fingerprint / البصمة الحصرية', category: 'craft' }
    ];

    let currentActiveIconInputId = null;
    let currentActiveIconPreviewId = null;

    function renderIconsGrid(filter = '', category = 'all') {
        const grid = document.getElementById('iconGrid');
        if (!grid) return;
        grid.innerHTML = '';

        const filtered = FA_ICONS_LIBRARY.filter(item => {
            const matchesCategory = (category === 'all' || item.category === category);
            const matchesSearch = (!filter || item.code.toLowerCase().includes(filter.toLowerCase()) || item.name.toLowerCase().includes(filter.toLowerCase()));
            return matchesCategory && matchesSearch;
        });

        if (filtered.length === 0) {
            grid.innerHTML = `<div class="col-span-full py-8 text-center text-xs text-slate-400">لا توجد أيقونات مطابقة للبحث</div>`;
            return;
        }

        filtered.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'flex flex-col items-center justify-center p-3 rounded-2xl border border-slate-100 hover:border-wood-500 hover:bg-wood-50/60 hover:scale-105 transition-all duration-200 group cursor-pointer text-center';
            btn.onclick = function() {
                selectIcon(item.code);
            };
            btn.innerHTML = `
                <div class="w-10 h-10 rounded-xl bg-slate-100 group-hover:bg-wood-600 group-hover:text-white text-slate-700 flex items-center justify-center text-lg mb-2 transition-colors">
                    <i class="${item.code}"></i>
                </div>
                <span class="text-[10px] font-mono text-slate-600 group-hover:text-wood-900 font-bold truncate max-w-full block">${item.code.replace('fa-solid ', '')}</span>
            `;
            grid.appendChild(btn);
        });

        document.getElementById('iconsCountLabel').innerText = `${filtered.length} أيقونة معروضة`;
    }

    function openIconPicker(inputId, previewId) {
        currentActiveIconInputId = inputId;
        currentActiveIconPreviewId = previewId;
        document.getElementById('iconSearchInput').value = '';
        renderIconsGrid('', 'all');
        document.getElementById('iconPickerModal').classList.remove('hidden');
    }

    function closeIconPicker() {
        document.getElementById('iconPickerModal').classList.add('hidden');
    }

    function selectIcon(iconCode) {
        if (currentActiveIconInputId) {
            const input = document.getElementById(currentActiveIconInputId);
            if (input) input.value = iconCode;
        }
        if (currentActiveIconPreviewId) {
            const preview = document.getElementById(currentActiveIconPreviewId);
            if (preview) {
                preview.innerHTML = `<i class="${iconCode}"></i>`;
            }
        }
        closeIconPicker();
    }

    function filterIcons() {
        const query = document.getElementById('iconSearchInput').value;
        const activeCategory = document.querySelector('.category-pill.bg-wood-600')?.dataset?.category || 'all';
        renderIconsGrid(query, activeCategory);
    }

    function filterCategory(cat, btn) {
        document.querySelectorAll('.category-pill').forEach(p => {
            p.classList.remove('bg-wood-600', 'text-white');
            p.classList.add('bg-slate-100', 'text-slate-700');
        });
        btn.classList.remove('bg-slate-100', 'text-slate-700');
        btn.classList.add('bg-wood-600', 'text-white');
        btn.dataset.category = cat;

        const query = document.getElementById('iconSearchInput').value;
        renderIconsGrid(query, cat);
    }

    function addValueRow() {
        const container = document.getElementById('valuesContainer');
        const vIdx = valueIndex;
        const row = document.createElement('div');
        row.className = 'p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 value-row relative group';
        row.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-wood-100 text-wood-800 flex items-center justify-center text-xs font-bold font-mono">${vIdx + 1}</span>
                    <span class="text-xs font-bold text-slate-800">بطاقة قيمة جديدة</span>
                </div>
                <button type="button" onclick="this.closest('.value-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف القيمة">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-3 space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-700">الأيقونة المحددة (FA)</label>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="values[items][${vIdx}][icon]" id="val_icon_input_${vIdx}" value="fa-solid fa-gem">
                        <button type="button" onclick="openIconPicker('val_icon_input_${vIdx}', 'val_icon_preview_${vIdx}')"
                            class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <div id="val_icon_preview_${vIdx}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                    <i class="fa-solid fa-gem"></i>
                                </div>
                                <span class="text-xs font-mono text-slate-600 font-semibold">اختر أيقونة</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </div>
                </div>

                <div class="sm:col-span-4 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">اسم القيمة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('val_title_ar_${vIdx}', 'val_title_en_${vIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="val_title_ar_${vIdx}" name="values[items][${vIdx}][title_ar]" placeholder="مثال: الإتقان والجودة" required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                </div>

                <div class="sm:col-span-5 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">اسم القيمة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('val_title_en_${vIdx}', 'val_title_ar_${vIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="val_title_en_${vIdx}" name="values[items][${vIdx}][title_en]" placeholder="e.g. Uncompromising Quality"
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">وصف وشرح القيمة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('val_desc_ar_${vIdx}', 'val_desc_en_${vIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="val_desc_ar_${vIdx}" name="values[items][${vIdx}][desc_ar]" rows="2" placeholder="اكتب نبذة أو شرحاً موجزاً لهذه القيمة..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">وصف وشرح القيمة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('val_desc_en_${vIdx}', 'val_desc_ar_${vIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="val_desc_en_${vIdx}" name="values[items][${vIdx}][desc_en]" rows="2" placeholder="Write a brief explanation for this core value..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>
            </div>
        `;
        container.appendChild(row);
        valueIndex++;
    }

    function addWhyUsRow() {
        const container = document.getElementById('whyUsContainer');
        const wIdx = whyUsIndex;
        const row = document.createElement('div');
        row.className = 'p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 why-us-row relative group animate-fade-in';
        row.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-wood-100 text-wood-800 flex items-center justify-center text-xs font-bold font-mono">${wIdx + 1}</span>
                    <span class="text-xs font-bold text-slate-800">ميزة لماذا تختارنا</span>
                </div>
                <button type="button" onclick="this.closest('.why-us-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-3 space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-700">الأيقونة (FA)</label>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="why_us[items][${wIdx}][icon]" id="why_icon_input_${wIdx}" value="fa-solid fa-crown">
                        <button type="button" onclick="openIconPicker('why_icon_input_${wIdx}', 'why_icon_preview_${wIdx}')"
                            class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <div id="why_icon_preview_${wIdx}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <span class="text-xs font-mono text-slate-600 font-semibold" id="why_icon_text_${wIdx}">اختر أيقونة</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </div>
                </div>

                <div class="sm:col-span-4 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">عنوان الميزة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('why_title_ar_${wIdx}', 'why_title_en_${wIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="why_title_ar_${wIdx}" name="why_us[items][${wIdx}][title_ar]" placeholder="مثال: أخشاب طبيعية 100%" required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                </div>

                <div class="sm:col-span-5 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">عنوان الميزة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('why_title_en_${wIdx}', 'why_title_ar_${wIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="why_title_en_${wIdx}" name="why_us[items][${wIdx}][title_en]" placeholder="e.g. 100% Premium Solid Hardwood"
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">شرح وتفاصيل الميزة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('why_desc_ar_${wIdx}', 'why_desc_en_${wIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="why_desc_ar_${wIdx}" name="why_us[items][${wIdx}][desc_ar]" rows="2" placeholder="اكتب تفاصيل وشرحاً لهذه الميزة..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">شرح وتفاصيل الميزة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('why_desc_en_${wIdx}', 'why_desc_ar_${wIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="why_desc_en_${wIdx}" name="why_us[items][${wIdx}][desc_en]" rows="2" placeholder="Write details for this feature..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>
            </div>
        `;
        container.appendChild(row);
        whyUsIndex++;
    }

    function addProcessRow() {
        const container = document.getElementById('processContainer');
        const pIdx = processIndex;
        const row = document.createElement('div');
        row.className = 'p-5 rounded-2xl border border-slate-200/90 bg-slate-50/70 space-y-4 process-row relative group animate-fade-in';
        const stepNumFormatted = (pIdx + 1).toString().padStart(2, '0');
        row.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-wood-600 text-white flex items-center justify-center text-xs font-bold font-mono">${stepNumFormatted}</span>
                    <span class="text-xs font-bold text-slate-800">خطوة العمل والمرحلة</span>
                </div>
                <button type="button" onclick="this.closest('.process-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف الخطوة">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                <div class="sm:col-span-2 space-y-1">
                    <label class="block text-[11px] font-bold text-slate-700">رقم الخطوة</label>
                    <input type="text" name="process[steps][${pIdx}][step_number]" value="${stepNumFormatted}" placeholder="01" required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-mono font-bold text-center focus:outline-none focus:border-wood-500">
                </div>

                <div class="sm:col-span-3 space-y-1.5">
                    <label class="block text-[11px] font-bold text-slate-700">الأيقونة (FA)</label>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="process[steps][${pIdx}][icon]" id="process_icon_input_${pIdx}" value="fa-solid fa-compass-drafting">
                        <button type="button" onclick="openIconPicker('process_icon_input_${pIdx}', 'process_icon_preview_${pIdx}')"
                            class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-white border border-slate-200 hover:border-wood-500 rounded-xl transition shadow-2xs group-hover:border-wood-400 cursor-pointer">
                            <div class="flex items-center gap-2.5">
                                <div id="process_icon_preview_${pIdx}" class="w-8 h-8 rounded-lg bg-wood-50 text-wood-700 flex items-center justify-center text-base border border-wood-200">
                                    <i class="fa-solid fa-compass-drafting"></i>
                                </div>
                                <span class="text-xs font-mono text-slate-600 font-semibold" id="process_icon_text_${pIdx}">اختر أيقونة</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                    </div>
                </div>

                <div class="sm:col-span-3 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">اسم المرحلة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('process_title_ar_${pIdx}', 'process_title_en_${pIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="process_title_ar_${pIdx}" name="process[steps][${pIdx}][title_ar]" placeholder="مثال: الاستشارة والتصميم" required
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:outline-none focus:border-wood-500">
                </div>

                <div class="sm:col-span-4 space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">اسم المرحلة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('process_title_en_${pIdx}', 'process_title_ar_${pIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <input type="text" id="process_title_en_${pIdx}" name="process[steps][${pIdx}][title_en]" placeholder="e.g. Consultation & Design"
                        class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">شرح خطوات المرحلة (بالعربي)</label>
                        <button type="button" onclick="autoTranslate('process_desc_ar_${pIdx}', 'process_desc_en_${pIdx}', 'ar', 'en', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="process_desc_ar_${pIdx}" name="process[steps][${pIdx}][desc_ar]" rows="2" placeholder="اكتب تفاصيل هذه المرحلة..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>

                <div class="space-y-1">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-bold text-slate-700">شرح خطوات المرحلة (بالإنجليزي)</label>
                        <button type="button" onclick="autoTranslate('process_desc_en_${pIdx}', 'process_desc_ar_${pIdx}', 'en', 'ar', this)" class="text-[10px] font-bold text-wood-600 hover:text-wood-700">
                            <i class="fa-solid fa-language"></i> ترجمة
                        </button>
                    </div>
                    <textarea id="process_desc_en_${pIdx}" name="process[steps][${pIdx}][desc_en]" rows="2" placeholder="Write details for this workflow step..."
                        class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 leading-relaxed"></textarea>
                </div>
            </div>
        `;
        container.appendChild(row);
        processIndex++;
    }

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
                <button type="button" onclick="this.closest('.counter-row').remove()" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-100 transition cursor-pointer" title="حذف">
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

            quillAboutAr = new Quill('#quill_about_ar', { theme: 'snow', placeholder: 'اكتب تفاصيل من نحن...', modules });
            quillAboutEn = new Quill('#quill_about_en', { theme: 'snow', placeholder: 'Write about us details...', modules });
            quillStoryAr = new Quill('#quill_story_ar', { theme: 'snow', placeholder: 'اكتب قصة الورشة...', modules });
            quillStoryEn = new Quill('#quill_story_en', { theme: 'snow', placeholder: 'Write workshop story...', modules });
            quillVisionAr = new Quill('#quill_vision_ar', { theme: 'snow', placeholder: 'اكتب الرؤية والرسالة...', modules });
            quillVisionEn = new Quill('#quill_vision_en', { theme: 'snow', placeholder: 'Write vision & mission...', modules });

            document.getElementById('aboutForm').addEventListener('submit', function() {
                document.getElementById('about_content_ar').value = quillAboutAr.root.innerHTML;
                document.getElementById('about_content_en').value = quillAboutEn.root.innerHTML;
                document.getElementById('story_content_ar').value = quillStoryAr.root.innerHTML;
                document.getElementById('story_content_en').value = quillStoryEn.root.innerHTML;
                document.getElementById('vision_content_ar').value = quillVisionAr.root.innerHTML;
                document.getElementById('vision_content_en').value = quillVisionEn.root.innerHTML;
            });
        }
    });
</script>
@endpush
