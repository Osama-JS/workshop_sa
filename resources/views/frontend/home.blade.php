@extends('frontend.layouts.app')

@section('title', \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية الفاخرة'))

@section('content')

<!-- ==========================================
     1. DYNAMIC HERO SECTION (Slider / Video / Static)
     ========================================== -->
<section id="hero" class="relative min-h-[85vh] lg:min-h-[92vh] flex items-center justify-center overflow-hidden bg-dark-950">

    @if($heroType === 'video' && !empty($heroVideoUrl))
        <!-- MODE: VIDEO BACKGROUND -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            @if(str_contains($heroVideoUrl, 'youtube.com') || str_contains($heroVideoUrl, 'youtu.be'))
                <iframe src="{{ $heroVideoUrl }}?autoplay=1&mute=1&loop=1&controls=0" class="w-full h-full object-cover scale-125 opacity-70"></iframe>
            @else
                <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-60">
                    <source src="{{ $heroVideoUrl }}" type="video/mp4">
                </video>
            @endif
            <div class="absolute inset-0 bg-dark-950" style="opacity: {{ $heroOverlayOpacity }};"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-transparent to-dark-950/80"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 text-center py-20 space-y-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/10 border border-gold-500/30 text-gold-400 backdrop-blur-md">
                <i class="fa-solid fa-crown text-xs"></i>
                <span>{{ \App\Models\Setting::get('site_slogan_' . app()->getLocale(), 'حرفية متوارثة وإتقان بلا حدود') }}</span>
            </span>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight">
                {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}
            </h1>

            <p class="text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                {{ \App\Models\Setting::get('footer_desc_' . app()->getLocale(), 'نصنع الأثاث الراقي والديكورات وبوثات المعارض والتكسيات الجدارية بأعلى معايير الإتقان.') }}
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ route('order.create') }}" class="px-8 py-3.5 rounded-xl bg-gold-gradient text-slate-950 font-bold text-sm shadow-xl shadow-gold-500/20 hover:scale-105 transition-transform flex items-center gap-2">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'طلب تفصيل مخصص' : 'Request Custom Quote' }}</span>
                </a>
                <a href="{{ route('portfolio.index') }}" class="px-8 py-3.5 rounded-xl glass-card text-white hover:bg-white/10 font-bold text-sm border border-white/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-images text-gold-400"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'معرض المشاريع' : 'View Portfolio' }}</span>
                </a>
            </div>
        </div>

    @elseif($heroType === 'static')
        <!-- MODE: STATIC LUXURY IMAGE -->
        <div class="absolute inset-0 z-0">
            @php
                $staticImg = $heroStaticImage ? storage_asset($heroStaticImage) : 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1920&q=80';
            @endphp
            <img src="{{ $staticImg }}" class="w-full h-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/70 to-dark-950/90"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 text-center py-20 space-y-6">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/10 border border-gold-500/30 text-gold-400 backdrop-blur-md">
                <i class="fa-solid fa-tree text-xs"></i>
                <span>{{ \App\Models\Setting::get('site_slogan_' . app()->getLocale(), 'أفخم الأعمال الخشبية المصممة حسب الطلب') }}</span>
            </span>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight">
                {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'ورشة أرتيزان السعودية') }}
            </h1>

            <p class="text-sm sm:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed">
                {{ \App\Models\Setting::get('footer_desc_' . app()->getLocale(), 'أعمال النجارة والتفصيل المخصص لغرف النوم والمكاتب والديكورات وبوثات المعارض.') }}
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                <a href="{{ route('order.create') }}" class="px-8 py-3.5 rounded-xl bg-gold-gradient text-slate-950 font-bold text-sm shadow-xl shadow-gold-500/20 hover:scale-105 transition-transform flex items-center gap-2">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'اطلب تسعيرة الآن' : 'Get a Quote' }}</span>
                </a>
                <a href="{{ route('services.index') }}" class="px-8 py-3.5 rounded-xl glass-card text-white hover:bg-white/10 font-bold text-sm border border-white/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-couch text-gold-400"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'استكشف خدماتنا' : 'Our Services' }}</span>
                </a>
            </div>
        </div>

    @else
        <!-- MODE: MULTI-SLIDE ANIMATED CAROUSEL (Default) -->
        <div id="heroCarousel" class="relative w-full h-[85vh] lg:h-[92vh] overflow-hidden">
            @foreach($heroSlides as $index => $slide)
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' }}" data-slide-index="{{ $index }}">
                    <!-- Background Image with subtle zoom -->
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="slide-img w-full h-full object-cover scale-100 transition-transform duration-10000 ease-out">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/60 to-dark-950/80"></div>
                    <div class="absolute inset-0 bg-dark-950/40"></div>

                    <!-- Slide Content -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-6">
                            @if($slide->subtitle)
                                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/20 border border-gold-500/40 text-gold-400 backdrop-blur-md animate-fade-in">
                                    <i class="fa-solid fa-crown text-[10px]"></i>
                                    <span>{{ $slide->subtitle }}</span>
                                </span>
                            @endif

                            <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-white leading-tight tracking-tight drop-shadow-lg">
                                {{ $slide->title }}
                            </h2>

                            @if($slide->description)
                                <p class="text-sm sm:text-base lg:text-lg text-slate-300 max-w-2xl mx-auto leading-relaxed drop-shadow-md">
                                    {{ $slide->description }}
                                </p>
                            @endif

                            <!-- CTA Buttons -->
                            <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
                                @if($slide->btn_text)
                                    <a href="{{ $slide->btn_url ?: '#custom-order' }}" class="px-8 py-3.5 rounded-xl bg-gold-gradient text-slate-950 font-bold text-sm shadow-xl shadow-gold-500/20 hover:scale-105 transition-transform flex items-center gap-2">
                                        <i class="fa-solid fa-file-signature"></i>
                                        <span>{{ $slide->btn_text }}</span>
                                    </a>
                                @endif

                                @if($slide->secondary_btn_text)
                                    <a href="{{ $slide->secondary_btn_url ?: '#portfolio' }}" class="px-8 py-3.5 rounded-xl glass-card text-white hover:bg-white/10 font-bold text-sm border border-white/20 transition flex items-center gap-2">
                                        <i class="fa-solid fa-images text-gold-400"></i>
                                        <span>{{ $slide->secondary_btn_text }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Carousel Controls: Previous & Next Buttons -->
            @if($heroSlides->count() > 1)
                <button onclick="prevSlide()" class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-4 sm:right-8' : 'left-4 sm:left-8' }} z-20 w-12 h-12 rounded-full glass-card text-white hover:text-gold-400 hover:border-gold-500/50 flex items-center justify-center transition shadow-lg">
                    <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-lg"></i>
                </button>
                <button onclick="nextSlide()" class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() === 'ar' ? 'left-4 sm:left-8' : 'right-4 sm:right-8' }} z-20 w-12 h-12 rounded-full glass-card text-white hover:text-gold-400 hover:border-gold-500/50 flex items-center justify-center transition shadow-lg">
                    <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-lg"></i>
                </button>

                <!-- Dots Indicators -->
                <div class="absolute bottom-8 inset-x-0 z-20 flex items-center justify-center gap-2.5">
                    @foreach($heroSlides as $index => $slide)
                        <button onclick="goToSlide({{ $index }})" class="slide-dot h-2.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-8 bg-gold-500' : 'w-2.5 bg-white/40 hover:bg-white/70' }}" data-index="{{ $index }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</section>

