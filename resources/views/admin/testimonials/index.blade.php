@extends('admin.layouts.master')

@section('title', __('admin.menu_testimonials'))

@section('page_icon')
    <i class="fa-solid fa-comment-dots text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_testimonials'))
@section('page_subtitle', 'إدارة آراء وتوصيات وتقييمات العملاء ثنائية اللغة')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('testimonials.manage'))
        <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة رأي عميل</span>
        </a>
    @endif
@endsection

@section('content')
<!-- Grid Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($testimonials as $item)
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition">
            <div class="space-y-4">
                <!-- Header: Avatar & Stars -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $item->avatar_url }}" alt="{{ $item->client_name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-wood-200">
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $item->client_name_ar }}</h4>
                            <p class="text-xs text-slate-400 font-sans">{{ $item->client_name_en }}</p>
                            @if($item->position_ar || $item->company_ar)
                                <p class="text-[11px] text-wood-600 font-medium mt-0.5">
                                    {{ $item->position_ar }} {{ $item->company_ar ? ' - ' . $item->company_ar : '' }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $item->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        {{ $item->is_active ? __('admin.active') : __('admin.inactive') }}
                    </span>
                </div>

                <!-- Rating Stars -->
                <div class="flex items-center gap-1 text-gold-500 text-xs">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-{{ $i <= $item->rating ? 'solid' : 'regular' }} fa-star"></i>
                    @endfor
                </div>

                <!-- Comment Content -->
                <div class="space-y-1 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                    <p class="text-xs text-slate-700 leading-relaxed font-medium">"{{ $item->comment_ar }}"</p>
                    <p class="text-[11px] text-slate-400 font-sans leading-relaxed">"{{ $item->comment_en }}"</p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <a href="{{ route('admin.testimonials.edit', $item->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                </a>
                <form method="POST" action="{{ route('admin.testimonials.destroy', $item->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition confirm-delete" title="{{ __('admin.delete') }}">
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

@if($testimonials->hasPages())
    <div class="mt-6">
        {{ $testimonials->links() }}
    </div>
@endif
@endsection
