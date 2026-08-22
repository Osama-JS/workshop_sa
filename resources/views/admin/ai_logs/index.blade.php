@extends('admin.layouts.master')

@section('title', __('admin.menu_ai_logs'))

@section('content')
<div class="space-y-6">

    <!-- Header & Stats Banner -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="sm:col-span-2 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-wood-600 uppercase tracking-wider mb-1">
                    <i class="fa-solid fa-comments"></i>
                    <span>{{ __('admin.menu_ai_hub') }}</span>
                </div>
                <h1 class="text-2xl font-black text-slate-900">{{ __('admin.ai_logs_list') }}</h1>
                <p class="text-xs text-slate-500 mt-1">{{ __('admin.menu_ai_logs') }} - {{ __('admin.ai_converted_orders') }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-wood-600 to-wood-800 p-6 rounded-3xl text-white shadow-lg shadow-wood-600/20 flex flex-col justify-between">
            <div class="flex items-center justify-between text-xs text-wood-100">
                <span>{{ __('admin.ai_converted_orders') }}</span>
                <i class="fa-solid fa-file-signature text-gold-400"></i>
            </div>
            <div class="my-2">
                <span class="text-3xl font-black">{{ $totalOrdersCreated }}</span>
                <span class="text-xs text-wood-200">{{ __('admin.menu_orders') }}</span>
            </div>
            <div class="text-[11px] text-wood-200">
                {{ __('admin.ai_logs_count') }}: {{ $totalSessions }}
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.ai-logs.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute top-3 right-3 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}" class="w-full pr-8 pl-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-wood-600">
            </div>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                {{ __('admin.search') }}
            </button>
            @if(request()->hasAny(['search', 'with_orders']))
                <a href="{{ route('admin.ai-logs.index') }}" class="text-xs text-rose-500 hover:underline">{{ __('admin.reset') }}</a>
            @endif
        </form>
    </div>

    <!-- Sessions Table -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold">
                    <tr>
                        <th class="p-4">{{ __('admin.name') }}</th>
                        <th class="p-4">{{ __('admin.menu_messages') }}</th>
                        <th class="p-4">{{ __('admin.menu_orders') }}</th>
                        <th class="p-4">{{ __('admin.created_at') }}</th>
                        <th class="p-4 text-center">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="p-4">
                                <div class="font-bold text-slate-900">
                                    {{ $session->user_name ?: 'زائر مجهول' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono flex items-center gap-2 mt-0.5">
                                    @if($session->user_phone)
                                        <span dir="ltr">{{ $session->user_phone }}</span>
                                    @endif
                                    <span>IP: {{ $session->visitor_ip ?: 'غير محدد' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-xl bg-slate-100 text-slate-800 font-bold">
                                    {{ $session->messages_count }} رسالة
                                </span>
                            </td>
                            <td class="p-4">
                                @if($session->order)
                                    <a href="{{ route('admin.orders.show', $session->order_id) }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold hover:bg-emerald-100 transition">
                                        <i class="fa-solid fa-circle-check"></i>
                                        <span>{{ $session->order->order_number }}</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-[11px]">محادثة استفسارية</span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-500">
                                <div>{{ $session->created_at->format('Y-m-d') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $session->created_at->format('H:i:s A') }}</div>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.ai-logs.show', $session->id) }}" class="px-3 py-1.5 rounded-xl bg-wood-50 text-wood-700 hover:bg-wood-100 font-bold transition flex items-center gap-1" title="عرض نص المحادثة">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>عرض الحوار</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai-logs.destroy', $session->id) }}" onsubmit="return confirm('هل تريد حذف سجل هذه الجلسة؟');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="حذف">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                لا توجد سجلات محادثات حتى الآن.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-100">
            {{ $sessions->links() }}
        </div>
    </div>

</div>
@endsection
