@extends('frontend.layouts.app')

@section('title', $portfolio->title . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))
@section('meta_description', Str::limit(strip_tags($portfolio->description), 160) ?: $portfolio->title)

@section('content')
<!-- Project Banner Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 space-y-4 relative z-10 text-center">
        <a href="{{ route('portfolio.index') }}" class="inline-flex items-center gap-2 text-xs font-bold text-gold-400 hover:text-gold-300 transition mb-2">
            <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"></i>
            <span>{{ app()->getLocale() === 'ar' ? 'العودة لمعرض المشاريع' : 'Back to Portfolio' }}</span>
        </a>
        @if($portfolio->service)
            <div class="inline-block">
                <a href="{{ route('services.show', $portfolio->service->slug) }}" class="px-3 py-1 rounded-full text-xs font-bold bg-gold-500/10 text-gold-400 border border-gold-500/30">
                    {{ $portfolio->service->title }}
                </a>
            </div>
        @endif
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ $portfolio->title }}
        </h1>
    </div>
</div>

<!-- Project Details Content -->
<div class="py-20 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        <!-- Main Info Bar & Cover -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left 2 Cols: Main Cover -->
            <div class="lg:col-span-2 rounded-3xl overflow-hidden border border-white/10 shadow-2xl bg-dark-950">
                <img src="{{ $portfolio->main_image_url }}" alt="{{ $portfolio->title }}" class="w-full max-h-[520px] object-cover">
            </div>

            <!-- Right Col: Project Meta Information -->
            <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3">
                    {{ app()->getLocale() === 'ar' ? 'بيانات ومواصفات المشروع' : 'Project Details' }}
                </h3>

                <div class="space-y-4 text-xs sm:text-sm">
                    @if($portfolio->service)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">{{ app()->getLocale() === 'ar' ? 'القسم / الخدمة' : 'Service' }}</span>
                            <span class="font-bold text-gold-400">{{ $portfolio->service->title }}</span>
                        </div>
                    @endif

                    @if($portfolio->client_name)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">{{ app()->getLocale() === 'ar' ? 'العميل / الجهة' : 'Client' }}</span>
                            <span class="font-bold text-white">{{ $portfolio->client_name }}</span>
                        </div>
                    @endif

                    @if($portfolio->location)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">{{ app()->getLocale() === 'ar' ? 'الموقع' : 'Location' }}</span>
                            <span class="font-bold text-white">{{ $portfolio->location }}</span>
                        </div>
                    @endif

                    @if($portfolio->completion_date)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400">{{ app()->getLocale() === 'ar' ? 'تاريخ التسليم' : 'Completion Date' }}</span>
                            <span class="font-bold text-white font-mono">{{ $portfolio->completion_date->format('Y-m-d') }}</span>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-white/10 space-y-3">
                    @if($wa = \App\Models\Setting::get('whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ urlencode(app()->getLocale() === 'ar' ? 'مرحباً، أود الاستفسار وطلب تفصيل تصميم مشابه لمشروع: ' . $portfolio->title : 'Hello, I want to inquire about a similar design to: ' . $portfolio->title) }}" target="_blank"
                            class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg transition flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-base"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'طلب تنفيذ عمل مشابه' : 'Request Similar Project' }}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description -->
        @if($portfolio->description)
            <div class="glass-card rounded-3xl p-8 space-y-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'عن المشروع وتفاصيل التنفيذ' : 'About This Project' }}</span>
                </h3>
                <div class="text-slate-300 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                    {{ $portfolio->description }}
                </div>
            </div>
        @endif

        <!-- Video Showcase -->
        @if($portfolio->video_url)
            <div class="glass-card rounded-3xl p-8 space-y-6">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fa-brands fa-youtube text-red-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'فيديو توثيقي للمشروع' : 'Project Video' }}</span>
                </h3>
                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black">
                    @if(str_contains($portfolio->video_url, 'youtube.com') || str_contains($portfolio->video_url, 'youtu.be'))
                        @php
                            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $portfolio->video_url, $matches);
                            $ytId = $matches[1] ?? '';
                        @endphp
                        @if($ytId)
                            <iframe src="https://www.youtube.com/embed/{{ $ytId }}" class="w-full h-full" allowfullscreen></iframe>
                        @else
                            <iframe src="{{ $portfolio->video_url }}" class="w-full h-full" allowfullscreen></iframe>
                        @endif
                    @else
                        <video controls class="w-full h-full object-cover">
                            <source src="{{ $portfolio->video_url }}" type="video/mp4">
                        </video>
                    @endif
                </div>
            </div>
        @endif

        <!-- Gallery Images -->
        @if($portfolio->images->count() > 0)
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-camera text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'ألبوم صور المشروع' : 'Photo Gallery' }} ({{ $portfolio->images->count() }})</span>
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($portfolio->images as $img)
                        <div onclick="openLightbox('{{ asset('storage/' . $img->file_path) }}')" class="glass-card rounded-2xl overflow-hidden aspect-square cursor-pointer group relative">
                            <img src="{{ asset('storage/' . $img->file_path) }}" alt="Project Photo" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xl">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- PDF Documents / Blueprints Downloads -->
        @if($portfolio->pdfs->count() > 0)
            <div class="space-y-6">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'المخططات الهندسية والكتالوجات المرفقة (PDF)' : 'PDF Blueprints & Catalogs' }}</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($portfolio->pdfs as $pdf)
                        <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-red-500/10 border border-red-500/30 flex items-center justify-center text-red-500 text-lg">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white text-xs sm:text-sm truncate">{{ $pdf->file_name ?: 'المخطط الهندسي للمشروع' }}</h4>
                                    <p class="text-[11px] text-slate-500 font-mono">{{ $pdf->file_size ?: 'PDF' }}</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $pdf->file_path) }}" target="_blank" download class="px-4 py-2 rounded-xl bg-gold-gradient text-slate-950 text-xs font-bold hover:brightness-110 transition flex items-center gap-1.5">
                                <i class="fa-solid fa-download"></i>
                                <span>{{ app()->getLocale() === 'ar' ? 'تحميل' : 'Download' }}</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Projects -->
        @if($relatedPortfolios->count() > 0)
            <div class="space-y-6 pt-6 border-t border-white/10">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-images text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'مشاريع وأعمال مشابهة' : 'Similar Projects' }}</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedPortfolios as $other)
                        <a href="{{ route('portfolio.show', $other->slug) }}" class="glass-card rounded-2xl overflow-hidden group hover:-translate-y-1 transition duration-300 block">
                            <div class="h-44 relative overflow-hidden bg-dark-950">
                                <img src="{{ $other->main_image_url }}" alt="{{ $other->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-4">
                                <h4 class="font-bold text-white text-sm group-hover:text-gold-400 transition line-clamp-1">{{ $other->title }}</h4>
                                @if($other->client_name)
                                    <p class="text-[11px] text-slate-400 mt-1 truncate">{{ $other->client_name }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Lightbox Modal for Gallery Images -->
<div id="lightboxModal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md hidden items-center justify-center p-4" onclick="closeLightbox()">
    <div class="relative max-w-5xl max-h-[90vh]">
        <img id="lightboxImg" src="" class="max-w-full max-h-[85vh] rounded-2xl object-contain shadow-2xl">
        <button onclick="closeLightbox()" class="absolute -top-12 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} text-white text-2xl hover:text-gold-400 p-2">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openLightbox(src) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxModal').classList.remove('hidden');
        document.getElementById('lightboxModal').classList.add('flex');
    }
    function closeLightbox() {
        document.getElementById('lightboxModal').classList.add('hidden');
        document.getElementById('lightboxModal').classList.remove('flex');
    }
</script>
@endpush
