@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'طلب تفصيل مخصص' : 'Custom Woodwork Quote') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'صناعة يدوية حسب الطلب' : 'Bespoke Craftsmanship' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'طلب تفصيل وتنفيذ أعمال خشبية' : 'Request Custom Woodwork Quote' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto leading-relaxed">
            {{ app()->getLocale() === 'ar' ? 'أرسل تفاصيل مشروعك أو مخططاتك الهندسية، وسيقوم مهندسونا بدارسة الطلب وتقديم تسعيرة دقيقة في أسرع وقت.' : 'Submit your project details or blueprints, and our team will provide a tailored quote.' }}
        </p>

        <!-- Quick Track Link -->
        <div class="pt-2">
            <a href="{{ route('order.track') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl glass-card text-gold-400 hover:text-white text-xs font-bold transition border border-gold-500/20">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'هل لديك طلب سابق؟ تتبع حالة طلبك هنا' : 'Already have an order? Track here' }}</span>
            </a>
        </div>
    </div>
</div>

<!-- Form Content -->
<div class="py-20 bg-dark-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('order.store') }}" enctype="multipart/form-data" class="glass-card rounded-3xl p-6 sm:p-10 space-y-8 border-gold-500/30">
            @csrf

            <!-- Section 1: Customer Contact Info -->
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? '1. بيانات العميل والتواصل' : '1. Customer Information' }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                            class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: عبدالله الراجحي' : 'e.g. Abdullah Al-Rajhi' }}">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'رقم الهاتف / الجوال' : 'Phone Number' }} <span class="text-rose-500">*</span>
                        </label>
                        <input type="tel" name="customer_phone" value="{{ old('customer_phone') }}" required dir="ltr"
                            class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition text-right"
                            placeholder="05XXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'رقم الواتساب (لإرسال المخططات والتسعيرة)' : 'WhatsApp Number' }}
                        </label>
                        <input type="tel" name="customer_whatsapp" value="{{ old('customer_whatsapp') }}" dir="ltr"
                            class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition text-right"
                            placeholder="05XXXXXXXX">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                        </label>
                        <input type="email" name="customer_email" value="{{ old('customer_email') }}"
                            class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="name@example.com">
                    </div>
                </div>
            </div>

            <!-- Section 2: Woodwork Specifications -->
            <div class="space-y-6 pt-4 border-t border-white/10">
                <h3 class="text-lg font-bold text-white border-b border-white/10 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-ruler-combined text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? '2. تفاصيل ومواصفات العمل الخشبي' : '2. Project Specifications' }}</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'نوع الخدمة / العمل' : 'Service Category' }}
                        </label>
                        <select name="service_id" class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-gold-500 transition">
                            <option value="">{{ app()->getLocale() === 'ar' ? 'اختر تصنيف العمل...' : 'Select Category...' }}</option>
                            @foreach($services as $s)
                                <option value="{{ $s->id }}" {{ old('service_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'نوع الخشب المفضل' : 'Preferred Wood Type' }}
                        </label>
                        <select name="wood_type" class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-gold-500 transition">
                            <option value="خشب بلوط طبيعي (Oak Wood)">خشب بلوط طبيعي (Oak Wood)</option>
                            <option value="خشب جوز أمريكي (Walnut)">خشب جوز أمريكي (Walnut)</option>
                            <option value="خشب زان ألماني (Beech)">خشب زان ألماني (Beech)</option>
                            <option value="خشب تيك فاخر (Teak Wood)">خشب تيك فاخر (Teak Wood)</option>
                            <option value="خشب سويدي معالج (Swedish Pine)">خشب سويدي معالج (Swedish Pine)</option>
                            <option value="خشب MDF مقسى مكسو قشرة طبيعية">MDF مقسى بقشرة خشب طبيعي</option>
                            <option value="اقتراح الأنسب من قِبل الورشة">اقتراح الأنسب من قِبل مهندسي الورشة</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'الميزانية التقريبية المتوقعة' : 'Budget Range' }}
                        </label>
                        <select name="budget_range" class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-xs text-white focus:outline-none focus:border-gold-500 transition">
                            <option value="أقل من 10,000 ريال">أقل من 10,000 ريال سعودي</option>
                            <option value="10,000 - 25,000 ريال">10,000 - 25,000 ريال سعودي</option>
                            <option value="25,000 - 50,000 ريال">25,000 - 50,000 ريال سعودي</option>
                            <option value="50,000 - 100,000 ريال">50,000 - 100,000 ريال سعودي</option>
                            <option value="أكثر من 100,000 ريال (مشاريع قصور ومعارض)">أكثر من 100,000 ريال (قصور ومعارض)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'المقاسات والأبعاد التقريبية (الطول × العرض × الارتفاع)' : 'Dimensions (L x W x H)' }}
                        </label>
                        <input type="text" name="dimensions" value="{{ old('dimensions') }}"
                            class="w-full bg-dark-950/80 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 transition"
                            placeholder="مثال: 4 متر × 3 متر - ارتفاع 2.80 متر">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'إرفاق مخططات هندسية أو صور تصاميم (PDF / صور / CAD)' : 'Attach Blueprints / Sketches' }}
                        </label>
                        <input type="file" name="attachments[]" multiple class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-gold-500 file:text-slate-950 hover:file:bg-gold-400 cursor-pointer">
                        <p class="text-[11px] text-slate-500 mt-1">يمكنك اختيار عدة ملفات معاً (بحد أقصى 10 ميجابايت لكل ملف)</p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'شرح ووصف تفاصيل الطلب والملاحظات الخاصة' : 'Project Description & Detailed Notes' }} <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="description" rows="4" required
                        class="w-full bg-dark-950/80 border border-white/10 rounded-xl p-4 text-sm text-white focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"
                        placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب تفاصيل طلبك، نوع الدهان أو التشطيب المطلوب، نوع المقابض أو الإضاءات المدمجة، وأي متطلبات إضافية...' : 'Provide details regarding finish, handles, integrated LED lighting, and requirements...' }}">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-slate-400 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-gold-500"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'بياناتكم ومخططاتكم محمية وتخضع لسرية تامة.' : 'Your data and designs are strictly confidential.' }}</span>
                </p>

                <button type="submit" class="w-full sm:w-auto px-10 py-4 rounded-xl bg-gold-gradient text-slate-950 font-bold text-sm shadow-xl shadow-gold-500/20 hover:brightness-110 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'إرسال طلب التفصيل واستخراج رقم التتبع' : 'Submit Custom Order' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
