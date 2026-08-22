@extends('admin.layouts.master')

@section('title', __('admin.menu_portfolio'))

@section('page_icon')
    <i class="fa-solid fa-images text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_portfolio'))
@section('page_subtitle', 'معرض مشاريع وأعمال الورشة، رفع الفيديوهات والمخططات الهندسية PDF')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('portfolios.create'))
        <a href="{{ route('admin.portfolios.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة مشروع جديد</span>
        </a>
    @endif
@endsection

@section('content')
<!-- Filter & Search Bar -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-6">
    <form method="GET" action="{{ route('admin.portfolios.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
        </div>
        <div>
            <select name="service_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                <option value="">{{ __('admin.all') }} - الخدمات</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ request('service_id') == $s->id ? 'selected' : '' }}>{{ $s->title_ar }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                <option value="">{{ __('admin.all') }} - {{ __('admin.status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-filter ml-1"></i> تصفية
            </button>
            @if(request()->anyFilled(['search', 'service_id', 'status']))
                <a href="{{ route('admin.portfolios.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="إعادة تعيين">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Portfolio Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($portfolios as $portfolio)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <!-- Cover Image -->
                <div class="h-48 bg-slate-900 relative overflow-hidden group">
                    <img src="{{ $portfolio->main_image_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} flex items-center gap-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $portfolio->is_active ? 'bg-emerald-500/90 text-white backdrop-blur-md' : 'bg-rose-500/90 text-white backdrop-blur-md' }}">
                            {{ $portfolio->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        @if($portfolio->is_featured)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-gold-500 text-slate-950 backdrop-blur-md">
                                <i class="fa-solid fa-star text-[9px]"></i> مميز
                            </span>
                        @endif
                    </div>

                    <!-- Media Badges Count Overlay -->
                    <div class="absolute bottom-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} flex items-center gap-2">
                        @if($portfolio->images->count() > 0)
                            <span class="px-2 py-1 rounded-lg bg-black/60 text-white text-[10px] font-bold backdrop-blur-md flex items-center gap-1">
                                <i class="fa-solid fa-image text-wood-400"></i> {{ $portfolio->images->count() }}
                            </span>
                        @endif
                        @if($portfolio->video_url)
                            <span class="px-2 py-1 rounded-lg bg-black/60 text-white text-[10px] font-bold backdrop-blur-md flex items-center gap-1">
                                <i class="fa-brands fa-youtube text-red-500"></i> فيديو
                            </span>
                        @endif
                        @if($portfolio->pdfs->count() > 0)
                            <span class="px-2 py-1 rounded-lg bg-black/60 text-white text-[10px] font-bold backdrop-blur-md flex items-center gap-1">
                                <i class="fa-solid fa-file-pdf text-amber-400"></i> {{ $portfolio->pdfs->count() }} PDF
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="p-5 space-y-2">
                    @if($portfolio->service)
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-wood-50 text-wood-800 inline-block">
                            {{ $portfolio->service->title_ar }}
                        </span>
                    @endif
                    <h3 class="font-bold text-slate-900 text-base leading-snug">
                        {{ $portfolio->title_ar }}
                    </h3>
                    <p class="text-xs text-slate-400 font-sans">{{ $portfolio->title_en }}</p>
                    @if($portfolio->client_name)
                        <div class="text-xs text-slate-500 flex items-center gap-2 pt-1">
                            <i class="fa-solid fa-user-tie text-slate-400 text-[10px]"></i>
                            <span>{{ $portfolio->client_name }}</span>
                            @if($portfolio->location)
                                <span>• {{ $portfolio->location }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400 font-mono">
                    {{ $portfolio->completion_date ? $portfolio->completion_date->format('Y-m-d') : '' }}
                </span>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.portfolios.edit', $portfolio->id) }}" class="p-2 rounded-lg bg-white border border-slate-200 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.portfolios.destroy', $portfolio->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="p-2 rounded-lg bg-white border border-slate-200 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition confirm-delete" title="{{ __('admin.delete') }}">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-2xl p-12 text-center text-slate-400 text-xs border border-slate-200">
            {{ __('admin.no_records') }}
        </div>
    @endforelse
</div>

@if($portfolios->hasPages())
    <div class="mt-6">
        {{ $portfolios->links() }}
    </div>
@endif
@endsection
