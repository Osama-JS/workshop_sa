@extends('admin.layouts.master')

@section('title', __('admin.dashboard'))

@section('page_icon')
    <i class="fa-solid fa-gauge-high text-wood-600"></i>
@endsection

@section('page_title', __('admin.dashboard'))
@section('page_subtitle', __('admin.welcome') . ' - ' . __('admin.login_subtitle'))

@section('content')
<!-- Metric Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    <!-- Card 1: Custom Orders -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">{{ __('admin.menu_orders') }}</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['orders_count'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">الطلبات المعلقة (Pending)</span>
            <span class="font-bold text-amber-600 px-2 py-0.5 bg-amber-50 rounded-full">{{ $stats['pending_orders_count'] }}</span>
        </div>
    </div>

    <!-- Card 2: Contact Messages -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">{{ __('admin.menu_messages') }}</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['messages_count'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">رسائل غير مقروءة (Unread)</span>
            <span class="font-bold text-blue-600 px-2 py-0.5 bg-blue-50 rounded-full">{{ $stats['unread_messages_count'] }}</span>
        </div>
    </div>

    <!-- Card 3: Services & Portfolio -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">{{ __('admin.menu_services') }} & {{ __('admin.menu_portfolio') }}</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['services_count'] }} / {{ $stats['portfolios_count'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-wood-50 text-wood-700 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-couch"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">مشاريع منجزة معروضة</span>
            <span class="font-bold text-wood-700">{{ $stats['portfolios_count'] }} مشروع</span>
        </div>
    </div>

    <!-- Card 4: Visitors Today -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">{{ __('admin.menu_analytics') }}</span>
                <span class="text-2xl font-black text-slate-900 mt-1 block">{{ $stats['visitors_count'] }}</span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">زيارات اليوم (Today)</span>
            <span class="font-bold text-emerald-600">{{ $stats['today_visitors'] }}</span>
        </div>
    </div>
</div>

<!-- Quick Action Shortcuts Banner -->
<div class="bg-gradient-to-r from-wood-800 via-wood-900 to-dark-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
    <div class="absolute -right-10 -bottom-10 opacity-10 text-9xl pointer-events-none">
        <i class="fa-solid fa-tree"></i>
    </div>
    <div class="space-y-2 text-center md:text-start relative z-10">
        <span class="px-3 py-1 bg-gold-500/20 border border-gold-500/40 text-gold-400 text-xs font-semibold rounded-full inline-block">
            ✨ نظام إدارة ورشة الأعمال الخشبية
        </span>
        <h2 class="text-xl sm:text-2xl font-bold">إدارة متكاملة لجميع الأنشطة والطلبات والصلاحيات</h2>
        <p class="text-xs sm:text-sm text-slate-300 max-w-xl">
            يمكنك تخصيص الأدوار، مراجعة طلبات الزوار المخصصة للتفصيل، وتحديث محتوى الخدمات ومعرض الأعمال بكل سهولة.
        </p>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-3 relative z-10">
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('roles.manage'))
            <a href="{{ route('admin.roles.create') }}" class="px-4 py-2.5 bg-white text-wood-900 hover:bg-wood-50 text-xs font-bold rounded-xl shadow transition">
                <i class="fa-solid fa-plus ml-1"></i> {{ __('admin.create_role') }}
            </a>
        @endif
        @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('users.manage'))
            <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 bg-wood-600 hover:bg-wood-500 text-white text-xs font-bold rounded-xl shadow transition">
                <i class="fa-solid fa-user-plus ml-1"></i> {{ __('admin.create_user') }}
            </a>
        @endif
    </div>
</div>

<!-- Grid: Recent Orders & Recent Messages -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Custom Orders -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-file-signature text-wood-600"></i>
                <span>أحدث طلبات التفصيل المخصصة</span>
            </h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-wood-600 hover:text-wood-700 font-semibold">
                {{ __('admin.all') }} &rarr;
            </a>
        </div>
        <div class="divide-y divide-slate-100 overflow-x-auto">
            @forelse($recentOrders as $order)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition">
                    <div class="space-y-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-slate-800 truncate">{{ $order->customer_name }}</span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-md {{ $order->status_badge }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 flex items-center gap-3">
                            <span><i class="fa-solid fa-hashtag text-[10px] text-slate-400"></i> {{ $order->order_number }}</span>
                            <span><i class="fa-solid fa-phone text-[10px] text-slate-400"></i> {{ $order->customer_phone }}</span>
                        </div>
                    </div>
                    <div>
                        @if($order->whatsapp_link)
                            <a href="{{ $order->whatsapp_link }}" target="_blank" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition text-xs font-bold inline-flex items-center gap-1" title="محادثة واتساب مباشرة">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    {{ __('admin.no_records') }}
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Contact Messages -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <i class="fa-solid fa-envelope-open-text text-blue-600"></i>
                <span>أحدث رسائل واستفسارات الزوار</span>
            </h3>
            <a href="{{ route('admin.messages.index') }}" class="text-xs text-wood-600 hover:text-wood-700 font-semibold">
                {{ __('admin.all') }} &rarr;
            </a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentMessages as $msg)
                <div class="p-4 flex items-center justify-between gap-4 hover:bg-slate-50 transition">
                    <div class="space-y-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-slate-800 truncate">{{ $msg->name }}</span>
                            @if(!$msg->is_read)
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-rose-100 text-rose-700 rounded-md">جديد</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 truncate max-w-sm">{{ $msg->message }}</p>
                    </div>
                    <span class="text-[11px] text-slate-400 shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    {{ __('admin.no_records') }}
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
