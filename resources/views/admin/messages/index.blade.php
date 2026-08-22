@extends('admin.layouts.master')

@section('title', __('admin.menu_messages'))

@section('page_icon')
    <i class="fa-solid fa-envelope-open-text text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_messages'))
@section('page_subtitle', 'متابعة وإدارة استفسارات الزوار ورسائل التواصل الواردة عبر الموقع')

@section('content')
<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-6">
    <form method="GET" action="{{ route('admin.messages.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
        <div class="sm:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="بحث بالاسم، البريد، الجوال، أو الموضوع..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
        </div>
        <div>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                <option value="">{{ __('admin.all') }} - الرسائل</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>غير مقروءة ({{ $unreadCount }})</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>مقروءة</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-filter ml-1"></i> تصفية
            </button>
            @if(request()->anyFilled(['search', 'status']))
                <a href="{{ route('admin.messages.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="إعادة تعيين">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Messages Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-bold">
                <tr>
                    <th class="px-6 py-4 text-start">المرسل / التواصل</th>
                    <th class="px-6 py-4 text-start">الموضوع والرسالة</th>
                    <th class="px-6 py-4 text-start">الحالة</th>
                    <th class="px-6 py-4 text-start">التاريخ</th>
                    <th class="px-6 py-4 text-center">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($messages as $msg)
                    <tr class="hover:bg-slate-50/80 transition {{ !$msg->is_read ? 'bg-amber-50/30 font-semibold' : '' }}">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $msg->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $msg->email }}</div>
                            @if($msg->phone)
                                <div class="text-[11px] text-slate-400 font-mono" dir="ltr">{{ $msg->phone }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.messages.show', $msg->id) }}" class="font-bold text-wood-700 hover:underline block">
                                {{ $msg->subject ?: 'استفسار عام' }}
                            </a>
                            <div class="text-xs text-slate-500 truncate max-w-sm mt-0.5">{{ $msg->message }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if(!$msg->is_read)
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                    رسالة جديدة
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                    مقروءة
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">
                            {{ $msg->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.messages.show', $msg->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="عرض الرسالة">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                @if($msg->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $msg->phone) }}?text={{ urlencode('مرحباً ' . $msg->name . '، رداً على رسالتكم عبر موقع ورشة أرتيزان للأعمال الخشبية...') }}" target="_blank" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition" title="واتساب">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.messages.toggle-read', $msg->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="{{ $msg->is_read ? 'تحديد كغير مقروء' : 'تحديد كمقروء' }}">
                                        <i class="fa-solid fa-{{ $msg->is_read ? 'envelope' : 'envelope-open' }} text-xs"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" class="inline">
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
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-xs">
                            {{ __('admin.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($messages->hasPages())
    <div class="mt-6">
        {{ $messages->links() }}
    </div>
@endif
@endsection
