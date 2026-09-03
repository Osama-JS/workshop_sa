@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'من نحن وتاريخ الورشة' : 'About Us') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية'))

@section('content')
<!-- =========================================================================
     PAGE HERO HEADER
     ========================================================================= -->
<div class="py-24 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="absolute inset-0 bg-radial-at-c from-wood-600/10 via-transparent to-transparent pointer-events-none"></div>
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-gold-400 text-xs font-bold uppercase tracking-widest">
            <i class="fa-solid fa-gem text-[10px]"></i>
            <span>{{ app()->getLocale() === 'ar' ? 'أصالة وحرفية سعودية فاخرة' : 'Authentic Saudi Craftsmanship' }}</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight">
            {{ app()->getLocale() === 'ar' ? 'من نحن وقصة إبداعنا' : 'About Our Workshop & Heritage' }}
        </h1>
        <p class="text-xs sm:text-base text-slate-400 max-w-2xl mx-auto leading-relaxed">
            {{ app()->getLocale() === 'ar' ? 'رحلة ممتدة من الشغف والإتقان في تحويل كتل الخشب الطبيعي إلى تحف هندسية وديكورات مخصصة تليق بأرقى المساحات.' : 'A legacy of passion and master craftsmanship in turning premium natural timber into bespoke architectural masterpieces.' }}
        </p>
    </div>
</div>

