@php
    $wa = \App\Models\Setting::get('contact_whatsapp') 
        ?? \App\Models\Setting::get('whatsapp') 
        ?? \App\Models\Setting::get('contact_phone') 
        ?? '+966501234567';
    $cleanWa = preg_replace('/[^0-9]/', '', $wa);
    $defaultMsg = app()->getLocale() === 'ar' 
        ? 'مرحباً ورشة أرتيزان، أود الاستفسار عن تفصيل أعمال خشبية مخصصة' 
        : 'Hello Artisan Workshop, I would like to inquire about custom woodwork joinery';
    $waUrl = "https://wa.me/{$cleanWa}?text=" . urlencode($defaultMsg);
@endphp

<!-- =========================================================================
     LUXURY DIRECT WHATSAPP FLOATING WIDGET
     ========================================================================= -->
<div id="artisanWhatsappWidget" class="fixed z-40 {{ app()->getLocale() === 'ar' ? 'bottom-6 right-6' : 'bottom-6 left-6' }} font-sans" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="relative group">
        
        <!-- Floating Tooltip Bubble -->
        <div id="waFloatingBubble" class="absolute bottom-16 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} w-64 p-3.5 rounded-2xl bg-dark-900 border border-emerald-500/40 shadow-2xl text-xs space-y-1 hidden sm:block transition-all duration-300 opacity-95 group-hover:opacity-100">
            <div class="flex items-center justify-between">
                <span class="font-bold text-emerald-400 flex items-center gap-1.5 text-xs">
                    <i class="fa-brands fa-whatsapp text-base"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'محادثة واتساب مباشرة' : 'WhatsApp Support' }}</span>
                </span>
                <button type="button" onclick="dismissWaBubble(event); document.getElementById('waFloatingBubble')?.remove();" class="text-slate-400 hover:text-rose-400 text-xs p-1 cursor-pointer" title="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="text-[11px] text-slate-300 leading-relaxed">
                {{ app()->getLocale() === 'ar' ? 'تواصل معنا مباشرة عبر واتساب للحصول على استشارة وتسعيرة فورية!' : 'Chat directly with our team for instant custom quotes!' }}
            </p>
            <div class="pt-1 flex items-center gap-1.5 text-[10px] text-emerald-400 font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-ping"></span>
                <span>{{ app()->getLocale() === 'ar' ? 'فريق المبيعات متاح الآن' : 'Support team is online' }}</span>
            </div>
        </div>

        <!-- Pulsing Radar Glow Ring -->
        <div class="absolute inset-0 rounded-full bg-emerald-500 opacity-40 animate-ping pointer-events-none"></div>

        <!-- Floating Button -->
        <a href="{{ $waUrl }}" target="_blank" id="waFloatBtn" 
           class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-tr from-emerald-600 via-emerald-500 to-green-400 text-white shadow-2xl shadow-emerald-500/50 hover:shadow-emerald-500/80 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center text-3xl sm:text-4xl relative border-2 border-white/40 group z-10" 
           title="WhatsApp Direct">
            <i class="fa-brands fa-whatsapp group-hover:rotate-12 transition-transform duration-300"></i>
            <!-- Online Dot -->
            <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-emerald-400 border-2 border-dark-950 rounded-full"></span>
        </a>
    </div>
</div>

<script>
    function dismissWaBubble(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const bubble = document.getElementById('waFloatingBubble');
        if (bubble) {
            bubble.style.setProperty('display', 'none', 'important');
            bubble.remove();
        }
    }
</script>
