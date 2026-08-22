@extends('admin.layouts.master')

@section('title', __('admin.menu_orders'))

@section('page_icon')
    <i class="fa-solid fa-file-signature text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_orders'))
@section('page_subtitle', 'إدارة ومتابعة طلبات التفصيل والتسعير المخصصة وتحديث مراحل التصنيع والتنفيذ')

@section('content')
<!-- Status KPI Pills Row -->
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="p-3.5 rounded-2xl border transition {{ !request('status') ? 'bg-wood-600 text-white border-wood-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
        <span class="text-xs font-semibold block">{{ __('admin.all') }}</span>
        <span class="text-xl font-black font-mono">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="p-3.5 rounded-2xl border transition {{ request('status') === 'pending' ? 'bg-amber-500 text-white border-amber-500 shadow-md' : 'bg-white text-amber-600 border-slate-200 hover:bg-slate-50' }}">
        <span class="text-xs font-semibold block">قيد الانتظار (جديدة)</span>
        <span class="text-xl font-black font-mono">{{ $counts['pending'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'in_review']) }}" class="p-3.5 rounded-2xl border transition {{ request('status') === 'in_review' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-blue-600 border-slate-200 hover:bg-slate-50' }}">
        <span class="text-xs font-semibold block">قيد الدراسة والتسعير</span>
        <span class="text-xl font-black font-mono">{{ $counts['in_review'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'in_progress']) }}" class="p-3.5 rounded-2xl border transition {{ request('status') === 'in_progress' ? 'bg-cyan-600 text-white border-cyan-600 shadow-md' : 'bg-white text-cyan-600 border-slate-200 hover:bg-slate-50' }}">
        <span class="text-xs font-semibold block">قيد التصنيع بالورشة</span>
        <span class="text-xl font-black font-mono">{{ $counts['in_progress'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="p-3.5 rounded-2xl border transition {{ request('status') === 'completed' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md' : 'bg-white text-emerald-600 border-slate-200 hover:bg-slate-50' }}">
        <span class="text-xs font-semibold block">تم الإنجاز والتسليم</span>
        <span class="text-xl font-black font-mono">{{ $counts['completed'] }}</span>
    </a>
</div>

<!-- Search & Filter Bar -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-6">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 sm:grid-cols-6 gap-3">
        <div class="sm:col-span-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="رقم الطلب، اسم العميل، الجوال..."
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
        </div>
        <div>
            <select name="status" class="w-full">
                <option value="">{{ __('admin.all') }} - الحالات</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>قيد الانتظار (pending)</option>
                <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>قيد الدراسة (in_review)</option>
                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>تم التواصل (contacted)</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>قيد التصنيع (in_progress)</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>مكتمل ومسلّم (completed)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغي (cancelled)</option>
            </select>
        </div>
        <div>
            <select name="service_id" class="w-full">
                <option value="">{{ __('admin.all') }} - الخدمات</option>
                @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ request('service_id') == $s->id ? 'selected' : '' }}>{{ $s->title_ar }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="من تاريخ..."
                class="w-full datepicker bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800">
        </div>
        <div class="flex items-center gap-2">
            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="إلى تاريخ..."
                class="w-full datepicker bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800">
            <button type="submit" class="py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition flex-shrink-0" title="تصفية">
                <i class="fa-solid fa-filter"></i>
            </button>
            @if(request()->anyFilled(['search', 'status', 'service_id', 'date_from', 'date_to']))
                <a href="{{ route('admin.orders.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition flex-shrink-0" title="إعادة تعيين">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-bold">
                <tr>
                    <th class="px-6 py-4 text-start">رقم الطلب</th>
                    <th class="px-6 py-4 text-start">العميل / التواصل</th>
                    <th class="px-6 py-4 text-start">الخدمة والتفاصيل</th>
                    <th class="px-6 py-4 text-start">المرفقات</th>
                    <th class="px-6 py-4 text-start">الحالة</th>
                    <th class="px-6 py-4 text-start">التاريخ</th>
                    <th class="px-6 py-4 text-center">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-mono font-bold text-wood-700 text-xs">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="hover:underline">
                                {{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $order->customer_name }}</div>
                            <div class="text-xs text-slate-500 font-mono" dir="ltr">{{ $order->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->service)
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-wood-50 text-wood-800">
                                    {{ $order->service->title_ar }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">عام</span>
                            @endif
                            <div class="text-xs text-slate-500 truncate max-w-xs mt-1">{{ $order->description }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php $attCount = is_array($order->attachments) ? count($order->attachments) : 0; @endphp
                            @if($attCount > 0)
                                <span class="px-2 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold flex items-center gap-1 w-fit">
                                    <i class="fa-solid fa-paperclip"></i>
                                    <span>{{ $attCount }} ملفات</span>
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badges = [
                                    'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    'in_review' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'contacted' => 'bg-purple-100 text-purple-800 border-purple-300',
                                    'in_progress' => 'bg-cyan-100 text-cyan-800 border-cyan-300',
                                    'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
                                ];
                                $names = [
                                    'pending' => 'قيد الانتظار',
                                    'in_review' => 'قيد الدراسة',
                                    'contacted' => 'تم التواصل',
                                    'in_progress' => 'قيد التصنيع',
                                    'completed' => 'مكتمل',
                                    'cancelled' => 'ملغي',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold border {{ $badges[$order->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $names[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-400">
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="عرض تفاصيل الطلب">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                @if($order->customer_whatsapp || $order->customer_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_whatsapp ?: $order->customer_phone) }}?text={{ urlencode('مرحباً ' . $order->customer_name . '، بخصوص طلبكم رقم ' . $order->order_number . ' من ورشة أرتيزان للأعمال الخشبية...') }}" target="_blank" class="p-2 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition" title="مراسلة العميل عبر واتساب">
                                        <i class="fa-brands fa-whatsapp text-xs"></i>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" class="inline">
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
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-xs">
                            {{ __('admin.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@endif
@endsection
