@extends('frontend.layouts.app')

@section('title', (app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us') . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <span class="text-xs font-bold uppercase tracking-widest text-gold-500 block">
            {{ app()->getLocale() === 'ar' ? 'نحن هنا لخدمتكم' : 'Get in Touch' }}
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ app()->getLocale() === 'ar' ? 'تواصل مع ورشة أرتيزان' : 'Contact Our Workshop' }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-400 max-w-xl mx-auto">
            {{ app()->getLocale() === 'ar' ? 'يسعدنا استقبال استفساراتكم وزيارتكم في مقر ورشتنا، أو التواصل المباشر عبر الهاتف والواتساب.' : 'We are delighted to receive your inquiries and welcome you at our workshop.' }}
        </p>
    </div>
</div>

<div class="py-20 bg-dark-900 space-y-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Contact Cards Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Phone -->
            <div class="glass-card p-6 rounded-3xl text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-gold-gradient text-slate-950 flex items-center justify-center text-xl mx-auto shadow-lg">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h4 class="font-bold text-white text-sm">{{ app()->getLocale() === 'ar' ? 'الهاتف المباشر' : 'Phone' }}</h4>
                <p class="text-xs text-slate-400" dir="ltr">{{ \App\Models\Setting::get('phone', '+966 50 000 0000') }}</p>
            </div>

            <!-- WhatsApp -->
            <div class="glass-card p-6 rounded-3xl text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl mx-auto shadow-lg">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h4 class="font-bold text-white text-sm">{{ app()->getLocale() === 'ar' ? 'واتساب مباشر' : 'WhatsApp' }}</h4>
                <p class="text-xs text-slate-400" dir="ltr">{{ \App\Models\Setting::get('whatsapp', '+966 50 000 0000') }}</p>
            </div>

            <!-- Email -->
            <div class="glass-card p-6 rounded-3xl text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center text-xl mx-auto shadow-lg">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h4 class="font-bold text-white text-sm">{{ app()->getLocale() === 'ar' ? 'البريد الرسمي' : 'Email' }}</h4>
                <p class="text-xs text-slate-400 truncate">{{ \App\Models\Setting::get('email', 'info@artisanwood.sa') }}</p>
            </div>

            <!-- Working Hours -->
            <div class="glass-card p-6 rounded-3xl text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-wood-600 text-white flex items-center justify-center text-xl mx-auto shadow-lg">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h4 class="font-bold text-white text-sm">{{ app()->getLocale() === 'ar' ? 'ساعات العمل' : 'Hours' }}</h4>
                <p class="text-xs text-slate-400">{{ \App\Models\Setting::get('working_hours_' . app()->getLocale(), 'السبت - الخميس: 8 صباحاً - 8 مساءً') }}</p>
            </div>
        </div>

        <!-- Form & Map Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <!-- Left: Contact Form -->
            <div class="glass-card rounded-3xl p-6 sm:p-10 space-y-6">
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-white">
                        {{ app()->getLocale() === 'ar' ? 'أرسل لنا رسالة أو استفسار' : 'Send Us a Message' }}
                    </h3>
                    <p class="text-xs text-slate-400">
                        {{ app()->getLocale() === 'ar' ? 'املأ النموذج أدناه وسيتواصل معكم ممثل خدمة العملاء في أقرب وقت.' : 'Fill the form and our customer service team will reach out.' }}
                    </p>
                </div>

                @if(session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'الاسم الكريم' : 'Your Name' }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full bg-dark-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 transition"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'الاسم' : 'Name' }}">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full bg-dark-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 transition"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" dir="ltr"
                                class="w-full bg-dark-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 transition text-right"
                                placeholder="05XXXXXXXX">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'موضوع الرسالة' : 'Subject' }}
                            </label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="w-full bg-dark-950 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-gold-500 transition"
                                placeholder="{{ app()->getLocale() === 'ar' ? 'استفسار عن بوثات معارض' : 'Inquiry regarding booths' }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'نص الرسالة' : 'Message' }} <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="message" rows="4" required
                            class="w-full bg-dark-950 border border-white/10 rounded-xl p-4 text-sm text-white focus:outline-none focus:border-gold-500 transition"
                            placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب رسالتك أو استفسارك هنا...' : 'Type your inquiry here...' }}">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-xl bg-gold-gradient text-slate-950 font-bold text-xs shadow-lg hover:brightness-110 transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>{{ app()->getLocale() === 'ar' ? 'إرسال الرسالة الآن' : 'Send Message' }}</span>
                    </button>
                </form>
            </div>

            <!-- Right: Location & Google Map -->
            <div class="space-y-6">
                <div class="glass-card rounded-3xl p-6 sm:p-8 space-y-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-gold-500"></i>
                        <span>{{ app()->getLocale() === 'ar' ? 'مقر الورشة والمعرض' : 'Workshop Location' }}</span>
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('address_' . app()->getLocale(), 'المملكة العربية السعودية - الرياض - المنطقة الصناعية') }}
                    </p>
                </div>

                <!-- Google Maps Embed Card -->
                <div class="rounded-3xl overflow-hidden border border-white/10 shadow-2xl h-96 bg-dark-950">
                    @if($map = \App\Models\Setting::get('google_maps_code'))
                        {!! $map !!}
                    @else
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d231940.67204481024!2d46.73858085!3d24.7253981!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f03890d48939b%3A0x7ff9f410381bc7b!2sRiyadh%20Saudi%20Arabia!5e0!3m2!1sen!2ssa!4v1690000000000!5m2!1sen!2ssa" class="w-full h-full border-0" allowfullscreen="" loading="lazy"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
