@extends('frontend.layouts.app')

@section('title', $service->title . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))
@section('meta_description', $service->short_desc ?: $service->title)

@section('content')
<!-- Service Banner Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-4 relative z-10 text-center">
        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gold-400 hover:text-gold-300 transition mb-2">
            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            <span>{{ app()->getLocale() === 'ar' ? 'العودة لقائمة الخدمات' : 'Back to Services' }}</span>
        </a>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ $service->title }}
        </h1>
        @if($service->short_desc)
            <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                {{ $service->short_desc }}
            </p>
        @endif
    </div>
</div>

<!-- Main Content & Sidebar -->
<div class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left: Main Body Details -->
            <div class="lg:col-span-2 space-y-10">
                <!-- Main Cover Image -->
                @if($service->image)
                    <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="{{ storage_asset($service->image) }}" alt="{{ $service->title }}" class="w-full max-h-[480px] object-cover">
                    </div>
                @endif

                <!-- Detailed Rich Text Content -->
                <div class="glass-card rounded-3xl p-8 space-y-6 text-slate-300 leading-relaxed text-sm sm:text-base prose prose-invert max-w-none">
                    {!! $service->content ?: '<p>نقدم خدمة ' . $service->title . ' وفق أعلى مقاييس النجارة والحرفية السعودية، مع إمكانية تفصيل التصاميم حسب المقاسات والمخططات الهندسية بدقة متناهية.</p>' !!}
                </div>

                <!-- Related Projects for this Service -->
                @if($relatedPortfolios->count() > 0)
                    <div class="space-y-6 pt-6">
                        <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                            <i class="fa-solid fa-images text-gold-500"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'مشاريع وأعمال منفذة في هذه الخدمة' : 'Projects in this Service' }}</span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach($relatedPortfolios as $item)
                                <a href="{{ route('portfolio.show', $item->slug) }}" class="glass-card rounded-2xl overflow-hidden group hover:-translate-y-1 transition duration-300 block">
                                    <div class="h-44 relative overflow-hidden bg-dark-950">
                                        <img src="{{ $item->main_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-bold text-white text-sm group-hover:text-gold-400 transition">{{ $item->title }}</h4>
                                        @if($item->client_name)
                                            <p class="text-[11px] text-slate-400 mt-1">{{ $item->client_name }}</p>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right: Service Sidebar & Fast Request Box -->
            <div class="space-y-8">
                <!-- Quote Request Box -->
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6 border-gold-500/30">
                    <div class="space-y-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gold-400 block">
                            {{ app()->getLocale() === 'ar' ? 'طلب تفصيل مباشر' : 'Fast Order' }}
                        </span>
                        <h3 class="text-xl font-black text-white">
                            {{ app()->getLocale() === 'ar' ? 'هل ترغب في طلب هذه الخدمة؟' : 'Interested in this Service?' }}
                        </h3>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            {{ app()->getLocale() === 'ar' ? 'تواصل مع فريقنا الآن للحصول على تسعيرة فورية ومناقشة المقاسات والمخططات.' : 'Contact our team for an instant quote and technical review.' }}
                        </p>
                    </div>

                    <div class="space-y-3">
                        @if($wa = \App\Models\Setting::get('whatsapp'))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ urlencode(app()->getLocale() === 'ar' ? 'مرحباً، أود الاستفسار عن تفصيل: ' . $service->title : 'Hello, I want to inquire about: ' . $service->title) }}" target="_blank"
                                class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition flex items-center justify-center gap-2">
                                <i class="fa-brands fa-whatsapp text-base"></i>
                                <span>{{ app()->getLocale() === 'ar' ? 'مراسلة عبر واتساب' : 'WhatsApp Inquiry' }}</span>
                            </a>
                        @endif

                        <a href="{{ route('order.create') }}" class="w-full py-3.5 px-4 rounded-xl bg-gold-gradient text-slate-950 font-bold text-xs shadow-lg hover:brightness-110 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-file-signature"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'تعبئة نموذج طلب تفصيل' : 'Request Custom Quote' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Other Services List -->
                @if($allServices->count() > 0)
                    <div class="glass-card rounded-3xl p-6 space-y-4">
                        <h4 class="text-sm font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
                            <i class="fa-solid fa-couch text-gold-500 text-xs"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'خدمات خشبية أخرى' : 'Other Services' }}</span>
                        </h4>
                        <div class="space-y-2">
                            @foreach($allServices as $other)
                                <a href="{{ route('services.show', $other->slug) }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-white/5 transition text-xs font-semibold text-slate-300 hover:text-gold-400">
                                    <div class="flex items-center gap-2.5">
                                        <i class="fa-solid fa-{{ $other->icon ?: 'couch' }} text-wood-500"></i>
                                        <span>{{ $other->title }}</span>
                                    </div>
                                    <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-slate-500"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
