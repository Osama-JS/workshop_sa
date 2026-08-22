@extends('admin.layouts.master')

@section('title', __('admin.menu_services'))

@section('page_icon')
    <i class="fa-solid fa-couch text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_services'))
@section('page_subtitle', 'إدارة وتخصيص خدمات وأعمال الورشة الخشبية ثنائية اللغة')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('services.create'))
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة خدمة جديدة</span>
        </a>
    @endif
@endsection

@section('content')
<!-- Filter & Search Bar -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-6">
    <form method="GET" action="{{ route('admin.services.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
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
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.services.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="إعادة تعيين">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Services Grid Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services as $service)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <!-- Top Cover & Icon Header -->
                <div class="h-40 bg-gradient-to-br from-wood-800 to-dark-900 relative flex items-center justify-center overflow-hidden">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="w-full h-full object-cover opacity-60">
                    @else
                        <div class="text-wood-400 opacity-20 text-8xl absolute">
                            <i class="fa-solid fa-couch"></i>
                        </div>
                    @endif

                    <!-- Badges -->
                    <div class="absolute top-3 {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} flex items-center gap-1.5">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $service->is_active ? 'bg-emerald-500/90 text-white backdrop-blur-md' : 'bg-rose-500/90 text-white backdrop-blur-md' }}">
                            {{ $service->is_active ? __('admin.active') : __('admin.inactive') }}
                        </span>
                        @if($service->is_featured)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-gold-500 text-slate-950 backdrop-blur-md">
                                <i class="fa-solid fa-star text-[9px]"></i> مميزة
                            </span>
                        @endif
                    </div>

                    <!-- Icon Floating Button -->
                    <div class="absolute -bottom-5 {{ app()->getLocale() === 'ar' ? 'right-5' : 'left-5' }} w-12 h-12 rounded-2xl bg-wood-600 text-white flex items-center justify-center text-xl shadow-lg ring-4 ring-white">
                        <i class="fa-solid fa-{{ $service->icon ?: 'couch' }}"></i>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5 pt-8 space-y-2">
                    <h3 class="font-bold text-slate-900 text-base leading-snug">
                        {{ $service->title_ar }}
                    </h3>
                    <p class="text-xs text-wood-600 font-medium font-sans">
                        {{ $service->title_en }}
                    </p>
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed pt-1">
                        {{ $service->short_desc_ar ?: 'لا يوجد وصف مختصر.' }}
                    </p>
                </div>
            </div>

            <!-- Footer Meta & Actions -->
            <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500 font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-images text-wood-600 text-[11px]"></i>
                    <span>{{ $service->portfolios()->count() }} مشروع مرتبط</span>
                </span>

                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.services.edit', $service->id) }}" class="p-2 rounded-lg bg-white border border-slate-200 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.services.destroy', $service->id) }}" class="inline">
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

<!-- Pagination -->
@if($services->hasPages())
    <div class="mt-6">
        {{ $services->links() }}
    </div>
@endif
@endsection