<!-- ==========================================
     2. ABOUT & CRAFTSMANSHIP & MILESTONES
     ========================================== -->
<section id="about" class="py-24 bg-dark-900 relative overflow-hidden border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Left Text Content -->
            <div class="space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                    {{ $story?->subtitle ?: (app()->getLocale() === 'ar' ? 'عراقة وجودة تليق بذوقكم' : 'Heritage & Uncompromising Quality') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    {{ $story?->title ?: (app()->getLocale() === 'ar' ? 'قصة شغف في تحويل الخشب الطبيعي إلى فن معماري' : 'A Passion for Turning Natural Hardwood into Architectural Art') }}
                </h2>
                <div class="w-16 h-1 bg-gold-500 rounded-full"></div>

                <div class="text-sm text-slate-300 leading-relaxed space-y-4">
                    @if($story?->content)
                        {!! $story->content !!}
                    @else
                        <p>
                            تأسست ورشة أرتيزان لتكون الوجهة الرائدة في المملكة العربية السعودية للأعمال الخشبية الفاخرة والمخصصة. نعتمد على أمهر الحرفيين وأحدث ماكينات التصنيع الدقيق (CNC) لنقدم لعملائنا غرف نوم ملكية، مكاتب تنفيذية، وتصاميم أجنحة معارض تحاكي أرقى المعايير العالمية.
                        </p>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <a href="{{ route('about') }}" class="px-6 py-3 rounded-xl bg-gold-gradient text-slate-950 font-bold text-xs shadow-lg shadow-gold-500/20 hover:brightness-110 transition">
                        {{ app()->getLocale() === 'ar' ? 'اقرأ المزيد عن ورشتنا' : 'Learn More About Us' }}
                    </a>
                </div>
            </div>

            <!-- Right Visual & Stats Box -->
            <div class="space-y-6">
                <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                    @php
                        $aboutImg = $story?->image ? storage_asset($story->image) : 'https://images.unsplash.com/photo-1540518614846-7ede433c4b13?auto=format&fit=crop&w=1000&q=80';
                    @endphp
                    <img src="{{ $aboutImg }}" alt="About Artisan Wood" class="w-full h-80 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-transparent to-transparent"></div>
                </div>

                <!-- Animated Milestone Counters -->
                @php
                    $counters = $stats?->meta_data ?: [
                        ['number' => '15+', 'label_ar' => 'سنوات من الخبرة', 'label_en' => 'Years Experience'],
                        ['number' => '450+', 'label_ar' => 'مشروع فاخر تم تسليمه', 'label_en' => 'Luxury Projects Delivered'],
                        ['number' => '98%', 'label_ar' => 'نسبة رضا العملاء', 'label_en' => 'Client Satisfaction'],
                        ['number' => '30+', 'label_ar' => 'حرفي وفني محترف', 'label_en' => 'Master Artisans'],
                    ];
                @endphp

                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($counters as $c)
                        <div class="glass-card p-4 rounded-2xl text-center space-y-1 w-[calc(50%-0.6rem)] sm:w-[calc(25%-0.85rem)] max-w-[160px]">
                            <span class="text-2xl sm:text-3xl font-black text-gold-gradient block font-mono">
                                {{ $c['number'] ?? '' }}
                            </span>
                            <span class="text-[11px] text-slate-400 font-semibold leading-tight block">
                                {{ app()->getLocale() === 'ar' ? ($c['label_ar'] ?? '') : ($c['label_en'] ?? $c['label_ar'] ?? '') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ==========================================
     3. SERVICES SHOWCASE SECTION
     ========================================== -->
<section id="services" class="py-24 bg-dark-950 relative border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto space-y-3 mb-16">
            <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                {{ app()->getLocale() === 'ar' ? 'ما نقدمه لعملائنا' : 'What We Offer' }}
            </span>
            <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                {{ app()->getLocale() === 'ar' ? 'خدمات وأعمال النجارة المخصصة' : 'Bespoke Joinery & Woodwork Services' }}
            </h2>
            <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full mt-2"></div>
            <p class="text-xs sm:text-sm text-slate-400 leading-relaxed pt-2">
                {{ app()->getLocale() === 'ar' ? 'نحول رؤيتكم وأفكاركم إلى تحف خشبية خالدة تجمع بين أصالة الحرفة وأحدث تقنيات التصنيع.' : 'Transforming your design vision into timeless wooden masterpieces with supreme craftsmanship.' }}
            </p>
        </div>

        <!-- Services Grid (Centered from Middle) -->
        <div class="flex flex-wrap justify-center gap-8">
            @forelse($services as $service)
                <div class="glass-card rounded-3xl overflow-hidden group hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between w-full md:w-[calc(50%-1.25rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm">
                    <div>
                        <!-- Image & Icon -->
                        <div class="h-56 relative overflow-hidden bg-dark-950">
                            @if($service->image)
                                <img src="{{ storage_asset($service->image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-80">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-wood-950 text-wood-600 text-6xl">
                                    <i class="fa-solid fa-{{ $service->icon ?: 'couch' }}"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent"></div>

                            <!-- Floating Icon -->
                            <div class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'right-4' : 'left-4' }} w-12 h-12 rounded-2xl bg-gold-gradient text-slate-950 flex items-center justify-center text-xl shadow-lg font-bold">
                                <i class="fa-solid fa-{{ $service->icon ?: 'couch' }}"></i>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-3">
                            <h3 class="text-xl font-bold text-white group-hover:text-gold-400 transition">
                                {{ $service->title }}
                            </h3>
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-3">
                                {{ $service->short_desc ?: 'تصنيع وتنفيذ مخصص بأجود أنواع الأخشاب الطبيعية والمعالجة.' }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer Link -->
                    <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02] flex items-center justify-between">
                        <a href="{{ route('services.show', $service->slug) }}" class="text-xs font-bold text-gold-400 hover:text-gold-300 flex items-center gap-1.5 transition">
                            <span>{{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة والأعمال' : 'View Service Details' }}</span>
                            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                        </a>
                        <span class="text-[11px] text-slate-500 font-mono">
                            {{ $service->portfolios()->count() }} {{ app()->getLocale() === 'ar' ? 'مشاريع' : 'Projects' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-12 text-slate-500 text-xs">
                    {{ app()->getLocale() === 'ar' ? 'سيتم إضافة الخدمات قريباً' : 'Services will be added soon.' }}
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ==========================================
     4. PORTFOLIO & PROJECTS SHOWCASE SECTION
     ========================================== -->
<section id="portfolio" class="py-24 bg-dark-900 relative border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div class="space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                    {{ app()->getLocale() === 'ar' ? 'سابقة أعمالنا وإنجازاتنا' : 'Our Masterpieces' }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                    {{ app()->getLocale() === 'ar' ? 'معرض المشاريع والأعمال المنفذة' : 'Completed Projects Showcase' }}
                </h2>
                <div class="w-16 h-1 bg-gold-500 rounded-full"></div>
            </div>
            <div>
                <a href="{{ route('portfolio.index') }}" class="px-6 py-3 rounded-xl glass-card text-white hover:bg-white/10 text-xs font-bold border border-white/20 transition inline-flex items-center gap-2">
                    <span>{{ app()->getLocale() === 'ar' ? 'تصفح كافة المشاريع' : 'Explore Full Gallery' }}</span>
                    <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-gold-500"></i>
                </a>
            </div>
        </div>

        <!-- Projects Centered Flex Grid -->
        <div class="flex flex-wrap justify-center gap-6">
            @forelse($featuredPortfolios as $item)
                <div class="glass-card rounded-2xl overflow-hidden group hover:-translate-y-1 transition duration-300 flex flex-col justify-between w-full sm:w-[calc(50%-0.85rem)] lg:w-[calc(25%-1.25rem)] max-w-sm">
                    <div>
                        <!-- Cover Image -->
                        <div class="h-48 relative overflow-hidden bg-dark-900">
                            <img src="{{ $item->main_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent"></div>

                            <!-- Badges Overlay -->
                            <div class="absolute bottom-2.5 inset-x-2.5 flex items-center justify-between">
                                @if($item->service)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-gold-500 text-slate-950">
                                        {{ $item->service->title }}
                                    </span>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    @if($item->images->count() > 0)
                                        <span class="px-1.5 py-0.5 rounded bg-black/70 text-white text-[9px] font-bold backdrop-blur-md">
                                            <i class="fa-solid fa-camera text-gold-400"></i> {{ $item->images->count() }}
                                        </span>
                                    @endif
                                    @if($item->pdfs->count() > 0)
                                        <span class="px-1.5 py-0.5 rounded bg-black/70 text-amber-300 text-[9px] font-bold backdrop-blur-md">
                                            <i class="fa-solid fa-file-pdf"></i> PDF
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 space-y-1.5">
                            <h4 class="font-bold text-white text-sm group-hover:text-gold-400 transition line-clamp-1">
                                <a href="{{ route('portfolio.show', $item->slug) }}">{{ $item->title }}</a>
                            </h4>
                            @if($item->client_name)
                                <p class="text-[11px] text-slate-400 flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-tie text-[10px] text-wood-500"></i>
                                    <span class="truncate">{{ $item->client_name }}</span>
                                    @if($item->location)
                                        <span>• {{ $item->location }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="px-4 py-3 border-t border-white/5 bg-white/[0.02] flex items-center justify-between text-xs">
                        <a href="{{ route('portfolio.show', $item->slug) }}" class="text-gold-400 hover:text-gold-300 font-bold flex items-center gap-1 text-[11px]">
                            <span>{{ app()->getLocale() === 'ar' ? 'عرض تفاصيل المشروع' : 'View Project' }}</span>
                            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[9px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-500 text-xs">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد مشاريع مضافة حالياً' : 'No projects added yet.' }}
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- ==========================================
<!-- ==========================================
     5. TESTIMONIALS SECTION (Horizontal Carousel)
     ========================================== -->
@if($testimonials->count() > 0)
<section id="testimonials" class="py-24 bg-dark-950 relative border-t border-white/5 overflow-hidden">
    <!-- Subtle Background Glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-gold-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <!-- Section Header with Slider Controls -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="space-y-3 max-w-xl">
                <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                    {{ app()->getLocale() === 'ar' ? 'شركاء النجاح والثقة' : 'Client Testimonials' }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight">
                    {{ app()->getLocale() === 'ar' ? 'ماذا يقول عملاؤنا عن دقة وفخامة أعمالنا' : 'What Our Valued Clients Say' }}
                </h2>
                <div class="w-16 h-1 bg-gold-500 rounded-full"></div>
            </div>

            <!-- Slider Navigation Controls -->
            <div class="flex items-center gap-3 self-end md:self-auto">
                <button type="button" onclick="scrollTestimonials(-1)" class="w-11 h-11 rounded-2xl bg-white/5 hover:bg-gold-500 text-slate-300 hover:text-slate-950 border border-white/10 hover:border-gold-500 flex items-center justify-center transition-all duration-300 shadow-lg active:scale-95 cursor-pointer group" title="{{ app()->getLocale() === 'ar' ? 'السابق' : 'Previous' }}">
                    <i class="fa-solid fa-arrow-right rtl:rotate-0 ltr:rotate-180 transition-transform group-hover:-translate-x-0.5"></i>
                </button>
                <button type="button" onclick="scrollTestimonials(1)" class="w-11 h-11 rounded-2xl bg-white/5 hover:bg-gold-500 text-slate-300 hover:text-slate-950 border border-white/10 hover:border-gold-500 flex items-center justify-center transition-all duration-300 shadow-lg active:scale-95 cursor-pointer group" title="{{ app()->getLocale() === 'ar' ? 'التالي' : 'Next' }}">
                    <i class="fa-solid fa-arrow-left rtl:rotate-0 ltr:rotate-180 transition-transform group-hover:translate-x-0.5"></i>
                </button>
            </div>
        </div>

        <!-- Horizontal Scrollable Track Container -->
        <div class="relative">
            <!-- Left Gradient Fade Mask -->
            <div class="hidden md:block absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-dark-950 to-transparent z-10 pointer-events-none"></div>
            <!-- Right Gradient Fade Mask -->
            <div class="hidden md:block absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-dark-950 to-transparent z-10 pointer-events-none"></div>

            <div id="testimonialsTrack" class="flex gap-6 overflow-x-auto no-scrollbar scroll-smooth py-4 px-1 snap-x snap-mandatory cursor-grab active:cursor-grabbing" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach($testimonials as $t)
                    <div class="glass-card rounded-3xl p-7 relative flex flex-col justify-between w-[310px] sm:w-[380px] lg:w-[410px] shrink-0 snap-start border border-white/10 hover:border-gold-500/50 hover:shadow-xl hover:shadow-gold-500/5 transition-all duration-300 group">
                        <!-- Watermark Quote Icon -->
                        <i class="fa-solid fa-quote-left absolute top-6 end-6 text-3xl text-gold-500/10 group-hover:text-gold-500/20 transition-colors"></i>

                        <div class="space-y-4">
                            <!-- Rating Stars & Verified Tag -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1 text-gold-500 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $t->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                                <span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20 flex items-center gap-1 font-bold">
                                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                                    <span>{{ app()->getLocale() === 'ar' ? 'رأي موثق' : 'Verified' }}</span>
                                </span>
                            </div>

                            <!-- Comment Text -->
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed italic line-clamp-4 group-hover:text-white transition-colors">
                                "{{ $t->comment }}"
                            </p>
                        </div>

                        <!-- Client Info -->
                        <div class="flex items-center gap-3.5 pt-5 mt-4 border-t border-white/5">
                            <img src="{{ $t->avatar_url }}" alt="{{ $t->client_name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-gold-500/30 group-hover:ring-gold-500 transition-all flex-shrink-0">
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-white text-sm truncate">{{ $t->client_name }}</h4>
                                <p class="text-[11px] text-gold-400 font-medium truncate">
                                    {{ $t->position }} {{ $t->company ? ' - ' . $t->company : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Carousel Dots / Progress Indicator -->
        <div id="testimonialsDots" class="flex items-center justify-center gap-2 mt-8">
            @foreach($testimonials as $idx => $t)
                <button type="button" onclick="scrollTestimonialsToIndex({{ $idx }})" class="testimonial-dot h-2 rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-8 bg-gold-500' : 'w-2 bg-white/20 hover:bg-white/40' }}" title="{{ $t->client_name }}"></button>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ==========================================
     6. CUSTOM ORDER CTA SECTION (Call to Action)
     ========================================== -->
<section id="custom-order" class="py-20 relative bg-gradient-to-r from-wood-950 via-dark-950 to-wood-950 border-t border-gold-500/20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-center space-y-6">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold bg-gold-500/20 text-gold-400 border border-gold-500/30">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            <span>{{ app()->getLocale() === 'ar' ? 'تفصيل حسب المقاس والمواصفات' : 'Custom Dimensions & Specifications' }}</span>
        </span>

        <h2 class="text-3xl sm:text-5xl font-black text-white leading-tight">
            {{ app()->getLocale() === 'ar' ? 'هل لديك تصميم أو مشروع خشبي ترغب في تنفيذه؟' : 'Have a Bespoke Woodwork Design in Mind?' }}
        </h2>

        <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
            {{ app()->getLocale() === 'ar' ? 'فريقنا الهندسي وحرفيونا المتمرسون مستعدون لدراسة مخططاتكم وتقديم أفضل الحلول الخشبية بأعلى دقة وضمان معتمد.' : 'Our master craftsmen are ready to review your blueprints and craft bespoke woodwork with guaranteed warranty.' }}
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 pt-4">
            @if($wa = \App\Models\Setting::get('whatsapp'))
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ urlencode(app()->getLocale() === 'ar' ? 'مرحباً، أود إرسال مخططات وطلب تسعيرة لتفصيل أعمال خشبية' : 'Hello, I want to send blueprints and request a quote for woodwork') }}" target="_blank"
                    class="px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-xl shadow-emerald-600/30 transition flex items-center gap-2.5">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'تواصل عبر واتساب مباشرة' : 'Instant WhatsApp Quote' }}</span>
                </a>
            @endif

            @if($phone = \App\Models\Setting::get('phone'))
                <a href="tel:{{ $phone }}" class="px-8 py-4 rounded-2xl glass-card text-white hover:bg-white/10 font-bold text-sm border border-white/20 transition flex items-center gap-2.5">
                    <i class="fa-solid fa-phone text-gold-400"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'اتصال هاتفي مباشر' : 'Call Directly' }}</span>
                </a>
            @endif
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Pure Vanilla JS Hero Slides Carousel Controller
    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slide-dot');
    let slideInterval = null;

    function showSlide(index) {
        if (!slides.length) return;

        // Wrap index
        if (index >= slides.length) currentSlide = 0;
        else if (index < 0) currentSlide = slides.length - 1;
        else currentSlide = index;

        slides.forEach((s, idx) => {
            if (idx === currentSlide) {
                s.classList.remove('opacity-0', 'z-0', 'pointer-events-none');
                s.classList.add('opacity-100', 'z-10');
            } else {
                s.classList.remove('opacity-100', 'z-10');
                s.classList.add('opacity-0', 'z-0', 'pointer-events-none');
            }
        });

        dots.forEach((d, idx) => {
            if (idx === currentSlide) {
                d.classList.add('w-8', 'bg-gold-500');
                d.classList.remove('w-2.5', 'bg-white/40');
            } else {
                d.classList.remove('w-8', 'bg-gold-500');
                d.classList.add('w-2.5', 'bg-white/40');
            }
        });
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function goToSlide(index) {
        showSlide(index);
        restartSlideTimer();
    }

    function startSlideTimer() {
        if (slides.length > 1) {
            slideInterval = setInterval(nextSlide, 6000);
        }
    }

    function restartSlideTimer() {
        clearInterval(slideInterval);
        startSlideTimer();
    }

    document.addEventListener('DOMContentLoaded', function() {
        startSlideTimer();

        const carousel = document.getElementById('heroCarousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
            carousel.addEventListener('mouseleave', () => startSlideTimer());
        }

        initTestimonialsSlider();
    });

    /* =========================================================================
       TESTIMONIALS HORIZONTAL SLIDER CONTROLLER
       ========================================================================= */
    let testimonialAutoPlayInterval = null;

    function getCardStepWidth() {
        const track = document.getElementById('testimonialsTrack');
        if (!track) return 350;
        const firstCard = track.querySelector('.glass-card');
        return firstCard ? firstCard.offsetWidth + 24 : 350;
    }

    function scrollTestimonials(direction) {
        const track = document.getElementById('testimonialsTrack');
        if (!track) return;
        const step = getCardStepWidth() * direction;
        // Check RTL direction
        const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
        const scrollAmount = isRtl ? -step : step;
        
        track.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
        restartTestimonialAutoPlay();
    }

    function scrollTestimonialsToIndex(index) {
        const track = document.getElementById('testimonialsTrack');
        if (!track) return;
        const cards = track.querySelectorAll('.glass-card');
        if (cards[index]) {
            cards[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            updateTestimonialDots(index);
        }
        restartTestimonialAutoPlay();
    }

    function updateTestimonialDots(activeIndex) {
        const dots = document.querySelectorAll('.testimonial-dot');
        dots.forEach((dot, idx) => {
            if (idx === activeIndex) {
                dot.classList.add('w-8', 'bg-gold-500');
                dot.classList.remove('w-2', 'bg-white/20');
            } else {
                dot.classList.remove('w-8', 'bg-gold-500');
                dot.classList.add('w-2', 'bg-white/20');
            }
        });
    }

    function initTestimonialsSlider() {
        const track = document.getElementById('testimonialsTrack');
        if (!track) return;

        // Auto update active dot on scroll
        track.addEventListener('scroll', () => {
            const cards = track.querySelectorAll('.glass-card');
            const trackRect = track.getBoundingClientRect();
            let closestIdx = 0;
            let minDistance = Infinity;

            cards.forEach((card, idx) => {
                const cardRect = card.getBoundingClientRect();
                const distance = Math.abs((cardRect.left + cardRect.width / 2) - (trackRect.left + trackRect.width / 2));
                if (distance < minDistance) {
                    minDistance = distance;
                    closestIdx = idx;
                }
            });

            updateTestimonialDots(closestIdx);
        }, { passive: true });

        // Auto-play horizontal scroll
        function startAutoPlay() {
            if (track.children.length > 1) {
                testimonialAutoPlayInterval = setInterval(() => {
                    const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
                    const maxScroll = track.scrollWidth - track.clientWidth;
                    const atEnd = Math.abs(track.scrollLeft) >= maxScroll - 20;

                    if (atEnd) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        const step = getCardStepWidth();
                        track.scrollBy({ left: isRtl ? -step : step, behavior: 'smooth' });
                    }
                }, 4500);
            }
        }

        startAutoPlay();

        track.addEventListener('mouseenter', () => clearInterval(testimonialAutoPlayInterval));
        track.addEventListener('mouseleave', () => startAutoPlay());
        track.addEventListener('touchstart', () => clearInterval(testimonialAutoPlayInterval), { passive: true });
        track.addEventListener('touchend', () => startAutoPlay(), { passive: true });
    }

    function restartTestimonialAutoPlay() {
        clearInterval(testimonialAutoPlayInterval);
        const track = document.getElementById('testimonialsTrack');
        if (track && track.children.length > 1) {
            testimonialAutoPlayInterval = setInterval(() => {
                const isRtl = document.documentElement.dir === 'rtl' || document.documentElement.lang === 'ar';
                const maxScroll = track.scrollWidth - track.clientWidth;
                const atEnd = Math.abs(track.scrollLeft) >= maxScroll - 20;

                if (atEnd) {
                    track.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    const step = getCardStepWidth();
                    track.scrollBy({ left: isRtl ? -step : step, behavior: 'smooth' });
                }
            }, 4500);
        }
    }
</script>
@endpush