<div class="py-20 bg-dark-900 space-y-24">

    <!-- =========================================================================
         SECTION 1: ABOUT US (قسم من نحن الرئيسي - أعلى الصفحة)
         ========================================================================= -->
    @php
        $aboutTitle = $about?->title ?: (app()->getLocale() === 'ar' ? 'من نحن - ورشة أرتيزان للأعمال الخشبية الفاخرة' : 'About Us - Artisan Luxury Woodworking Workshop');
        $aboutSubtitle = $about?->subtitle ?: (app()->getLocale() === 'ar' ? 'صرح سعودي رائد في هندسة وتفصيل الخشب الطبيعي' : 'A leading Saudi powerhouse in bespoke timber engineering');
        $aboutContent = $about?->content ?: (app()->getLocale() === 'ar' 
            ? '<p>نحن في ورشة أرتيزان نفخر بكوننا أحد أبرز الصروح المتخصصة في النجارة المعمارية وتفصيل الأثاث الخشبي الفاخر في المملكة العربية السعودية. نعتمد على كوادر فنية وحرفيين ذوي خبرات عريقة، ونوظف أحدث ما توصلت إليه التكنولوجيا لنلبي تطلعات القصور والمكاتب والمعارض والمشاريع التجارية الكبرى بدقة متناهية وجودة تفوق التوقعات.</p>' 
            : '<p>At Artisan Workshop, we take immense pride in being one of the premier workshops dedicated to architectural joinery and luxury custom woodwork in Saudi Arabia. Combining master artisan hands with state-of-the-art CNC technology, we craft bespoke furniture and timber interiors that exceed expectations.</p>');
        $aboutImgUrl = $about?->image ? asset('storage/' . $about->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Text Content -->
            <div class="lg:col-span-7 space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-wood-500/10 text-wood-400 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-building text-[11px]"></i>
                    <span>{{ $aboutSubtitle }}</span>
                </div>
                
                <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    {{ $aboutTitle }}
                </h2>
                
                <div class="w-20 h-1.5 bg-gradient-to-r from-gold-500 to-wood-500 rounded-full"></div>
                
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base space-y-4 prose prose-invert max-w-none">
                    {!! $aboutContent !!}
                </div>

                <!-- Features Highlights -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gold-500/10 text-gold-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-tree"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-200">{{ app()->getLocale() === 'ar' ? 'أخشاب طبيعية 100%' : '100% Natural Timber' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gold-500/10 text-gold-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-compass-drafting"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-200">{{ app()->getLocale() === 'ar' ? 'تصاميم هندسية دقيقة' : 'Precision Engineering' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gold-500/10 text-gold-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-200">{{ app()->getLocale() === 'ar' ? 'ضمان شامل على الأعمال' : 'Comprehensive Warranty' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gold-500/10 text-gold-400 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-200">{{ app()->getLocale() === 'ar' ? 'التزام صارم بالمواعيد' : 'On-Time Delivery' }}</span>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('order.create') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-gold-gradient text-dark-950 font-bold text-xs shadow-xl shadow-gold-500/20 hover:scale-105 transition-all">
                        <span>{{ app()->getLocale() === 'ar' ? 'اطلب استشارة وتفصيل مخصص' : 'Request Bespoke Joinery' }}</span>
                        <i class="fa-solid {{ app()->getLocale() === 'ar' ? 'fa-arrow-left' : 'fa-arrow-right' }} text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Right Visual Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl group">
                    <img src="{{ $aboutImgUrl }}" alt="About Artisan Workshop" class="w-full h-[420px] object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950/80 via-transparent to-transparent"></div>
                    
                    <!-- Floating Badge -->
                    <div class="absolute bottom-6 {{ app()->getLocale() === 'ar' ? 'right-6 left-6' : 'left-6 right-6' }} p-4 rounded-2xl glass-card border-gold-500/30 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gold-400 to-gold-600 text-dark-950 flex items-center justify-center text-xl font-bold shrink-0 shadow-lg">
                            <i class="fa-solid fa-award"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-gold-400 block">{{ app()->getLocale() === 'ar' ? 'معايير فندقية وسكنية فاخرة' : 'Luxury Hospitality Standards' }}</span>
                            <span class="text-[11px] text-slate-300">{{ app()->getLocale() === 'ar' ? 'تنفيذ وتفصيل وفق أعلى المواصفات السعودية' : 'Crafted to premium architectural benchmarks' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 2: OUR STORY (قصة البداية والشغف)
         ========================================================================= -->
    @php
        $storyTitle = $story?->title ?: (app()->getLocale() === 'ar' ? 'قصة تأسيس ورشة أرتيزان للأعمال الخشبية' : 'The Story Behind Artisan Woodcraft');
        $storySubtitle = $story?->subtitle ?: (app()->getLocale() === 'ar' ? 'البداية والشغف' : 'The Beginning & Passion');
        $storyContent = $story?->content ?: (app()->getLocale() === 'ar' 
            ? '<p>انطلقنا برؤية طموحة لتقديم أثاث وأعمال خشبية تفوق التوقعات، حيث نجمع بين أفضل أنواع الخشب الطبيعي وتقنيات التصنيع الحديثة واللمسات اليدوية المتقنة.</p>' 
            : '<p>We embarked with an ambitious vision to craft timber products exceeding expectations, marrying finest woods with state-of-the-art joinery.</p>');
        $storyImgUrl = $story?->image ? asset('storage/' . $story->image) : 'https://images.unsplash.com/photo-1540518614846-7ede433c4b13?auto=format&fit=crop&w=1000&q=80';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Image -->
            <div class="order-2 lg:order-1 rounded-3xl overflow-hidden border border-white/10 shadow-2xl relative group">
                <img src="{{ $storyImgUrl }}" alt="Our Story" class="w-full h-96 sm:h-[420px] object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-dark-950/70 via-transparent to-transparent"></div>
            </div>

            <!-- Right Story Content -->
            <div class="order-1 lg:order-2 space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                    {{ $storySubtitle }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    {{ $storyTitle }}
                </h2>
                <div class="w-16 h-1 bg-gold-500 rounded-full"></div>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base space-y-4 prose prose-invert max-w-none">
                    {!! $storyContent !!}
                </div>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         SECTION 3: VISION & MISSION (الرؤية والرسالة)
         ========================================================================= -->
    @if($vision)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-8 sm:p-14 space-y-8 border-gold-500/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <div class="text-center max-w-2xl mx-auto space-y-3 relative z-10">
                    <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">{{ $vision->subtitle }}</span>
                    <h3 class="text-3xl font-black text-white">{{ $vision->title }}</h3>
                    <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
                </div>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base prose prose-invert max-w-none text-center relative z-10">
                    {!! $vision->content !!}
                </div>
            </div>
        </div>
    @endif

    <!-- =========================================================================
         SECTION 4: OUR CORE VALUES (قيمنا ومبادئ عملنا - بطاقات فاخرة تحت الرؤية والرسالة)
         ========================================================================= -->
    @php
        $valuesTitle = $values?->title ?: (app()->getLocale() === 'ar' ? 'قيمنا ومبادئ عملنا الراسخة' : 'Our Enduring Core Values');
        $valuesSubtitle = $values?->subtitle ?: (app()->getLocale() === 'ar' ? 'المبادئ السامية التي تحكم كل مرحلة في ورشتنا' : 'The guiding principles behind every creation in our workshop');
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <!-- Section Heading -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold-500/10 border border-gold-500/30 text-gold-400 text-xs font-bold uppercase tracking-widest">
                <i class="fa-solid fa-sparkles text-[10px]"></i>
                <span>{{ $valuesSubtitle }}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white">
                {{ $valuesTitle }}
            </h2>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>

        <!-- Values Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($valueItems as $val)
                @php
                    $valTitle = app()->getLocale() === 'ar' ? ($val['title_ar'] ?? '') : ($val['title_en'] ?? $val['title_ar'] ?? '');
                    $valDesc = app()->getLocale() === 'ar' ? ($val['desc_ar'] ?? '') : ($val['desc_en'] ?? $val['desc_ar'] ?? '');
                    $valIcon = $val['icon'] ?? 'fa-solid fa-gem';
                @endphp
                <div class="glass-card rounded-3xl p-7 flex flex-col justify-between border-white/10 hover:border-gold-500/40 transition-all duration-300 group hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-gold-500/10">
                    <div class="space-y-4">
                        <!-- Icon Circle with Glowing Accent -->
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-wood-800 to-dark-950 border border-gold-500/40 text-gold-400 flex items-center justify-center text-2xl shadow-lg shadow-gold-500/10 group-hover:scale-110 group-hover:border-gold-400 group-hover:text-gold-300 transition-all duration-300">
                            <i class="{{ $valIcon }}"></i>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-black text-white group-hover:text-gold-400 transition-colors">
                            {{ $valTitle }}
                        </h3>

                        <!-- Description -->
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            {{ $valDesc }}
                        </p>
                    </div>

                    <!-- Bottom Accent Bar -->
                    <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="w-2 h-2 rounded-full bg-gold-500/60 group-hover:scale-150 transition-transform"></span>
                        <i class="fa-solid fa-arrow-left text-[11px] text-slate-600 group-hover:text-gold-400 group-hover:-translate-x-1 transition-all"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 5: MILESTONES & NUMBERS (الأرقام والإنجازات القياسية)
         ========================================================================= -->
    @php
        $counters = $stats?->meta_data ?: [
            ['number' => '15+', 'label_ar' => 'سنوات من الخبرة والإتقان', 'label_en' => 'Years of Master Craftsmanship'],
            ['number' => '450+', 'label_ar' => 'مشروع فاخر تم تسليمه', 'label_en' => 'Luxury Projects Delivered'],
            ['number' => '98%', 'label_ar' => 'نسبة رضا العملاء والشركات', 'label_en' => 'Customer Satisfaction Rate'],
            ['number' => '30+', 'label_ar' => 'حرفي وفني نجارة محترف', 'label_en' => 'Master Wood Artisans'],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach($counters as $c)
                <div class="glass-card p-6 sm:p-8 rounded-3xl text-center space-y-2 border-white/10 hover:border-gold-500/30 transition-all">
                    <span class="text-3xl sm:text-5xl font-black text-gold-gradient block font-mono tracking-tight">
                        {{ $c['number'] ?? '' }}
                    </span>
                    <span class="text-xs sm:text-sm text-slate-300 font-bold block leading-snug">
                        {{ app()->getLocale() === 'ar' ? ($c['label_ar'] ?? '') : ($c['label_en'] ?? $c['label_ar'] ?? '') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 6: CTA (دعوة للتواصل والتفصيل)
         ========================================================================= -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-3xl p-8 sm:p-12 border-gold-500/30 bg-gradient-to-r from-wood-950/90 via-dark-950 to-wood-950/90 text-center space-y-6 relative overflow-hidden shadow-2xl">
            <div class="max-w-2xl mx-auto space-y-3 relative z-10">
                <h3 class="text-2xl sm:text-3xl font-black text-white">
                    {{ app()->getLocale() === 'ar' ? 'هل لديك فكرة أو تصميم ترغب في تحويله لتحفة خشبية؟' : 'Ready to Bring Your Woodwork Vision to Life?' }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    {{ app()->getLocale() === 'ar' ? 'تواصل مع فريقنا الهندسي أو اطلب عرض سعر فوري مع رفع مخططاتك ومواصفاتك بسهولة.' : 'Connect with our engineering craftsmen or submit your custom order specifications for an instant review.' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-4 relative z-10">
                <a href="{{ route('order.create') }}" class="px-8 py-3.5 rounded-2xl bg-gold-gradient text-dark-950 font-bold text-xs shadow-xl shadow-gold-500/20 hover:scale-105 transition-all">
                    <i class="fa-solid fa-file-pen ml-1"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'تقديم طلب مخصص الآن' : 'Submit Custom Order' }}</span>
                </a>
                <a href="{{ route('portfolio.index') }}" class="px-8 py-3.5 rounded-2xl border border-white/20 text-white hover:bg-white/10 font-bold text-xs transition-all">
                    <i class="fa-solid fa-images ml-1"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'استكشف معرض المشاريع' : 'Explore Portfolio' }}</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
