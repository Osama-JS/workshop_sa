@extends('admin.layouts.master')

@section('title', __('admin.dashboard'))

@section('page_icon')
    <i class="fa-solid fa-chart-pie text-wood-600"></i>
@endsection

@section('page_title', __('admin.dashboard'))
@section('page_subtitle', 'نظرة عامة على نشاط الورشة، حركة الزيارات، وإحصائيات طلبات التفصيل المخصصة')

@section('content')
<div class="space-y-8">
    <!-- Top 4 Primary KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- 1. Total Custom Orders -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-wood-500/40 transition">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">إجمالي طلبات التفصيل</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ number_format($totalOrders) }}</span>
                <span class="text-[11px] text-amber-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-clock"></i>
                    <span>{{ $pendingOrders }} بانتظار المراجعة</span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-wood-50 text-wood-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </div>

        <!-- 2. Completed Projects & Orders -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-emerald-500/40 transition">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">المشاريع المنجزة والمسلّمة</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ number_format($completedOrders + $totalPortfolios) }}</span>
                <span class="text-[11px] text-emerald-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ $totalPortfolios }} في معرض الأعمال</span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-images"></i>
            </div>
        </div>

        <!-- 3. Total Visitors & Traffic -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-blue-500/40 transition">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">إجمالي زوار الموقع</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ number_format($totalVisitors) }}</span>
                <span class="text-[11px] text-blue-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-eye"></i>
                    <span>{{ number_format($totalPageViews) }} مشاهدة صفحة</span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- 4. Unread Messages -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-rose-500/40 transition">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 block">رسائل التواصل</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ number_format($unreadMessages) }}</span>
                <span class="text-[11px] text-rose-600 font-bold flex items-center gap-1">
                    <i class="fa-solid fa-envelope"></i>
                    <span>رسائل غير مقروءة</span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
        </div>
    </div>

    <!-- Secondary Quick Stats Bar -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-400 block">زوار اليوم</span>
                <span class="text-xl font-bold font-mono text-gold-400">{{ $visitorsToday }}</span>
            </div>
            <i class="fa-solid fa-chart-line text-slate-600 text-lg"></i>
        </div>

        <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-400 block">زوار هذا الأسبوع</span>
                <span class="text-xl font-bold font-mono text-gold-400">{{ $visitorsWeek }}</span>
            </div>
            <i class="fa-solid fa-calendar-week text-slate-600 text-lg"></i>
        </div>

        <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-400 block">طلبات قيد التصنيع</span>
                <span class="text-xl font-bold font-mono text-cyan-400">{{ $inProgressOrders }}</span>
            </div>
            <i class="fa-solid fa-hammer text-slate-600 text-lg"></i>
        </div>

        <div class="bg-slate-900 text-white rounded-2xl p-4 flex items-center justify-between">
            <div>
                <span class="text-[11px] text-slate-400 block">خدمات معتمدة</span>
                <span class="text-xl font-bold font-mono text-wood-300">{{ $totalServices }}</span>
            </div>
            <i class="fa-solid fa-couch text-slate-600 text-lg"></i>
        </div>
    </div>

    <!-- Interactive Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 1. Traffic Trends Chart (Line / Area Chart - 2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-area text-wood-600"></i>
                    <span>حركة الزيارات والمشاهدات اليومية (آخر 14 يوماً)</span>
                </h3>
                <span class="text-xs font-semibold text-slate-400">تحديث لحظي</span>
            </div>
            <div class="h-72 w-full">
                <canvas id="trafficTrendsChart"></canvas>
            </div>
        </div>

        <!-- 2. Orders Status Distribution (Doughnut Chart - 1 Col) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-wood-600"></i>
                    <span>توزيع حالات طلبات التفصيل</span>
                </h3>
            </div>
            <div class="h-72 w-full flex items-center justify-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Secondary Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 3. Top Requested Woodwork Categories (Bar Chart) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-wood-600"></i>
                    <span>أكثر الخدمات الخشبية طلباً من العملاء</span>
                </h3>
            </div>
            <div class="h-64 w-full">
                <canvas id="topServicesChart"></canvas>
            </div>
        </div>

        <!-- 4. Device Breakdown (Doughnut) -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-mobile-screen-button text-wood-600"></i>
                    <span>توزيع أجهزة الزوار (Desktop / Mobile / Tablet)</span>
                </h3>
            </div>
            <div class="h-64 w-full flex items-center justify-center">
                <canvas id="deviceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Feeds Table Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Custom Orders -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-file-signature text-wood-600"></i>
                    <span>أحدث طلبات التفصيل المخصصة</span>
                </h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-wood-600 hover:text-wood-700">
                    عرض الكل &larr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($latestOrders as $order)
                    <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between gap-3">
                        <div class="space-y-1">
                            <div class="font-bold text-slate-800 flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-mono text-wood-700 hover:underline">
                                    {{ $order->order_number }}
                                </a>
                                <span>- {{ $order->customer_name }}</span>
                            </div>
                            <div class="text-slate-400 text-[11px]">
                                {{ $order->service?->title_ar ?: 'طلب مخصص' }} • {{ $order->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @php
                                $statusBadge = match($order->status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'in_review' => 'bg-blue-100 text-blue-800',
                                    'in_progress' => 'bg-cyan-100 text-cyan-800',
                                    'completed' => 'bg-emerald-100 text-emerald-800',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $statusBadge }}">
                                {{ $order->status }}
                            </span>
                            @if($order->customer_phone)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_whatsapp ?: $order->customer_phone) }}" target="_blank" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition" title="واتساب">
                                    <i class="fa-brands fa-whatsapp text-xs"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">لا توجد طلبات حديثة</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Contact Messages -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-4 bg-slate-50/80 border-b border-slate-200/80 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-envelope-open-text text-wood-600"></i>
                    <span>أحدث رسائل واستفسارات التواصل</span>
                </h3>
                <a href="{{ route('admin.messages.index') }}" class="text-xs font-bold text-wood-600 hover:text-wood-700">
                    عرض الكل &larr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($latestMessages as $msg)
                    <div class="p-4 hover:bg-slate-50/80 transition flex items-center justify-between gap-3 {{ !$msg->is_read ? 'bg-amber-50/40' : '' }}">
                        <div class="space-y-1 truncate">
                            <div class="font-bold text-slate-800 flex items-center gap-2">
                                <span>{{ $msg->name }}</span>
                                @if(!$msg->is_read)
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                @endif
                            </div>
                            <div class="text-slate-500 text-[11px] truncate">
                                {{ $msg->subject ?: $msg->message }}
                            </div>
                        </div>

                        <div class="text-end shrink-0">
                            <span class="text-[10px] text-slate-400 block font-mono">{{ $msg->created_at->diffForHumans() }}</span>
                            <a href="{{ route('admin.messages.show', $msg->id) }}" class="text-[11px] font-bold text-wood-600 hover:underline">
                                قراءة &larr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">لا توجد رسائل تواصل جديدة</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Local Chart.js -->
