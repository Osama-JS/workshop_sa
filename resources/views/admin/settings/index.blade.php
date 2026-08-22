@extends('admin.layouts.master')

@section('title', __('admin.settings'))

@section('page_icon')
    <i class="fa-solid fa-sliders text-wood-600"></i>
@endsection

@section('page_title', __('admin.menu_settings'))
@section('page_subtitle', 'إدارة الهوية البصرية، ألوان المنصة، الـ SEO، وبيانات التواصل وخادم البريد SMTP')

@section('content')
<div class="space-y-6">

    <!-- Tabs Navigation Header -->
    <div class="bg-white rounded-2xl p-2 border border-slate-200/80 shadow-xs flex flex-wrap gap-1.5" id="settingsTabs">
        <button type="button" onclick="switchTab('identity')" class="tab-btn active px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2" data-tab="identity">
            <i class="fa-solid fa-building-columns"></i>
            <span>{{ __('admin.tab_identity') }}</span>
        </button>
        <button type="button" onclick="switchTab('colors')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="colors">
            <i class="fa-solid fa-palette"></i>
            <span>{{ __('admin.tab_colors') }}</span>
        </button>
        <button type="button" onclick="switchTab('contact')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="contact">
            <i class="fa-solid fa-phone-volume"></i>
            <span>{{ __('admin.tab_contact') }}</span>
        </button>
        <button type="button" onclick="switchTab('social')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="social">
            <i class="fa-solid fa-share-nodes"></i>
            <span>{{ __('admin.tab_social') }}</span>
        </button>
        <button type="button" onclick="switchTab('hero')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="hero">
            <i class="fa-solid fa-panorama"></i>
            <span>{{ __('admin.tab_hero') }}</span>
        </button>
        <button type="button" onclick="switchTab('seo')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="seo">
            <i class="fa-solid fa-magnifying-glass-chart"></i>
            <span>{{ __('admin.tab_seo') }}</span>
        </button>
        <button type="button" onclick="switchTab('smtp')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="smtp">
            <i class="fa-solid fa-envelope-circle-check"></i>
            <span>{{ __('admin.tab_smtp') }}</span>
        </button>
        <button type="button" onclick="switchTab('ai')" class="tab-btn px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 text-slate-600 hover:bg-slate-50" data-tab="ai">
            <i class="fa-solid fa-wand-magic-sparkles text-gold-600"></i>
            <span>المساعد الذكي (AI Assistant)</span>
        </button>
    </div>

    <!-- TAB 1: IDENTITY & COMPANY INFO -->
    <div id="tab-content-identity" class="tab-pane space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="identity">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-building text-wood-600"></i>
                        <span>بيانات المنشأة والهوية العامة</span>
                    </h2>
                </div>

                <!-- Logos & Favicon Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-slate-50/70 rounded-2xl border border-slate-200/70">
                    <!-- Main Logo -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-slate-700">
                            الشعار الرئيسي (Main Logo)
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 p-2 flex items-center justify-center overflow-hidden shadow-xs">
                                @if(!empty($allSettings['site_logo']->value))
                                    <img id="logo_preview" src="{{ asset('storage/' . $allSettings['site_logo']->value) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <div id="logo_preview_placeholder" class="text-wood-600 text-3xl font-bold">🪵</div>
                                    <img id="logo_preview" src="" class="max-h-full max-w-full object-contain hidden">
                                @endif
                            </div>
                            <div class="flex-1 space-y-1.5">
                                <input type="file" name="site_logo" id="site_logo_input" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer" onchange="previewImage(this, 'logo_preview', 'logo_preview_placeholder')">
                                <p class="text-[11px] text-slate-400">يفضل صورة بصيغة PNG أو WEBP أو SVG بخلفية شفافة</p>
                            </div>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="space-y-3">
                        <label class="block text-xs font-semibold text-slate-700">
                            أيقونة المتصفح (Favicon)
                        </label>
                        <div class="flex items-center gap-4">
                            <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 p-2 flex items-center justify-center overflow-hidden shadow-xs">
                                @if(!empty($allSettings['site_favicon']->value))
                                    <img id="favicon_preview" src="{{ asset('storage/' . $allSettings['site_favicon']->value) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <div id="favicon_preview_placeholder" class="text-wood-600 text-3xl font-bold">🌐</div>
                                    <img id="favicon_preview" src="" class="max-h-full max-w-full object-contain hidden">
                                @endif
                            </div>
                            <div class="flex-1 space-y-1.5">
                                <input type="file" name="site_favicon" id="site_favicon_input" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer" onchange="previewImage(this, 'favicon_preview', 'favicon_preview_placeholder')">
                                <p class="text-[11px] text-slate-400">المقاس الموصى به: 64x64 أو 128x128 بكسل</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Name AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_name_ar">
                                اسم المنشأة (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_name_ar', 'site_name_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="site_name_ar" name="site_name_ar" value="{{ $allSettings['site_name_ar']->value ?? '' }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_name_en">
                                اسم المنشأة (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_name_en', 'site_name_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="site_name_en" name="site_name_en" value="{{ $allSettings['site_name_en']->value ?? '' }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                    </div>
                </div>

                <!-- Tagline AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_tagline_ar">
                                الشعار اللفظي / السلوجان (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_tagline_ar', 'site_tagline_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="site_tagline_ar" name="site_tagline_ar" value="{{ $allSettings['site_tagline_ar']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_tagline_en">
                                الشعار اللفظي / السلوجان (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_tagline_en', 'site_tagline_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="site_tagline_en" name="site_tagline_en" value="{{ $allSettings['site_tagline_en']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                    </div>
                </div>

                <!-- Footer Description AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_footer_desc_ar">
                                نبذة تعريفية لفوتر الموقع (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_footer_desc_ar', 'site_footer_desc_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <textarea id="site_footer_desc_ar" name="site_footer_desc_ar" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">{{ $allSettings['site_footer_desc_ar']->value ?? '' }}</textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="site_footer_desc_en">
                                نبذة تعريفية لفوتر الموقع (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('site_footer_desc_en', 'site_footer_desc_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <textarea id="site_footer_desc_en" name="site_footer_desc_en" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">{{ $allSettings['site_footer_desc_en']->value ?? '' }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 2: COLORS & BRANDING -->
    <div id="tab-content-colors" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="colors">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-palette text-wood-600"></i>
                            <span>تخصيص لوحة ألوان الموقع الخارجي</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">تحكم في ألوان الموقع بالكامل أو اختر من النماذج والستايلات الجاهزة بضغطة زر</p>
                    </div>
                </div>

                <!-- Theme Presets (Click-to-Apply) -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-wand-magic-sparkles text-amber-500 ml-1"></i> ستايلات وقوالب ألوان جاهزة ومختارة بعناية:
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                        <!-- Preset 1: Royal Dark -->
                        <button type="button" onclick="applyThemePreset('#b88b64', '#191512', '#D4AF37', '#0d0a08', '#f8fafc', this)"
                            class="theme-preset-btn p-3 rounded-xl border-2 border-slate-200 hover:border-wood-600 bg-slate-900 text-right space-y-2 transition group text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold block text-gold-400">الستايل الليلي الفاخر</span>
                                <i class="fa-solid fa-moon text-gold-400 text-xs"></i>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #b88b64"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #191512"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #D4AF37"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #0d0a08"></span>
                            </div>
                        </button>

                        <!-- Preset 2: Modern Light -->
                        <button type="button" onclick="applyThemePreset('#8e5b32', '#ffffff', '#b48222', '#f8f6f2', '#1e293b', this)"
                            class="theme-preset-btn p-3 rounded-xl border-2 border-slate-200 hover:border-wood-600 bg-[#f8f6f2] text-right space-y-2 transition group text-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold block text-wood-700">الستايل النهاري العصري</span>
                                <i class="fa-solid fa-sun text-amber-500 text-xs"></i>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #8e5b32"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #ffffff"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #b48222"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #f8f6f2"></span>
                            </div>
                        </button>

                        <!-- Preset 3: Classic Walnut -->
                        <button type="button" onclick="applyThemePreset('#78350f', '#1c1917', '#d97706', '#0c0a09', '#fafaf9', this)"
                            class="theme-preset-btn p-3 rounded-xl border-2 border-slate-200 hover:border-wood-600 bg-stone-900 text-right space-y-2 transition group text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold block text-amber-500">خشب الجوز والبرونز</span>
                                <i class="fa-solid fa-tree text-amber-600 text-xs"></i>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #78350f"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #1c1917"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #d97706"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #0c0a09"></span>
                            </div>
                        </button>

                        <!-- Preset 4: Emerald & Gold -->
                        <button type="button" onclick="applyThemePreset('#047857', '#0f172a', '#fbbf24', '#022c22', '#f0fdf4', this)"
                            class="theme-preset-btn p-3 rounded-xl border-2 border-slate-200 hover:border-wood-600 bg-[#022c22] text-right space-y-2 transition group text-white">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold block text-emerald-300">الزمردي والقصور</span>
                                <i class="fa-solid fa-gem text-emerald-400 text-xs"></i>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #047857"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #0f172a"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #fbbf24"></span>
                                <span class="w-4 h-4 rounded-full border border-white/20" style="background-color: #022c22"></span>
                            </div>
                        </button>

                        <!-- Preset 5: Nordic Oak -->
                        <button type="button" onclick="applyThemePreset('#a16207', '#ffffff', '#ca8a04', '#fafaf9', '#0f172a', this)"
                            class="theme-preset-btn p-3 rounded-xl border-2 border-slate-200 hover:border-wood-600 bg-[#fafaf9] text-right space-y-2 transition group text-slate-900">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold block text-amber-700">السنديان النقي (نهاري)</span>
                                <i class="fa-solid fa-leaf text-amber-600 text-xs"></i>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #a16207"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #ffffff"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #ca8a04"></span>
                                <span class="w-4 h-4 rounded-full border border-slate-300" style="background-color: #fafaf9"></span>
                            </div>
                        </button>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <label class="block text-xs font-bold text-slate-700 mb-4">
                        <i class="fa-solid fa-sliders text-wood-600 ml-1"></i> أو تخصيص كل لون بدقة حسب رغبتك:
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Primary Color -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800">اللون الأساسي (Primary / Wood)</span>
                            <div id="preview_primary_box" class="w-7 h-7 rounded-lg border border-slate-300 shadow-xs" style="background-color: {{ $allSettings['primary_color']->value ?? '#b88b64' }}"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="primary_color" name="primary_color" value="{{ $allSettings['primary_color']->value ?? '#b88b64' }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('preview_primary_box').style.backgroundColor = this.value; document.getElementById('primary_color_text').value = this.value">
                            <input type="text" id="primary_color_text" value="{{ $allSettings['primary_color']->value ?? '#b88b64' }}"
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700" oninput="document.getElementById('primary_color').value = this.value; document.getElementById('preview_primary_box').style.backgroundColor = this.value">
                        </div>
                        <p class="text-[11px] text-slate-400">يستخدم في الأزرار الرئيسية، العناوين البارزة، وعناصر التمييز الخشبية.</p>
                    </div>

                    <!-- Secondary Color -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800">اللون الثانوي (Secondary / Cards)</span>
                            <div id="preview_secondary_box" class="w-7 h-7 rounded-lg border border-slate-300 shadow-xs" style="background-color: {{ $allSettings['secondary_color']->value ?? '#191512' }}"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="secondary_color" name="secondary_color" value="{{ $allSettings['secondary_color']->value ?? '#191512' }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('preview_secondary_box').style.backgroundColor = this.value; document.getElementById('secondary_color_text').value = this.value">
                            <input type="text" id="secondary_color_text" value="{{ $allSettings['secondary_color']->value ?? '#191512' }}"
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700" oninput="document.getElementById('secondary_color').value = this.value; document.getElementById('preview_secondary_box').style.backgroundColor = this.value">
                        </div>
                        <p class="text-[11px] text-slate-400">يستخدم في خلفيات بطاقات الخدمات، الفوتر، والحاويات الزجاجية.</p>
                    </div>

                    <!-- Accent Color -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800">لون اللمسات الذهبية (Accent / Gold)</span>
                            <div id="preview_accent_box" class="w-7 h-7 rounded-lg border border-slate-300 shadow-xs" style="background-color: {{ $allSettings['accent_color']->value ?? '#D4AF37' }}"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="accent_color" name="accent_color" value="{{ $allSettings['accent_color']->value ?? '#D4AF37' }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('preview_accent_box').style.backgroundColor = this.value; document.getElementById('accent_color_text').value = this.value">
                            <input type="text" id="accent_color_text" value="{{ $allSettings['accent_color']->value ?? '#D4AF37' }}"
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700" oninput="document.getElementById('accent_color').value = this.value; document.getElementById('preview_accent_box').style.backgroundColor = this.value">
                        </div>
                        <p class="text-[11px] text-slate-400">يستخدم في التدرجات الذهبية، الإطارات المشعة، وأزرار طلب التسعيرة.</p>
                    </div>

                    <!-- Background Dark Color -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800">خلفية الموقع الرئيسية (Main Background)</span>
                            <div id="preview_bg_box" class="w-7 h-7 rounded-lg border border-slate-300 shadow-xs" style="background-color: {{ $allSettings['bg_dark_color']->value ?? '#0d0a08' }}"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="bg_dark_color" name="bg_dark_color" value="{{ $allSettings['bg_dark_color']->value ?? '#0d0a08' }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('preview_bg_box').style.backgroundColor = this.value; document.getElementById('bg_dark_color_text').value = this.value">
                            <input type="text" id="bg_dark_color_text" value="{{ $allSettings['bg_dark_color']->value ?? '#0d0a08' }}"
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700" oninput="document.getElementById('bg_dark_color').value = this.value; document.getElementById('preview_bg_box').style.backgroundColor = this.value">
                        </div>
                        <p class="text-[11px] text-slate-400">اللون المعتمد للخلفية الشاملة لكافة صفحات الموقع الخارجي.</p>
                    </div>

                    <!-- Text Light Color -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-xs text-slate-800">لون النصوص الرئيسية (Text Color)</span>
                            <div id="preview_text_box" class="w-7 h-7 rounded-lg border border-slate-300 shadow-xs" style="background-color: {{ $allSettings['text_light_color']->value ?? '#f8fafc' }}"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="color" id="text_light_color" name="text_light_color" value="{{ $allSettings['text_light_color']->value ?? '#f8fafc' }}"
                                class="w-12 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 bg-white" oninput="document.getElementById('preview_text_box').style.backgroundColor = this.value; document.getElementById('text_light_color_text').value = this.value">
                            <input type="text" id="text_light_color_text" value="{{ $allSettings['text_light_color']->value ?? '#f8fafc' }}"
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-mono text-slate-700" oninput="document.getElementById('text_light_color').value = this.value; document.getElementById('preview_text_box').style.backgroundColor = this.value">
                        </div>
                        <p class="text-[11px] text-slate-400">لون الخطوط الأساسية المقروءة على الخلفيات الداكنة.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 3: CONTACT & LOCATION -->
    <div id="tab-content-contact" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="contact">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-phone text-wood-600"></i>
                        <span>بيانات التواصل وساعات العمل والعنوان</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="contact_phone">
                            رقم الهاتف المباشر
                        </label>
                        <input type="text" id="contact_phone" name="contact_phone" value="{{ $allSettings['contact_phone']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="+966 50 123 4567">
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="contact_whatsapp">
                            رقم الواتساب للاستفسارات
                        </label>
                        <input type="text" id="contact_whatsapp" name="contact_whatsapp" value="{{ $allSettings['contact_whatsapp']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="+966 50 123 4567">
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="contact_email">
                            البريد الإلكتروني الرسمي
                        </label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ $allSettings['contact_email']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="info@artisanwood.sa">
                    </div>
                </div>

                <!-- Addresses AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="contact_address_ar">
                                العنوان والموقع الجغرافي (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('contact_address_ar', 'contact_address_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="contact_address_ar" name="contact_address_ar" value="{{ $allSettings['contact_address_ar']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="contact_address_en">
                                العنوان والموقع الجغرافي (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('contact_address_en', 'contact_address_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="contact_address_en" name="contact_address_en" value="{{ $allSettings['contact_address_en']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>
                </div>

                <!-- Working Hours AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="working_hours_ar">
                                مواعيد وساعات العمل (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('working_hours_ar', 'working_hours_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="working_hours_ar" name="working_hours_ar" value="{{ $allSettings['working_hours_ar']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="working_hours_en">
                                مواعيد وساعات العمل (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('working_hours_en', 'working_hours_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="working_hours_en" name="working_hours_en" value="{{ $allSettings['working_hours_en']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="google_maps_embed">
                        كود تضمين خريطة جوجل (Google Maps Iframe Embed)
                    </label>
                    <textarea id="google_maps_embed" name="google_maps_embed" rows="2"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                        placeholder="<iframe src='https://www.google.com/maps/embed?...'></iframe>">{{ $allSettings['google_maps_embed']->value ?? '' }}</textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 4: SOCIAL MEDIA -->
    <div id="tab-content-social" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="social">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-share-nodes text-wood-600"></i>
                        <span>روابط منصات التواصل الاجتماعي</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Instagram -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="social_instagram">
                            <i class="fa-brands fa-instagram text-rose-500 ml-1"></i> حساب انستغرام (Instagram)
                        </label>
                        <input type="url" id="social_instagram" name="social_instagram" value="{{ $allSettings['social_instagram']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="https://instagram.com/your_account">
                    </div>

                    <!-- X / Twitter -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="social_x">
                            <i class="fa-brands fa-x-twitter text-slate-900 ml-1"></i> حساب منصة X (تويتر سابقاً)
                        </label>
                        <input type="url" id="social_x" name="social_x" value="{{ $allSettings['social_x']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="https://x.com/your_account">
                    </div>

                    <!-- TikTok -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="social_tiktok">
                            <i class="fa-brands fa-tiktok text-slate-900 ml-1"></i> حساب تيك توك (TikTok)
                        </label>
                        <input type="url" id="social_tiktok" name="social_tiktok" value="{{ $allSettings['social_tiktok']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="https://tiktok.com/@your_account">
                    </div>

                    <!-- Snapchat -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="social_snapchat">
                            <i class="fa-brands fa-snapchat text-amber-500 ml-1"></i> حساب سناب شات (Snapchat)
                        </label>
                        <input type="url" id="social_snapchat" name="social_snapchat" value="{{ $allSettings['social_snapchat']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="https://snapchat.com/add/your_account">
                    </div>

                    <!-- LinkedIn -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="social_linkedin">
                            <i class="fa-brands fa-linkedin text-blue-600 ml-1"></i> حساب لينكد إن (LinkedIn)
                        </label>
                        <input type="url" id="social_linkedin" name="social_linkedin" value="{{ $allSettings['social_linkedin']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="https://linkedin.com/company/your_account">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB: HERO SECTION CONFIGURATION -->
    <div id="tab-content-hero" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="hero">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-panorama text-wood-600"></i>
                        <span>تخصيص قسم البداية بالصفحة الرئيسية (Hero Section Mode)</span>
                    </h2>
                    <a href="{{ route('admin.hero-slides.index') }}" class="text-xs font-bold text-wood-700 bg-wood-50 hover:bg-wood-100 px-3 py-1.5 rounded-xl transition">
                        <i class="fa-solid fa-list-check ml-1"></i> إدارة شرائح السلايدر ({{ \App\Models\HeroSlide::count() }})
                    </a>
                </div>

                <!-- Hero Display Type Selector -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                        نوع وطريقة عرض قسم البداية (Hero Type) <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Mode 1: Slider -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none {{ ($allSettings['hero_type']->value ?? 'slider') === 'slider' ? 'border-wood-600 bg-wood-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-images text-wood-600"></i>
                                    <span>سلايدر شرائح متحركة</span>
                                </span>
                                <input type="radio" name="hero_type" value="slider" {{ ($allSettings['hero_type']->value ?? 'slider') === 'slider' ? 'checked' : '' }} class="text-wood-600 focus:ring-wood-500">
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                عرض شرائح متعددة متحركة تلقائياً مع نصوص وعناوين وأزرار مخصصة لكل شريحة.
                            </p>
                        </label>

                        <!-- Mode 2: Video -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none {{ ($allSettings['hero_type']->value ?? 'slider') === 'video' ? 'border-wood-600 bg-wood-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-brands fa-youtube text-red-500"></i>
                                    <span>خلفية فيديو تفاعلية</span>
                                </span>
                                <input type="radio" name="hero_type" value="video" {{ ($allSettings['hero_type']->value ?? '') === 'video' ? 'checked' : '' }} class="text-wood-600 focus:ring-wood-500">
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                تشغيل فيديو حي في الخلفية مع طبقة داكنة فخمة ونصوص إرشادية وأزرار طلب مباشر.
                            </p>
                        </label>

                        <!-- Mode 3: Static Image -->
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition select-none {{ ($allSettings['hero_type']->value ?? 'slider') === 'static' ? 'border-wood-600 bg-wood-50/50' : 'border-slate-200 hover:border-slate-300' }}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-image text-emerald-600"></i>
                                    <span>صورة ثابتة فاخرة</span>
                                </span>
                                <input type="radio" name="hero_type" value="static" {{ ($allSettings['hero_type']->value ?? '') === 'static' ? 'checked' : '' }} class="text-wood-600 focus:ring-wood-500">
                            </div>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                صورة خشبية عالية الدقة ثابتة مع تأثيرات بصرية جذابة وسرعة تحميل فائقة.
                            </p>
                        </label>
                    </div>
                </div>

                <!-- Video Background Options -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-film text-wood-600"></i>
                        <span>إعدادات خلفية الفيديو (عند اختيار نمط الفيديو)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="hero_video_url">
                                رابط الفيديو (Direct MP4 URL / YouTube Video Embed)
                            </label>
                            <input type="text" id="hero_video_url" name="hero_video_url" value="{{ $allSettings['hero_video_url']->value ?? '' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                                placeholder="https://example.com/woodwork-video.mp4">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                درجة قتامة الطبقة التعتيمية (Overlay Opacity)
                            </label>
                            <select name="hero_overlay_opacity" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                                <option value="0.7" {{ ($allSettings['hero_overlay_opacity']->value ?? '0.7') == '0.7' ? 'selected' : '' }}>70% قتامة (موصى به لقراءة واضحة)</option>
                                <option value="0.5" {{ ($allSettings['hero_overlay_opacity']->value ?? '') == '0.5' ? 'selected' : '' }}>50% قتامة متوازنة</option>
                                <option value="0.3" {{ ($allSettings['hero_overlay_opacity']->value ?? '') == '0.3' ? 'selected' : '' }}>30% خفيفة لإبراز تفاصيل الفيديو</option>
                                <option value="0.85" {{ ($allSettings['hero_overlay_opacity']->value ?? '') == '0.85' ? 'selected' : '' }}>85% داكنة جداً وفخمة</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Static Cover Image Option -->
                <div class="space-y-4 pt-4 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-camera text-wood-600"></i>
                        <span>صورة الغلاف الثابتة (عند اختيار نمط الصورة الثابتة)</span>
                    </h3>
                    <div class="flex items-center gap-4">
                        @if(!empty($allSettings['hero_static_image']->value))
                            <img src="{{ asset('storage/' . $allSettings['hero_static_image']->value) }}" class="w-24 h-16 rounded-xl object-cover ring-2 ring-slate-200">
                        @endif
                        <input type="file" name="hero_static_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-wood-100 file:text-wood-800 hover:file:bg-wood-200 cursor-pointer">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 5: SEO OPTIMIZATION -->
    <div id="tab-content-seo" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="seo">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass-chart text-wood-600"></i>
                            <span>تحسين محركات البحث والـ Meta Tags (SEO)</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">ضبط العناوين والكلمات المفتاحية التي تظهر في محركات البحث ومشاركات السوشيال ميديا</p>
                    </div>
                </div>

                <!-- Google Search Live Snippet Preview Box -->
                <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2">
                    <span class="text-xs font-bold text-slate-600 block uppercase tracking-wider">
                        <i class="fa-brands fa-google text-blue-600 ml-1"></i> {{ __('admin.seo_preview_title') }}
                    </span>
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs space-y-1">
                        <div class="text-xs text-slate-500 flex items-center gap-1 font-sans">
                            <span class="text-slate-700 font-semibold">{{ url('/') }}</span>
                            <span class="text-slate-400">&rsaquo; home</span>
                        </div>
                        <div id="seo_live_title" class="text-base sm:text-lg font-medium text-[#1a0dab] hover:underline cursor-pointer leading-snug">
                            {{ $allSettings['seo_meta_title_ar']->value ?? 'أرتيزان للأعمال الخشبية والديكور' }}
                        </div>
                        <div id="seo_live_desc" class="text-xs text-[#4d5156] leading-relaxed line-clamp-2">
                            {{ $allSettings['seo_meta_desc_ar']->value ?? 'أفضل ورشة نجارة وأعمال خشبية متخصصة في صناعة غرف النوم العصرية، المكاتب التنفيذية، وبوثات المعارض.' }}
                        </div>
                    </div>
                </div>

                <!-- SEO Title AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_meta_title_ar">
                                عنوان الـ Meta Title (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_meta_title_ar', 'seo_meta_title_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="seo_meta_title_ar" name="seo_meta_title_ar" value="{{ $allSettings['seo_meta_title_ar']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            oninput="document.getElementById('seo_live_title').innerText = this.value || 'عنوان الموقع'">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_meta_title_en">
                                عنوان الـ Meta Title (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_meta_title_en', 'seo_meta_title_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="seo_meta_title_en" name="seo_meta_title_en" value="{{ $allSettings['seo_meta_title_en']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>
                </div>

                <!-- SEO Description AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_meta_desc_ar">
                                وصف محركات البحث Meta Description (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_meta_desc_ar', 'seo_meta_desc_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <textarea id="seo_meta_desc_ar" name="seo_meta_desc_ar" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            oninput="document.getElementById('seo_live_desc').innerText = this.value || 'وصف الموقع'">{{ $allSettings['seo_meta_desc_ar']->value ?? '' }}</textarea>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_meta_desc_en">
                                وصف محركات البحث Meta Description (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_meta_desc_en', 'seo_meta_desc_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <textarea id="seo_meta_desc_en" name="seo_meta_desc_en" rows="3"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">{{ $allSettings['seo_meta_desc_en']->value ?? '' }}</textarea>
                    </div>
                </div>

                <!-- SEO Keywords AR & EN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_keywords_ar">
                                الكلمات المفتاحية Meta Keywords (بالعربي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_keywords_ar', 'seo_keywords_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="seo_keywords_ar" name="seo_keywords_ar" value="{{ $allSettings['seo_keywords_ar']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="ورشة نجارة, تفصيل غرف نوم, مكاتب فخمة">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700" for="seo_keywords_en">
                                الكلمات المفتاحية Meta Keywords (بالإنجليزي)
                            </label>
                            <button type="button" onclick="autoTranslate('seo_keywords_en', 'seo_keywords_ar', 'en', 'ar', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 inline-flex items-center gap-1">
                                <i class="fa-solid fa-language"></i> {{ __('admin.translate_btn') }}
                            </button>
                        </div>
                        <input type="text" id="seo_keywords_en" name="seo_keywords_en" value="{{ $allSettings['seo_keywords_en']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="woodworking workshop, bespoke furniture, luxury offices">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB 6: SMTP & MAIL SETTINGS -->
    <div id="tab-content-smtp" class="tab-pane hidden space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="smtp">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-envelope-circle-check text-wood-600"></i>
                            <span>إعدادات خادم البريد الإلكتروني (SMTP Settings)</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">تكوين بيانات الربط بالبريد الذي سيستخدم لإرسال الإشعارات وتأكيدات الطلبات</p>
                    </div>

                    <!-- Send Test Mail Button -->
                    <button type="button" onclick="triggerTestMailModal()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        <span>{{ __('admin.test_mail_btn') }}</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Mail Mailer -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_mailer">
                            بروتوكول الإرسال (Driver)
                        </label>
                        <select id="mail_mailer" name="mail_mailer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                            <option value="smtp" {{ ($allSettings['mail_mailer']->value ?? '') === 'smtp' ? 'selected' : '' }}>SMTP (خادم مخصص)</option>
                            <option value="log" {{ ($allSettings['mail_mailer']->value ?? '') === 'log' ? 'selected' : '' }}>Log (تسجيل محلي للتطوير)</option>
                        </select>
                    </div>

                    <!-- SMTP Host -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_host">
                            خادم البريد (SMTP Host)
                        </label>
                        <input type="text" id="mail_host" name="mail_host" value="{{ $allSettings['mail_host']->value ?? 'smtp.mailtrap.io' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="smtp.gmail.com أو smtp.hostinger.com">
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_port">
                            منفذ البريد (Port)
                        </label>
                        <input type="text" id="mail_port" name="mail_port" value="{{ $allSettings['mail_port']->value ?? '587' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="587 أو 465 أو 2525">
                    </div>

                    <!-- SMTP Username -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_username">
                            اسم مستخدم البريد (Username)
                        </label>
                        <input type="text" id="mail_username" name="mail_username" value="{{ $allSettings['mail_username']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="user@example.com">
                    </div>

                    <!-- SMTP Password -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_password">
                            كلمة مرور البريد (Password / App Password)
                        </label>
                        <input type="password" id="mail_password" name="mail_password" value="{{ $allSettings['mail_password']->value ?? '' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-mono text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="••••••••">
                    </div>

                    <!-- Encryption -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_encryption">
                            نوع التشفير (Encryption)
                        </label>
                        <select id="mail_encryption" name="mail_encryption" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                            <option value="tls" {{ ($allSettings['mail_encryption']->value ?? '') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($allSettings['mail_encryption']->value ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="" {{ empty($allSettings['mail_encryption']->value) ? 'selected' : '' }}>بدون تشفير (None)</option>
                        </select>
                    </div>

                    <!-- From Address -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_from_address">
                            بريد المرسل (From Address)
                        </label>
                        <input type="email" id="mail_from_address" name="mail_from_address" value="{{ $allSettings['mail_from_address']->value ?? 'notifications@artisanwood.sa' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition"
                            placeholder="notifications@artisanwood.sa">
                    </div>

                    <!-- From Name -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="mail_from_name">
                            اسم المرسل (From Name)
                        </label>
                        <input type="text" id="mail_from_name" name="mail_from_name" value="{{ $allSettings['mail_from_name']->value ?? 'ورشة أرتيزان للأعمال الخشبية' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>

                    <!-- Notification Receiver Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="notification_receiver_email">
                            البريد المستلم للإشعارات والطلبات الجديدة
                        </label>
                        <input type="email" id="notification_receiver_email" name="notification_receiver_email" value="{{ $allSettings['notification_receiver_email']->value ?? 'admin@artisanwood.sa' }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                        <i class="fa-solid fa-floppy-disk ml-1"></i> {{ __('admin.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- TAB: AI ASSISTANT CONFIGURATION -->
    <div id="tab-content-ai" class="tab-pane space-y-6 hidden">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="ai">

            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-8">
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-wand-magic-sparkles text-gold-600"></i>
                            <span>إعدادات وهوية المساعد الذكي (Google Gemini AI)</span>
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">التحكم بمفتاح Gemini API، اسم المساعد، ونصوص الترحيب، وقواعد السلوك والقيود الصارمة.</p>
                    </div>

                    <!-- AI Master Toggle -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="ai_enabled" value="0">
                        <input type="checkbox" name="ai_enabled" value="1" class="sr-only peer" {{ ($allSettings['ai_enabled']->value ?? '1') === '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        <span class="ms-3 text-xs font-bold text-slate-700">تفعيل المساعد الذكي في الموقع</span>
                    </label>
                </div>

                <!-- 1. Google Gemini API Config -->
                <div class="p-5 bg-slate-50/80 rounded-2xl border border-slate-200/70 space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-key text-wood-600"></i>
                        <span>إعدادات الاتصال بـ Google Gemini API</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Gemini API Key -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_gemini_api_key">
                                مفتاح Google Gemini API Key
                            </label>
                            <div class="relative">
                                <input type="password" id="ai_gemini_api_key" name="ai_gemini_api_key" value="{{ $allSettings['ai_gemini_api_key']->value ?? '' }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500 font-mono"
                                    placeholder="AIzaSy...">
                                <button type="button" onclick="togglePasswordVisibility('ai_gemini_api_key', this)" class="absolute top-2.5 left-3 text-slate-400 hover:text-slate-600 text-xs">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-1">يمكنك الحصول على مفتاح API مجاني أو مدفوع من <a href="https://aistudio.google.com/app/apikey" target="_blank" class="text-wood-600 font-bold hover:underline">Google AI Studio</a>.</p>
                        </div>

                        <!-- AI Model -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_model">
                                النموذج المعتمد (Gemini Model)
                            </label>
                            <select id="ai_model" name="ai_model" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                                <option value="gemini-1.5-flash" {{ ($allSettings['ai_model']->value ?? 'gemini-1.5-flash') === 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash (سريع واقتصادي وموصى به)</option>
                                <option value="gemini-2.0-flash" {{ ($allSettings['ai_model']->value ?? '') === 'gemini-2.0-flash' ? 'selected' : '' }}>Gemini 2.0 Flash (الجيل الأحدث فائق السرعة)</option>
                                <option value="gemini-1.5-pro" {{ ($allSettings['ai_model']->value ?? '') === 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro (ذكاء هندسي متقدم)</option>
                            </select>
                        </div>

                        <!-- Temperature & Max Tokens -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_temperature">
                                    درجة الإبداع (Temperature)
                                </label>
                                <input type="number" step="0.1" min="0.0" max="1.0" id="ai_temperature" name="ai_temperature" value="{{ $allSettings['ai_temperature']->value ?? '0.7' }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_max_tokens">
                                    الحد الأقصى للرد (Tokens)
                                </label>
                                <input type="number" step="100" min="200" max="4000" id="ai_max_tokens" name="ai_max_tokens" value="{{ $allSettings['ai_max_tokens']->value ?? '1000' }}"
                                    class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:outline-none focus:border-wood-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Persona & Hospitality -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-user-tie text-wood-600"></i>
                        <span>هوية ومسمى المساعد الذكي</span>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Name AR -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_bot_name_ar">اسم المساعد (بالعربي)</label>
                            <input type="text" id="ai_bot_name_ar" name="ai_bot_name_ar" value="{{ $allSettings['ai_bot_name_ar']->value ?? 'مستشار أرتيزان الذكي' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500">
                        </div>

                        <!-- Name EN -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700" for="ai_bot_name_en">اسم المساعد (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('ai_bot_name_ar', 'ai_bot_name_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 flex items-center gap-1">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="ai_bot_name_en" name="ai_bot_name_en" value="{{ $allSettings['ai_bot_name_en']->value ?? 'Artisan AI Wood Consultant' }}" dir="ltr"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500">
                        </div>

                        <!-- Role AR -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_bot_role_ar">المسمى الوظيفي والدور (بالعربي)</label>
                            <input type="text" id="ai_bot_role_ar" name="ai_bot_role_ar" value="{{ $allSettings['ai_bot_role_ar']->value ?? 'مستشار تفصيل الأثاث والأعمال الخشبية' }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500">
                        </div>

                        <!-- Role EN -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700" for="ai_bot_role_en">المسمى الوظيفي والدور (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('ai_bot_role_ar', 'ai_bot_role_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 flex items-center gap-1">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <input type="text" id="ai_bot_role_en" name="ai_bot_role_en" value="{{ $allSettings['ai_bot_role_en']->value ?? 'Joinery Engineering & Luxury Woodwork Specialist' }}" dir="ltr"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500">
                        </div>

                        <!-- Welcome Msg AR -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_welcome_msg_ar">رسالة الترحيب الأولى للمستخدم (بالعربي)</label>
                            <textarea id="ai_welcome_msg_ar" name="ai_welcome_msg_ar" rows="2"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 leading-relaxed">{{ $allSettings['ai_welcome_msg_ar']->value ?? 'أهلاً بك في ورشة أرتيزان للأعمال الخشبية الفاخرة! 🪵✨ أنا مستشارك الحرفي والهندسي، كيف يمكنني مساعدتك اليوم؟' }}</textarea>
                        </div>

                        <!-- Welcome Msg EN -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700" for="ai_welcome_msg_en">رسالة الترحيب الأولى (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('ai_welcome_msg_ar', 'ai_welcome_msg_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 flex items-center gap-1">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="ai_welcome_msg_en" name="ai_welcome_msg_en" rows="2" dir="ltr"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 leading-relaxed">{{ $allSettings['ai_welcome_msg_en']->value ?? 'Welcome to Artisan Luxury Woodwork Workshop! 🪵✨ I am your AI Joinery Consultant. How can I assist you today?' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 3. System Guardrails & Instructions -->
                <div class="space-y-4">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-wood-600"></i>
                        <span>التعليمات والقواعد الصارمة (System Prompt & Guardrails)</span>
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="ai_system_prompt_ar">
                                موجه النظام وشروط السلوك (بالعربي)
                            </label>
                            <textarea id="ai_system_prompt_ar" name="ai_system_prompt_ar" rows="6"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 font-mono leading-relaxed">{{ $allSettings['ai_system_prompt_ar']->value ?? '' }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-slate-700" for="ai_system_prompt_en">موجه النظام (بالإنجليزي)</label>
                                <button type="button" onclick="autoTranslate('ai_system_prompt_ar', 'ai_system_prompt_en', 'ar', 'en', this)" class="text-[11px] font-bold text-wood-600 hover:text-wood-700 flex items-center gap-1">
                                    <i class="fa-solid fa-language"></i> ترجمة
                                </button>
                            </div>
                            <textarea id="ai_system_prompt_en" name="ai_system_prompt_en" rows="6" dir="ltr"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 font-mono leading-relaxed">{{ $allSettings['ai_system_prompt_en']->value ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ إعدادات المساعد الذكي</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Tab switching using pure Vanilla JS (works instantly with 0 dependencies)
    function switchTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(el => {
            el.classList.add('hidden');
        });

        // Show target tab pane
        const targetPane = document.getElementById('tab-content-' + tabId);
        if (targetPane) {
            targetPane.classList.remove('hidden');
        }

        // Update active styling on tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-wood-600', 'text-white', 'shadow-md', 'shadow-wood-600/20', 'active');
            btn.classList.add('text-slate-600', 'hover:bg-slate-50');
        });

        const activeBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('bg-wood-600', 'text-white', 'shadow-md', 'shadow-wood-600/20', 'active');
            activeBtn.classList.remove('text-slate-600', 'hover:bg-slate-50');
        }

        // Save active tab in local storage
        try {
            localStorage.setItem('admin_active_settings_tab', tabId);
        } catch(e) {}

        if (typeof initAllSelect2AndDatepickers === 'function') {
            setTimeout(initAllSelect2AndDatepickers, 50);
        }
    }

    // Restore active tab on load
    document.addEventListener('DOMContentLoaded', function() {
        let savedTab = 'identity';
        try {
            savedTab = localStorage.getItem('admin_active_settings_tab') || 'identity';
        } catch(e) {}

        if (document.getElementById('tab-content-' + savedTab)) {
            switchTab(savedTab);
        } else {
            switchTab('identity');
        }
    });

    // Image preview helper
    function previewImage(input, previewImgId, placeholderId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById(placeholderId);
                const previewImg = document.getElementById(previewImgId);
                if (placeholder) placeholder.classList.add('hidden');
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Trigger SMTP Test Email with SweetAlert2 / Fetch
    function triggerTestMailModal() {
        if (typeof Swal === 'undefined') {
            const email = prompt("{{ __('admin.test_mail_prompt') }}", "{{ auth()->user()->email }}");
            if (email) {
                fetch("{{ route('admin.settings.send-test-mail') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ test_email: email })
                })
                .then(r => r.json())
                .then(data => alert(data.message || (data.success ? 'Success' : 'Failed')))
                .catch(e => alert('Error sending test email'));
            }
            return;
        }

        Swal.fire({
            title: "{{ __('admin.test_mail_title') }}",
            text: "{{ __('admin.test_mail_prompt') }}",
            input: 'email',
            inputValue: "{{ auth()->user()->email }}",
            inputPlaceholder: 'user@example.com',
            showCancelButton: true,
            confirmButtonText: "{{ __('admin.test_mail_btn') }}",
            cancelButtonText: "{{ __('admin.cancel') }}",
            confirmButtonColor: '#059669',
            showLoaderOnConfirm: true,
            preConfirm: async (email) => {
                if (!email) {
                    Swal.showValidationMessage('يرجى كتابة بريد إلكتروني صحيح');
                    return false;
                }

                try {
                    const response = await fetch("{{ route('admin.settings.send-test-mail') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ test_email: email })
                    });

                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || "{{ __('admin.test_mail_failed') }}");
                    }
                    return data;
                } catch (error) {
                    Swal.showValidationMessage(error.message || "{{ __('admin.test_mail_failed') }}");
                }
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed && result.value && result.value.success) {
                Swal.fire({
                    icon: 'success',
                    title: "{{ __('admin.success') }}",
                    text: result.value.message || "{{ __('admin.test_mail_success') }}",
                    confirmButtonColor: '#059669'
                });
            }
        });
    }

    // Apply Theme Preset Helper Function
    function applyThemePreset(primary, secondary, accent, bgDark, textLight, btn) {
        // Set values for primary color
        const primInput = document.getElementById('primary_color');
        const primText = document.getElementById('primary_color_text');
        const primBox = document.getElementById('preview_primary_box');
        if (primInput) primInput.value = primary;
        if (primText) primText.value = primary;
        if (primBox) primBox.style.backgroundColor = primary;

        // Set values for secondary color
        const secInput = document.getElementById('secondary_color');
        const secText = document.getElementById('secondary_color_text');
        const secBox = document.getElementById('preview_secondary_box');
        if (secInput) secInput.value = secondary;
        if (secText) secText.value = secondary;
        if (secBox) secBox.style.backgroundColor = secondary;

        // Set values for accent color
        const accInput = document.getElementById('accent_color');
        const accText = document.getElementById('accent_color_text');
        const accBox = document.getElementById('preview_accent_box');
        if (accInput) accInput.value = accent;
        if (accText) accText.value = accent;
        if (accBox) accBox.style.backgroundColor = accent;

        // Set values for background dark color
        const bgInput = document.getElementById('bg_dark_color');
        const bgText = document.getElementById('bg_dark_color_text');
        const bgBox = document.getElementById('preview_bg_box');
        if (bgInput) bgInput.value = bgDark;
        if (bgText) bgText.value = bgDark;
        if (bgBox) bgBox.style.backgroundColor = bgDark;

        // Set values for text light color
        const txtInput = document.getElementById('text_light_color');
        const txtText = document.getElementById('text_light_color_text');
        const txtBox = document.getElementById('preview_text_box');
        if (txtInput) txtInput.value = textLight;
        if (txtText) txtText.value = textLight;
        if (txtBox) txtBox.style.backgroundColor = textLight;

        // Highlight active preset button
        document.querySelectorAll('.theme-preset-btn').forEach(b => {
            b.classList.remove('ring-4', 'ring-wood-500', 'border-wood-600');
        });
        if (btn) {
            btn.classList.add('ring-4', 'ring-wood-500', 'border-wood-600');
        }

        if (typeof toastr !== 'undefined') {
            toastr.success('تم اختيار الستايل بنجاح! اضغط على "حفظ التغييرات" لتطبيقه على الموقع الخارجي.', 'تم التحديد');
        }
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input) {
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i class="fa-solid fa-eye-slash"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i class="fa-solid fa-eye"></i>';
            }
        }
    }
</script>
@endpush

