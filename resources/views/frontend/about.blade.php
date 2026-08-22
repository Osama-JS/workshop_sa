@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'من نحن وتاريخ الورشة' : 'About Us') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'أصالة وحرفية سعودية' : 'Authentic Saudi Craftsmanship' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'من نحن وقصة ورشتنا' : 'About Our Workshop & Heritage' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto">
            {{ app()->getLocale() === 'ar' ? 'تاريخ ممتد من الشغف والإتقان في صناعة وتفصيل أفخر الأعمال الخشبية والديكورات.' : 'A legacy of passion and master craftsmanship in luxury woodwork.' }}
        </p>
    </div>
</div>

<div class="py-20 bg-dark-900 space-y-20">
    <!-- Story Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
                    {{ $story?->subtitle ?: (app()->getLocale() === 'ar' ? 'البداية والشغف' : 'The Beginning & Passion') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-black text-white leading-tight">
                    {{ $story?->title ?: (app()->getLocale() === 'ar' ? 'قصة تأسيس ورشة أرتيزان للأعمال الخشبية' : 'The Story Behind Artisan Woodcraft') }}
                </h2>
                <div class="w-16 h-1 bg-gold-500 rounded-full"></div>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base space-y-4">
                    {!! $story?->content ?: '<p>انطلقنا برؤية طموحة لتقديم أثاث وأعمال خشبية تفوق التوقعات، حيث نجمع بين أفضل أنواع الخشب الطبيعي وتقنيات التصنيع الحديثة واللمسات اليدوية المتقنة.</p>' !!}
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                @php
                    $aboutImg = $story?->image ? asset('storage/' . $story->image) : 'https://images.unsplash.com/photo-1540518614846-7ede433c4b13?auto=format&fit=crop&w=1000&q=80';
                @endphp
                <img src="{{ $aboutImg }}" alt="Our Story" class="w-full h-96 object-cover">
            </div>
        </div>
    </div>

    <!-- Vision & Mission Section -->
    @if($vision)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-card rounded-3xl p-8 sm:p-12 space-y-8 border-gold-500/20">
                <div class="text-center max-w-2xl mx-auto space-y-3">
                    <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">{{ $vision->subtitle }}</span>
                    <h3 class="text-3xl font-black text-white">{{ $vision->title }}</h3>
                    <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full"></div>
                </div>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base prose prose-invert max-w-none text-center">
                    {!! $vision->content !!}
                </div>
            </div>
        </div>
    @endif

    <!-- Milestones & Numbers -->
    @php
        $counters = $stats?->meta_data ?: [
            ['number' => '15+', 'label_ar' => 'سنوات من الخبرة', 'label_en' => 'Years Experience'],
            ['number' => '450+', 'label_ar' => 'مشروع فاخر تم تسليمه', 'label_en' => 'Luxury Projects Delivered'],
            ['number' => '98%', 'label_ar' => 'نسبة رضا العملاء', 'label_en' => 'Client Satisfaction'],
            ['number' => '30+', 'label_ar' => 'حرفي وفني محترف', 'label_en' => 'Master Artisans'],
        ];
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @foreach($counters as $c)
                <div class="glass-card p-6 rounded-3xl text-center space-y-2">
                    <span class="text-3xl sm:text-4xl font-black text-gold-gradient block font-mono">
                        {{ $c['number'] ?? '' }}
                    </span>
                    <span class="text-xs sm:text-sm text-slate-300 font-bold block">
                        {{ app()->getLocale() === 'ar' ? ($c['label_ar'] ?? '') : ($c['label_en'] ?? $c['label_ar'] ?? '') }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
