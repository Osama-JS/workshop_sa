@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'خدمات وأعمال النجارة' : 'Our Services') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'مجالات إبداعنا' : 'Our Expertise' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'خدمات وأعمال النجارة الفاخرة' : 'Bespoke Woodwork & Joinery Services' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto">
            {{ app()->getLocale() === 'ar' ? 'نقدم حلولاً خشبية متكاملة للمشاريع السكنية والتجارية والمعارض وفق أعلى معايير الجودة والحرفية.' : 'Comprehensive woodwork solutions for residential, commercial, and exhibition projects.' }}
        </p>
    </div>
</div>

<!-- Services Grid -->
<div class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-8">
            @forelse($services as $service)
                <div class="glass-card rounded-3xl overflow-hidden group hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between w-full md:w-[calc(50%-1.25rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm">
                    <div>
                        <!-- Image & Icon -->
                        <div class="h-60 relative overflow-hidden bg-dark-950">
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
                            <span>{{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'View Service' }}</span>
                            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                        </a>
                        <span class="text-[11px] text-slate-500 font-mono">
                            {{ $service->portfolios()->count() }} {{ app()->getLocale() === 'ar' ? 'مشاريع' : 'Projects' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-12 text-slate-500 text-xs">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد خدمات متاحة حالياً' : 'No services available at the moment.' }}
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
