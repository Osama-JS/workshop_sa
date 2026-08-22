@extends('admin.layouts.master')

@section('title', __('admin.menu_hero_slides'))

@section('page_icon')
    <i class="fa-solid fa-panorama text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_hero_slides'))
@section('page_subtitle', 'إدارة شرائح البداية المتحركة للواجهة الرئيسية (Hero Slides) والصور والخلفيات والأزرار')

@section('page_actions')
    <a href="{{ route('admin.hero-slides.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
        <i class="fa-solid fa-plus"></i>
        <span>إضافة شريحة عرض جديدة</span>
    </a>
@endsection

@section('content')
<!-- Hero Settings Quick Bar -->
<div class="bg-gradient-to-r from-wood-900 to-dark-900 rounded-2xl p-5 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="space-y-1">
        <h3 class="font-bold text-base flex items-center gap-2">
            <i class="fa-solid fa-sliders text-gold-400"></i>
            <span>تخصيص نوع قسم البداية (Hero Section Type)</span>
        </h3>
        <p class="text-xs text-wood-200">
            يمكنك اختيار ما إذا كنت تريد عرض **سلايدر شرائح متحركة**، أو **فيديو خلفية تفاعلي**، أو **صورة ثابتة** من صفحة الإعدادات.
        </p>
    </div>
    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-wood-600 hover:bg-wood-500 text-white text-xs font-bold rounded-xl transition shadow-md whitespace-nowrap">
        <i class="fa-solid fa-gear ml-1"></i> ضبط نوع الـ Hero في الإعدادات
    </a>
</div>

<!-- Slides Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    @forelse($slides as $slide)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <!-- Slide Preview Box -->
                <div class="h-52 bg-slate-900 relative overflow-hidden group">
                    <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-70">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/40 to-transparent"></div>

                    <!-- Badges -->
                    <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} flex items-center gap-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $slide->is_active ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                            {{ $slide->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-dark-900/80 text-wood-400 border border-wood-600/40">
                            الترتيب: {{ $slide->sort_order }}
                        </span>
                    </div>

                    <!-- Slide Content Overlay Preview -->
                    <div class="absolute bottom-4 inset-x-4 space-y-1">
                        @if($slide->subtitle_ar)
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gold-400 block">{{ $slide->subtitle_ar }}</span>
                        @endif
                        <h4 class="font-bold text-white text-sm leading-snug line-clamp-2">{{ $slide->title_ar }}</h4>
                    </div>
                </div>

                <!-- Text Info -->
                <div class="p-5 space-y-3">
                    <p class="text-xs text-wood-600 font-sans font-medium line-clamp-1">{{ $slide->title_en }}</p>
                    <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{{ $slide->description_ar ?: 'لا يوجد وصف' }}</p>

                    <!-- Buttons details -->
                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-[11px]">
                        @if($slide->btn_text_ar)
                            <span class="px-2.5 py-1 rounded-lg bg-wood-50 text-wood-800 font-bold flex items-center gap-1">
                                <i class="fa-solid fa-arrow-left text-[9px]"></i> {{ $slide->btn_text_ar }}
                            </span>
                        @endif
                        @if($slide->secondary_btn_text_ar)
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                {{ $slide->secondary_btn_text_ar }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.hero-slides.edit', $slide->id) }}" class="p-2 rounded-lg bg-white border border-slate-200 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.hero-slides.destroy', $slide->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="p-2 rounded-lg bg-white border border-slate-200 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition confirm-delete" title="{{ __('admin.delete') }}">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-400 text-xs border border-slate-200">
            {{ __('admin.no_records') }}
        </div>
    @endforelse
</div>

@if($slides->hasPages())
    <div class="mt-6">
        {{ $slides->links() }}
    </div>
@endif
@endsection