<script src="{{ asset('vendor/chartjs/chart.umd.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;

        // Common Chart Defaults
        Chart.defaults.font.family = "'Cairo', 'Outfit', sans-serif";
        Chart.defaults.color = '#64748b';

        // 1. Traffic Trends (Area / Line Chart)
        const trafficCtx = document.getElementById('trafficTrendsChart');
        if (trafficCtx) {
            new Chart(trafficCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trafficLabels) !!},
                    datasets: [
                        {
                            label: 'مشاهدات الصفحات (Page Views)',
                            data: {!! json_encode($trafficPageViews) !!},
                            borderColor: '#b88b64',
                            backgroundColor: 'rgba(184, 139, 100, 0.15)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointBackgroundColor: '#b88b64',
                        },
                        {
                            label: 'الزوار الفريدون (Unique Visitors)',
                            data: {!! json_encode($trafficUniqueVisitors) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointBackgroundColor: '#3b82f6',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        // 2. Orders Status Breakdown (Doughnut)
        const orderStatusCtx = document.getElementById('orderStatusChart');
        if (orderStatusCtx) {
            new Chart(orderStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['قيد الانتظار', 'قيد الدراسة', 'تم التواصل', 'قيد التصنيع', 'مكتمل', 'ملغي'],
                    datasets: [{
                        data: [
                            {{ $orderStatuses['pending'] ?? 0 }},
                            {{ $orderStatuses['in_review'] ?? 0 }},
                            {{ $orderStatuses['contacted'] ?? 0 }},
                            {{ $orderStatuses['in_progress'] ?? 0 }},
                            {{ $orderStatuses['completed'] ?? 0 }},
                            {{ $orderStatuses['cancelled'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#f59e0b', // pending
                            '#3b82f6', // in_review
                            '#a855f7', // contacted
                            '#06b6d4', // in_progress
                            '#10b981', // completed
                            '#f43f5e'  // cancelled
                        ],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '70%'
                }
            });
        }

        // 3. Top Requested Services (Bar Chart)
        const topServicesCtx = document.getElementById('topServicesChart');
        if (topServicesCtx) {
            new Chart(topServicesCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($topServiceLabels) !!},
                    datasets: [{
                        label: 'عدد الطلبات',
                        data: {!! json_encode($topServiceCounts) !!},
                        backgroundColor: '#8e5b32',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0 }
                        }
                    }
                }
            });
        }

        // 4. Device Distribution (Doughnut)
        const deviceCtx = document.getElementById('deviceChart');
        if (deviceCtx) {
            new Chart(deviceCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($deviceLabels) !!},
                    datasets: [{
                        data: {!! json_encode($deviceData) !!},
                        backgroundColor: ['#b88b64', '#3b82f6', '#10b981'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '65%'
                }
            });
        }
    });
</script>
@endpush
