@extends('admin.layouts.master')

@section('title', 'تفاصيل الطلب: ' . $order->order_number)

@section('page_icon')
    <i class="fa-solid fa-file-signature text-wood-600"></i>
@endsection

@section('page_title', 'تفاصيل الطلب: ' . $order->order_number)
@section('page_subtitle', 'مراجعة بيانات العميل والمخططات وتحديث حالة التصنيع')

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة لقائمة الطلبات</span>
        </a>

        <div class="flex items-center gap-3">
            @if($order->customer_whatsapp || $order->customer_phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->customer_whatsapp ?: $order->customer_phone) }}?text={{ urlencode('مرحباً ' . $order->customer_name . '، نتواصل معك من ورشة أرتيزان للأعمال الخشبية بخصوص طلبكم رقم ' . $order->order_number) }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>مراسلة العميل واتساب</span>
                </a>
            @endif

            <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold transition confirm-delete flex items-center gap-2">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>حذف الطلب</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main 2-Col Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Left 2 Cols: Details & Attachments -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer & Service Overview Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-wood-600"></i>
                    <span>بيانات العميل والتواصل</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">اسم العميل:</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $order->customer_name }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">رقم الهاتف:</span>
                        <a href="tel:{{ $order->customer_phone }}" class="font-bold font-mono text-wood-600 text-sm" dir="ltr">{{ $order->customer_phone }}</a>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">رقم الواتساب:</span>
                        <span class="font-bold font-mono text-emerald-600 text-sm" dir="ltr">{{ $order->customer_whatsapp ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">البريد الإلكتروني:</span>
                        <span class="font-bold text-slate-700">{{ $order->customer_email ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Specifications Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-ruler-combined text-wood-600"></i>
                    <span>مواصفات وتفاصيل العمل الخشبي المطلوب</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1">نوع الخدمة / التصنيف:</span>
                        <span class="font-bold text-wood-700 text-sm">{{ $order->service?->title_ar ?: 'طلب تفصيل مخصص عام' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">نوع الخشب المفضل:</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $order->wood_type ?: '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-1">الميزانية المتوقعة:</span>
                        <span class="font-bold text-slate-800 text-sm">{{ $order->budget_range ?: '-' }}</span>
                    </div>
                </div>

                @if($order->dimensions)
                    <div class="text-xs">
                        <span class="text-slate-400 block mb-1">المقاسات والأبعاد التقريبية:</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $order->dimensions }}</span>
                    </div>
                @endif

                <div class="pt-2">
                    <span class="text-slate-400 text-xs block mb-2">الوصف والشرح التفصيلي للطلب:</span>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $order->description }}
                    </div>
                </div>
            </div>

            <!-- Attachments Card -->
            @php $attachments = is_array($order->attachments) ? $order->attachments : []; @endphp
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center justify-between">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-paperclip text-wood-600"></i>
                        <span>المرفقات والمخططات الهندسية</span>
                    </span>
                    <span class="text-xs font-mono text-slate-400">{{ count($attachments) }} ملفات</span>
                </h3>

                @if(count($attachments) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($attachments as $att)
                            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-3 truncate">
                                    <div class="w-9 h-9 rounded-lg bg-wood-100 text-wood-700 flex items-center justify-center text-sm font-bold shrink-0">
                                        <i class="fa-solid fa-file"></i>
                                    </div>
                                    <div class="truncate text-xs">
                                        <h5 class="font-bold text-slate-800 truncate">{{ $att['name'] ?? 'مرفق' }}</h5>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $att['size'] ?? '' }} • {{ $att['type'] ?? '' }}</span>
                                    </div>
                                </div>
                                @if(!empty($att['path']))
                                    <a href="{{ storage_asset($att['path']) }}" target="_blank" download class="p-2 rounded-lg bg-wood-600 hover:bg-wood-700 text-white text-xs transition" title="تحميل">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400 text-center py-4">لم يقم العميل بإرفاق أي ملفات مع هذا الطلب.</p>
                @endif
            </div>
        </div>

        <!-- Right Col: Status Updater & Admin Notes -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-wood-600"></i>
                    <span>تحديث حالة الطلب وملاحظات الورشة</span>
                </h3>

                <form method="POST" action="{{ route('admin.orders.status.update', $order->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">حالة الطلب الحالية:</label>
                        <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition font-bold">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ قيد الانتظار (pending)</option>
                            <option value="in_review" {{ $order->status === 'in_review' ? 'selected' : '' }}>🔍 قيد دراسة المخططات والتسعير (in_review)</option>
                            <option value="contacted" {{ $order->status === 'contacted' ? 'selected' : '' }}>📞 تم التواصل واعتماد المواصفات (contacted)</option>
                            <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>🔨 قيد التصنيع في الورشة (in_progress)</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ تم الإنجاز وجاهز للتسليم (completed)</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ ملغي (cancelled)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">ملاحظات الإدارة الداخلية (لا تظهر للعميل):</label>
                        <textarea name="admin_notes" rows="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition" placeholder="اكتب ملاحظات المهندس، تفاصيل التسعيرة المتفق عليها، تاريخ التسليم المتوقع...">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-wood-600 hover:bg-wood-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ التعديلات والحالة</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
