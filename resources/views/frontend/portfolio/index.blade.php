@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'معرض المشاريع والأعمال' : 'Portfolio Gallery') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'أعمال نفتخر بإنجازها' : 'Our Masterpieces' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'معرض المشاريع والأعمال الخشبية' : 'Portfolio & Completed Projects' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto">
            {{ app()->getLocale() === 'ar' ? 'استكشف صور ومقاطع فيديو ومخططات المشاريع المنفذة لغرف النوم والمكاتب والبوثات.' : 'Explore photos, videos, and blueprints of our custom woodwork projects.' }}
        </p>
    </div>
</div>

<!-- Portfolio Grid & Filter Tabs -->
<div class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <!-- Service Filter Buttons -->
        <div class="flex flex-wrap items-center justify-center gap-2">
            <a href="{{ route('portfolio.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition {{ !request('service') ? 'bg-gold-gradient text-slate-950 shadow-md' : 'glass-card text-slate-300 hover:text-white' }}">
                {{ app()->getLocale() === 'ar' ? 'جميع المشاريع' : 'All Projects' }}
            </a>
            @foreach($services as $s)
                <a href="{{ route('portfolio.index', ['service' => $s->slug]) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold transition {{ request('service') === $s->slug ? 'bg-gold-gradient text-slate-950 shadow-md' : 'glass-card text-slate-300 hover:text-white' }}">
                    {{ $s->title }}
                </a>
            @endforeach
        </div>

        <!-- Projects Centered Flex Grid -->
        <div class="flex flex-wrap justify-center gap-8">
            @forelse($portfolios as $item)
                <div class="glass-card rounded-3xl overflow-hidden group hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between w-full md:w-[calc(50%-1.25rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm">
                    <div>
                        <!-- Cover Image -->
                        <div class="h-60 relative overflow-hidden bg-dark-950">
                            <img src="{{ $item->main_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-transparent to-transparent"></div>

                            <!-- Badges Overlay -->
                            <div class="absolute bottom-3 inset-x-3 flex items-center justify-between">
                                @if($item->service)
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-gold-500 text-slate-950">
                                        {{ $item->service->title }}
                                    </span>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    @if($item->images->count() > 0)
                                        <span class="px-2 py-1 rounded-lg bg-black/70 text-white text-[10px] font-bold backdrop-blur-md">
                                            <i class="fa-solid fa-camera text-gold-400"></i> {{ $item->images->count() }}
                                        </span>
                                    @endif
                                    @if($item->video_url)
                                        <span class="px-2 py-1 rounded-lg bg-black/70 text-red-400 text-[10px] font-bold backdrop-blur-md">
                                            <i class="fa-brands fa-youtube"></i> فيديو
                                        </span>
                                    @endif
                                    @if($item->pdfs->count() > 0)
                                        <span class="px-2 py-1 rounded-lg bg-black/70 text-amber-300 text-[10px] font-bold backdrop-blur-md">
                                            <i class="fa-solid fa-file-pdf"></i> PDF
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6 space-y-2">
                            <h3 class="font-bold text-white text-base group-hover:text-gold-400 transition leading-snug">
                                <a href="{{ route('portfolio.show', $item->slug) }}">{{ $item->title }}</a>
                            </h3>
                            @if($item->client_name)
                                <p class="text-xs text-slate-400 flex items-center gap-1.5 pt-1">
                                    <i class="fa-solid fa-user-tie text-wood-500"></i>
                                    <span>{{ $item->client_name }}</span>
                                    @if($item->location)
                                        <span>• {{ $item->location }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="px-6 py-4 border-t border-white/5 bg-white/[0.02] flex items-center justify-between">
                        <a href="{{ route('portfolio.show', $item->slug) }}" class="text-xs font-bold text-gold-400 hover:text-gold-300 flex items-center gap-1.5 transition">
                            <span>{{ app()->getLocale() === 'ar' ? 'عرض تفاصيل المشروع والألبوم' : 'View Project & Gallery' }}</span>
                            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="w-full text-center py-16 text-slate-500 text-xs">
                    {{ app()->getLocale() === 'ar' ? 'لا توجد مشاريع مضافة في هذا القسم' : 'No projects found in this category.' }}
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($portfolios->hasPages())
            <div class="pt-8">
                {{ $portfolios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
