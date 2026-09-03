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
        $aboutImgUrl = $about?->image ? storage_asset($about->image) : 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80';
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
        $storyImgUrl = $story?->image ? storage_asset($story->image) : 'https://images.unsplash.com/photo-1540518614846-7ede433c4b13?auto=format&fit=crop&w=1000&q=80';
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

        <!-- Values Cards Centered Flex Grid -->
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($valueItems as $val)
                @php
                    $valTitle = app()->getLocale() === 'ar' ? ($val['title_ar'] ?? '') : ($val['title_en'] ?? $val['title_ar'] ?? '');
                    $valDesc = app()->getLocale() === 'ar' ? ($val['desc_ar'] ?? '') : ($val['desc_en'] ?? $val['desc_ar'] ?? '');
                    $valIcon = $val['icon'] ?? 'fa-solid fa-gem';
                @endphp
                <div class="glass-card rounded-3xl p-7 flex flex-col justify-between border-white/10 hover:border-gold-500/40 transition-all duration-300 group hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-gold-500/10 w-full sm:w-[calc(50%-0.85rem)] lg:w-[calc(25%-1.25rem)] max-w-sm">
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

                    <!-- Subtle Bottom Luxury Line Indicator (No Arrow) -->
                    <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-center">
                        <span class="w-10 h-1 rounded-full bg-gold-500/30 group-hover:bg-gold-500/80 group-hover:w-20 transition-all duration-300"></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 5: WHY CHOOSE US (لماذا تختارنا)
         ========================================================================= -->
    @php
        $siteNameCurrent = \App\Models\Setting::get('site_name_' . app()->getLocale(), app()->getLocale() === 'ar' ? 'ورشة أرتيزان' : 'Artisan Workshop');
        $whyUsTitle = $whyUs?->title ?: (app()->getLocale() === 'ar' ? "لماذا تختار {$siteNameCurrent}؟" : "Why Choose {$siteNameCurrent}?");
        $whyUsSubtitle = $whyUs?->subtitle ?: (app()->getLocale() === 'ar' ? 'معايير ملكية تفوق التوقعات وتمنحك راحة البال التامة' : 'Royal standards that exceed expectations and ensure total peace of mind');
        $whyUsItems = $whyUs?->meta_data ?: [
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/15 border border-gold-500/30 text-gold-400">
                <i class="fa-solid fa-crown text-[11px]"></i>
                <span>{{ $whyUsSubtitle }}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                {{ $whyUsTitle }}
            </h2>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>

        <!-- Why Choose Us Centered Cards -->
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($whyUsItems as $item)
                @php
                    $wTitle = app()->getLocale() === 'ar' ? ($item['title_ar'] ?? '') : ($item['title_en'] ?? $item['title_ar'] ?? '');
                    $wDesc = app()->getLocale() === 'ar' ? ($item['desc_ar'] ?? '') : ($item['desc_en'] ?? $item['desc_ar'] ?? '');
                    $wIcon = $item['icon'] ?? 'fa-solid fa-crown';
                @endphp
                <div class="glass-card rounded-3xl p-7 flex flex-col justify-between border-white/10 hover:border-gold-500/40 transition-all duration-300 group hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-gold-500/10 w-full sm:w-[calc(50%-0.85rem)] lg:w-[calc(25%-1.25rem)] max-w-sm">
                    <div class="space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-wood-800 to-dark-950 border border-gold-500/40 text-gold-400 flex items-center justify-center text-2xl shadow-lg shadow-gold-500/10 group-hover:scale-110 group-hover:border-gold-400 group-hover:text-gold-300 transition-all duration-300">
                            <i class="{{ $wIcon }}"></i>
                        </div>
                        <h3 class="text-lg font-black text-white group-hover:text-gold-400 transition-colors">
                            {{ $wTitle }}
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            {{ $wDesc }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-center">
                        <span class="w-10 h-1 rounded-full bg-gold-500/30 group-hover:bg-gold-500/80 group-hover:w-20 transition-all duration-300"></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 6: HOW WE WORK / PROCESS (كيف نعمل ومراحل التصنيع)
         ========================================================================= -->
    @php
        $processTitle = $process?->title ?: (app()->getLocale() === 'ar' ? 'كيف نعمل - مراحل تحويل فكرتك إلى تحفة خشبية' : 'How We Work - The Journey from Vision to Masterpiece');
        $processSubtitle = $process?->subtitle ?: (app()->getLocale() === 'ar' ? 'منهجية عمل هندسية مدروسة تضمن أرقى مستويات الجودة والإتقان' : 'A structured engineering workflow delivering exquisite craftsmanship');
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto space-y-3">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/15 border border-gold-500/30 text-gold-400">
                <i class="fa-solid fa-diagram-project text-[11px]"></i>
                <span>{{ $processSubtitle }}</span>
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                {{ $processTitle }}
            </h2>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
        </div>

        <!-- Workflow Centered Steps Grid -->
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($processSteps as $pIdx => $step)
                @php
                    $stepNum = $step['step_number'] ?? sprintf('%02d', $pIdx + 1);
                    $sTitle = app()->getLocale() === 'ar' ? ($step['title_ar'] ?? '') : ($step['title_en'] ?? $step['title_ar'] ?? '');
                    $sDesc = app()->getLocale() === 'ar' ? ($step['desc_ar'] ?? '') : ($step['desc_en'] ?? $step['desc_ar'] ?? '');
                    $sIcon = $step['icon'] ?? 'fa-solid fa-compass-drafting';
                @endphp
                <div class="glass-card rounded-3xl p-6 sm:p-7 flex flex-col justify-between border-white/10 hover:border-gold-500/50 transition-all duration-300 group hover:-translate-y-2 hover:shadow-2xl hover:shadow-gold-500/15 w-full sm:w-[calc(50%-0.85rem)] lg:w-[calc(33.333%-1rem)] max-w-sm relative overflow-hidden">
                    <!-- Background Watermark Number -->
                    <span class="absolute -bottom-4 {{ app()->getLocale() === 'ar' ? '-left-2' : '-right-2' }} text-7xl font-black font-mono text-white/[0.03] group-hover:text-gold-500/[0.08] transition-colors select-none pointer-events-none">
                        {{ $stepNum }}
                    </span>

                    <div class="space-y-4 relative z-10">
                        <!-- Step Header: Icon + Number Badge -->
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-wood-800 to-dark-950 border border-gold-500/40 text-gold-400 flex items-center justify-center text-xl shadow-md group-hover:scale-110 group-hover:border-gold-300 transition-transform">
                                <i class="{{ $sIcon }}"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-gold-500/15 border border-gold-500/30 text-gold-400 font-mono font-bold text-xs tracking-wider">
                                {{ app()->getLocale() === 'ar' ? 'المرحلة' : 'Step' }} {{ $stepNum }}
                            </span>
                        </div>

                        <!-- Step Title -->
                        <h4 class="text-base sm:text-lg font-black text-white group-hover:text-gold-400 transition-colors">
                            {{ $sTitle }}
                        </h4>

                        <!-- Step Description -->
                        <p class="text-xs text-slate-300 leading-relaxed">
                            {{ $sDesc }}
                        </p>
                    </div>

                    <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between relative z-10">
                        <span class="text-[11px] font-bold text-wood-400 font-mono">{{ $stepNum }} / {{ sprintf('%02d', count($processSteps)) }}</span>
                        <span class="w-12 h-1 rounded-full bg-gold-500/30 group-hover:bg-gold-500/80 group-hover:w-24 transition-all duration-300"></span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- =========================================================================
         SECTION 7: MILESTONES & NUMBERS (الأرقام والإنجازات القياسية - توسيط من المنتصف)
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
        <div class="flex flex-wrap justify-center gap-6">
            @foreach($counters as $c)
                <div class="glass-card p-6 sm:p-8 rounded-3xl text-center space-y-2 border-white/10 hover:border-gold-500/30 transition-all w-full sm:w-[calc(50%-0.85rem)] lg:w-[calc(25%-1.25rem)] max-w-xs">
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
