@extends('admin.layouts.master')

@section('title', __('admin.menu_pages'))

@section('page_icon')
    <i class="fa-solid fa-file-lines text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_pages'))
@section('page_subtitle', 'إدارة الصفحات المخصصة وتحديد مكان ظهور الروابط (الهيدر أو الفوتر)')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('pages.create'))
        <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-plus"></i>
            <span>إنشاء صفحة جديدة</span>
        </a>
    @endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-bold">
                <tr>
                    <th class="px-6 py-4 text-start">#</th>
                    <th class="px-6 py-4 text-start">عنوان الصفحة (العربي / الإنجليزي)</th>
                    <th class="px-6 py-4 text-start">المسار (Slug)</th>
                    <th class="px-6 py-4 text-start">مكان الظهور</th>
                    <th class="px-6 py-4 text-start">الحالة</th>
                    <th class="px-6 py-4 text-center">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pages as $index => $page)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $page->title_ar }}</div>
                            <div class="text-xs text-slate-400 font-sans mt-0.5">{{ $page->title_en }}</div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-wood-700">
                            /page/{{ $page->slug }}
                        </td>
                        <td class="px-6 py-4">
                            @if($page->placement === 'navbar')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-800">الهيدر (Navbar)</span>
                            @elseif($page->placement === 'footer')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-100 text-purple-800">الفوتر (Footer)</span>
                            @elseif($page->placement === 'both')
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800">الهيدر والفوتر معاً</span>
                            @else
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">بدون رابط (رابط مباشر فقط)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $page->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $page->is_active ? __('admin.active') : __('admin.inactive') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.pages.edit', $page->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition confirm-delete" title="{{ __('admin.delete') }}">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                            {{ __('admin.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
