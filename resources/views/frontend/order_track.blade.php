@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'تتبع حالة طلبك' : 'Track Your Order') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'نظام التتبع اللحظي' : 'Live Order Tracking' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'تتبع حالة طلب التفصيل والمشروع' : 'Track Your Custom Order Status' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto">
            {{ app()->getLocale() === 'ar' ? 'أدخل رقم الطلب المرجعي (مثل: ORD-2026-XXXX) لمتابعة مراحل تصنيع وتنفيذ مشروعك.' : 'Enter your order tracking reference code to check production status.' }}
        </p>
    </div>
</div>

<!-- Tracking Search & Results -->
<div class="py-20 bg-dark-900 min-h-[50vh]">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-12">
        <!-- Search Input Card -->
        <form method="GET" action="{{ route('order.track') }}" class="glass-card rounded-2xl p-4 sm:p-6 border-gold-500/20">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none text-gold-500">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <input type="text" name="code" value="{{ $code }}" required
                        class="w-full bg-dark-950 border border-white/10 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-11 pl-4' : 'pl-11 pr-4' }} py-3.5 text-sm text-white font-mono uppercase focus:outline-none focus:border-gold-500 transition"
                        placeholder="ORD-2026-XXXX">
                </div>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-xl bg-gold-gradient text-slate-950 font-bold text-xs shadow-lg hover:brightness-110 transition flex items-center justify-center gap-2 whitespace-nowrap">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'استعلام عن الطلب' : 'Track Order' }}</span>
                </button>
            </div>
        </form>

        <!-- Order Result Card -->
        @if($order)
            <div class="glass-card rounded-3xl p-6 sm:p-10 space-y-8 border-gold-500/30">
                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
                    <div>
                        <span class="text-xs font-bold text-slate-400 block">{{ app()->getLocale() === 'ar' ? 'رقم الطلب' : 'Order Reference' }}</span>
                        <h2 class="text-2xl font-black text-gold-gradient font-mono">{{ $order->order_number }}</h2>
                        <p class="text-xs text-slate-400 mt-1">{{ app()->getLocale() === 'ar' ? 'تاريخ التقديم:' : 'Submitted on:' }} {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
                                'in_review' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
                                'contacted' => 'bg-purple-500/10 text-purple-400 border-purple-500/30',
                                'in_progress' => 'bg-cyan-500/10 text-cyan-400 border-cyan-500/30',
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
                                'cancelled' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
                            ];
                            $statusLabels = [
                                'pending' => app()->getLocale() === 'ar' ? 'قيد الانتظار والمراجعة' : 'Pending Review',
                                'in_review' => app()->getLocale() === 'ar' ? 'قيد دراسة المخططات والتسعير' : 'In Technical Review',
                                'contacted' => app()->getLocale() === 'ar' ? 'تم التواصل وتحديد المواصفات' : 'Contacted / Quoted',
                                'in_progress' => app()->getLocale() === 'ar' ? 'قيد التصنيع في الورشة' : 'In Production',
                                'completed' => app()->getLocale() === 'ar' ? 'تم الإنجاز والتسليم' : 'Completed',
                                'cancelled' => app()->getLocale() === 'ar' ? 'ملغي' : 'Cancelled',
                            ];
                        @endphp
                        <span class="inline-block px-4 py-2 rounded-xl text-xs font-bold border {{ $statusClasses[$order->status] ?? 'bg-slate-500/10 text-slate-300' }}">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Visual Progress Steps -->
                @php
                    $steps = [
                        'pending' => 1,
                        'in_review' => 2,
                        'contacted' => 3,
                        'in_progress' => 4,
                        'completed' => 5,
                    ];
                    $currentStep = $steps[$order->status] ?? 1;
                @endphp

                <div class="py-4">
                    <div class="relative flex items-center justify-between">
                        <div class="absolute inset-y-1/2 left-0 right-0 h-1 bg-white/10 -z-0"></div>
                        <div class="absolute inset-y-1/2 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} h-1 bg-gold-500 -z-0 transition-all duration-500" style="width: {{ (($currentStep - 1) / 4) * 100 }}%;"></div>

                        <!-- Step 1 -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mx-auto {{ $currentStep >= 1 ? 'bg-gold-500 text-slate-950 ring-4 ring-gold-500/20' : 'bg-dark-950 text-slate-500 border border-white/20' }}">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 mt-2 block">{{ app()->getLocale() === 'ar' ? 'الاستلام' : 'Received' }}</span>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mx-auto {{ $currentStep >= 2 ? 'bg-gold-500 text-slate-950 ring-4 ring-gold-500/20' : 'bg-dark-950 text-slate-500 border border-white/20' }}">
                                <i class="fa-solid fa-calculator"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 mt-2 block">{{ app()->getLocale() === 'ar' ? 'الدراسة والتسعير' : 'Quoting' }}</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mx-auto {{ $currentStep >= 3 ? 'bg-gold-500 text-slate-950 ring-4 ring-gold-500/20' : 'bg-dark-950 text-slate-500 border border-white/20' }}">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 mt-2 block">{{ app()->getLocale() === 'ar' ? 'الاعتماد' : 'Approved' }}</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mx-auto {{ $currentStep >= 4 ? 'bg-gold-500 text-slate-950 ring-4 ring-gold-500/20' : 'bg-dark-950 text-slate-500 border border-white/20' }}">
                                <i class="fa-solid fa-hammer"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 mt-2 block">{{ app()->getLocale() === 'ar' ? 'التصنيع' : 'Production' }}</span>
                        </div>

                        <!-- Step 5 -->
                        <div class="relative z-10 text-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold mx-auto {{ $currentStep >= 5 ? 'bg-emerald-500 text-white ring-4 ring-emerald-500/20' : 'bg-dark-950 text-slate-500 border border-white/20' }}">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <span class="text-[11px] font-bold text-slate-300 mt-2 block">{{ app()->getLocale() === 'ar' ? 'التسليم' : 'Delivered' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Details Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-white/[0.02] p-5 rounded-2xl border border-white/5 text-xs">
                    <div>
                        <span class="text-slate-500 block">{{ app()->getLocale() === 'ar' ? 'صاحب الطلب:' : 'Customer Name:' }}</span>
                        <span class="font-bold text-white text-sm">{{ $order->customer_name }}</span>
                    </div>
                    @if($order->service)
                        <div>
                            <span class="text-slate-500 block">{{ app()->getLocale() === 'ar' ? 'الخدمة / التصنيف:' : 'Category:' }}</span>
                            <span class="font-bold text-gold-400 text-sm">{{ $order->service->title }}</span>
                        </div>
                    @endif
                    @if($order->wood_type)
                        <div>
                            <span class="text-slate-500 block">{{ app()->getLocale() === 'ar' ? 'نوع الخشب:' : 'Wood Type:' }}</span>
                            <span class="font-bold text-slate-200">{{ $order->wood_type }}</span>
                        </div>
                    @endif
                    @if($order->dimensions)
                        <div>
                            <span class="text-slate-500 block">{{ app()->getLocale() === 'ar' ? 'المقاسات والأبعاد:' : 'Dimensions:' }}</span>
                            <span class="font-bold text-slate-200">{{ $order->dimensions }}</span>
                        </div>
                    @endif
                </div>

                <!-- WhatsApp Assistance -->
                @if($wa = \App\Models\Setting::get('whatsapp'))
                    <div class="text-center pt-2">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ urlencode(app()->getLocale() === 'ar' ? 'مرحباً، أستفسر عن حالة طلبي رقم: ' . $order->order_number : 'Hello, inquiring about my order status: ' . $order->order_number) }}" target="_blank"
                            class="inline-flex items-center gap-2 text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'هل لديك استفسار حول هذا الطلب؟ راسل مهندس الورشة عبر واتساب' : 'Have a question? Chat with our engineer on WhatsApp' }}</span>
                        </a>
                    </div>
                @endif
            </div>
        @elseif($code)
            <div class="glass-card rounded-3xl p-12 text-center space-y-4 border-rose-500/30">
                <div class="w-16 h-16 rounded-full bg-rose-500/10 text-rose-500 flex items-center justify-center text-2xl mx-auto">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-lg font-bold text-white">
                    {{ app()->getLocale() === 'ar' ? 'لم يتم العثور على طلب بهذا الرقم' : 'Order Not Found' }}
                </h3>
                <p class="text-xs text-slate-400 max-w-md mx-auto">
                    {{ app()->getLocale() === 'ar' ? 'يرجى التأكد من كتابة رمز الطلب بشكل صحيح (مثل: ORD-2026-XXXX).' : 'Please verify the order reference code and try again.' }}
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
