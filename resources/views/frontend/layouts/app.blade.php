<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('meta_title_' . app()->getLocale(), \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية الفاخرة')))</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_desc_' . app()->getLocale(), 'ورشة سعودية رائدة متخصصة في صناعة غرف النوم الفاخرة، المكاتب التنفيذية، بوثات المعارض، والديكورات والتكسيات الخشبية الراقية.'))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords_' . app()->getLocale(), 'أعمال خشبية, نجارة فاخرة, غرف نوم خشب طبيعي, بوثات معارض الرياض, ديكورات خشبية, ورشة نجارة بالرياض'))">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook / WhatsApp Preview -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', \App\Models\Setting::get('meta_title_' . app()->getLocale(), \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية الفاخرة')))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('meta_desc_' . app()->getLocale(), 'ورشة سعودية متخصصة في تفصيل وصناعة أفخر الأعمال الخشبية والديكورات.'))">
    @if($logo = \App\Models\Setting::get('site_logo'))
        <meta property="og:image" content="{{ asset('storage/' . $logo) }}">
    @endif
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', \App\Models\Setting::get('site_name_' . app()->getLocale()))">
    <meta name="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('meta_desc_' . app()->getLocale()))">

    <!-- Schema.org JSON-LD LocalBusiness Structured Data for Google -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "HomeAndConstructionBusiness",
        "name": "{{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'ورشة أرتيزان للأعمال الخشبية الفاخرة') }}",
        "description": "{{ \App\Models\Setting::get('meta_desc_' . app()->getLocale(), 'تصنيع وتفصيل الأعمال الخشبية الفاخرة وغرف النوم والمكاتب وبوثات المعارض') }}",
        "url": "{{ url('/') }}",
        "telephone": "{{ \App\Models\Setting::get('phone', '+966500000000') }}",
        "email": "{{ \App\Models\Setting::get('email', 'info@artisanwood.sa') }}",
        "address": {
            "@@type": "PostalAddress",
            "streetAddress": "{{ \App\Models\Setting::get('address_' . app()->getLocale(), 'الرياض - المنطقة الصناعية') }}",
            "addressLocality": "Riyadh",
            "addressCountry": "SA"
        },
        "priceRange": "$$"
    }
    </script>

    <!-- Favicon -->
    @if($favicon = \App\Models\Setting::get('site_favicon'))
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @endif

    <!-- Google Fonts: Cairo (Arabic) & Outfit (English) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Global & Fast CDN for FontAwesome 6 Icons, Toastr, Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">

    @php
        $primaryColor = \App\Models\Setting::get('primary_color', '#b88b64');
        $secondaryColor = \App\Models\Setting::get('secondary_color', '#191512');
        $accentColor = \App\Models\Setting::get('accent_color', '#D4AF37');
        $bgDarkColor = \App\Models\Setting::get('bg_dark_color', '#0d0a08');
        $textLightColor = \App\Models\Setting::get('text_light_color', '#f8fafc');

        // Automatic Brightness / Day Mode Detection
        $cleanBg = ltrim($bgDarkColor, '#');
        if (strlen($cleanBg) === 3) {
            $cleanBg = $cleanBg[0].$cleanBg[0].$cleanBg[1].$cleanBg[1].$cleanBg[2].$cleanBg[2];
        }
        $r = hexdec(substr($cleanBg, 0, 2) ?: '00');
        $g = hexdec(substr($cleanBg, 2, 2) ?: '00');
        $b = hexdec(substr($cleanBg, 4, 2) ?: '00');
        $isDayMode = ((0.299 * $r + 0.587 * $g + 0.114 * $b) / 255) > 0.5;
    @endphp

    <!-- Tailwind CSS (Play CDN with dynamic custom wood & gold palette) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        wood: {
                            50: '#fcf9f5',
                            100: '#f7f1e9',
                            200: '#eee0d1',
                            300: '#e1caaF',
                            400: '#cfae8b',
                            500: '{{ $primaryColor }}',
                            600: '{{ $primaryColor }}',
                            700: '#754724',
                            800: '#5f3a1e',
                            900: '{{ $secondaryColor }}',
                            950: '{{ $bgDarkColor }}',
                        },
                        gold: {
                            300: '#F5E296',
                            400: '{{ $accentColor }}',
                            500: '{{ $accentColor }}',
                            600: '#B89324',
                            700: '#8C6C10',
                        },
                        dark: {
                            800: '{{ $secondaryColor }}',
                            900: '{{ $secondaryColor }}',
                            950: '{{ $bgDarkColor }}',
                        }
                    },
                    fontFamily: {
                        sans: "['Cairo', 'sans-serif']",
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --bg-dark-color: {{ $bgDarkColor }};
            --text-light-color: {{ $textLightColor }};
        }

        body {
            font-family: 'Cairo', sans-serif !important;
            background-color: var(--bg-dark-color) !important;
            color: var(--text-light-color) !important;
            overflow-x: hidden;
        }

        @if($isDayMode)
            /* ===================================================
               DAY / LIGHT MODE COMPREHENSIVE CONTRAST ENGINE
               =================================================== */
            body, main, section, .page-header {
                background-color: var(--bg-dark-color) !important;
                color: var(--text-light-color) !important;
            }

            /* Main Headings & Titles */
            h1, h2, h3, h4, h5, h6, .text-white, .font-black, .font-bold {
                color: var(--text-light-color) !important;
            }

            /* Subtitles & Descriptions */
            p, .text-slate-200, .text-slate-300, .text-slate-400, .text-stone-300, .text-stone-400 {
                color: #334155 !important;
            }
            .text-slate-500, .text-stone-500, .text-slate-400 {
                color: #64748b !important;
            }

            /* Glass Cards in Day Mode */
            .glass-card {
                background-color: #ffffff !important;
                border: 1px solid rgba(0, 0, 0, 0.08) !important;
                box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
                color: var(--text-light-color) !important;
            }
            .glass-card:hover {
                border-color: var(--accent-color) !important;
                box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1) !important;
            }

            /* Navbar Header in Day Mode */
            .glass-nav {
                background-color: rgba(255, 255, 255, 0.95) !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.03) !important;
            }
            .glass-nav .text-white {
                color: #0f172a !important;
            }
            .glass-nav .text-slate-300 {
                color: #334155 !important;
            }
            .glass-nav a:hover {
                color: var(--primary-color) !important;
            }

            /* Top Announcement Bar in Day Mode */
            .bg-dark-950.border-b {
                background-color: #eae5dc !important;
                border-color: rgba(0, 0, 0, 0.06) !important;
                color: #475569 !important;
            }
            .bg-dark-950.border-b a, .bg-dark-950.border-b span {
                color: #334155 !important;
            }

            /* Footer in Day Mode */
            footer.bg-dark-950 {
                background-color: #eae5dc !important;
                border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
                color: #475569 !important;
            }
            footer.bg-dark-950 h4, footer.bg-dark-950 .font-bold {
                color: #0f172a !important;
            }
            footer.bg-dark-950 p, footer.bg-dark-950 a, footer.bg-dark-950 span, footer.bg-dark-950 li {
                color: #475569 !important;
            }
            footer.bg-dark-950 a:hover {
                color: var(--primary-color) !important;
            }

            /* Offcanvas Mobile Drawer in Day Mode */
            #mobileDrawer .bg-dark-900 {
                background-color: #ffffff !important;
                color: #0f172a !important;
            }
            #mobileDrawer a, #mobileDrawer span {
                color: #334155 !important;
            }
            #mobileDrawer a:hover {
                background-color: #f1f5f9 !important;
            }

            /* Custom Order CTA Banner in Day Mode */
            #custom-order {
                background: linear-gradient(135deg, #f1ede6 0%, #ffffff 50%, #f1ede6 100%) !important;
                border-top: 1px solid rgba(0, 0, 0, 0.08) !important;
            }
            #custom-order h2 {
                color: #0f172a !important;
            }
            #custom-order p {
                color: #475569 !important;
            }

            /* Hero Section Overlay & Texts */
            #hero .hero-slide h1, #hero .hero-slide h2, #hero .hero-slide p {
                color: #ffffff !important;
            }
            #hero .hero-slide .glass-card {
                background-color: rgba(15, 23, 42, 0.75) !important;
                color: #ffffff !important;
            }

            /* Background Helpers */
            .bg-dark-950, .bg-slate-950 {
                background-color: var(--bg-dark-color) !important;
            }
            .bg-dark-900, .bg-slate-900 {
                background-color: var(--secondary-color) !important;
            }
            .border-white\/5, .border-white\/10, .border-white\/20 {
                border-color: rgba(0, 0, 0, 0.08) !important;
            }

            /* Form Inputs in Day Mode */
            input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="number"], textarea, select {
                background-color: #ffffff !important;
                color: #0f172a !important;
                border: 1px solid #cbd5e1 !important;
            }
            input:focus, textarea:focus, select:focus {
                border-color: var(--primary-color) !important;
                box-shadow: 0 0 0 3px rgba(184, 139, 100, 0.2) !important;
            }
            input::placeholder, textarea::placeholder {
                color: #94a3b8 !important;
            }

            /* Select2 in Day Mode */
            .select2-container--default .select2-selection--single {
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #0f172a !important;
            }
            .select2-dropdown {
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            }
            .select2-search--dropdown {
                background-color: #f8fafc !important;
            }
            .select2-search--dropdown .select2-search__field {
                background-color: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                color: #0f172a !important;
            }
            .select2-container--default .select2-results__option {
                color: #334155 !important;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected],
            .select2-container--default .select2-results__option--selected {
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
            }

        @else
            /* ===================================================
               DARK / NIGHT MODE COMPREHENSIVE CONTRAST ENGINE
               =================================================== */
            body, main, section, footer, header, #hero, #services, #about, #portfolio, #testimonials, #custom-order, .page-header {
                background-color: var(--bg-dark-color);
                color: var(--text-light-color);
            }

            .bg-dark-950, .bg-slate-950, .bg-stone-950 {
                background-color: var(--bg-dark-color) !important;
            }
            .bg-dark-900, .bg-slate-900, .bg-stone-900 {
                background-color: var(--secondary-color) !important;
            }

            h1, h2, h3, h4, h5, h6, .text-white, .font-black, .font-bold {
                color: var(--text-light-color);
            }
            p, .text-slate-200, .text-slate-300, .text-slate-400, .text-stone-300, .text-stone-400 {
                color: var(--text-light-color) !important;
                opacity: 0.85;
            }
            .text-slate-500, .text-stone-500 {
                color: var(--text-light-color) !important;
                opacity: 0.65;
            }

            .glass-nav {
                background-color: var(--secondary-color) !important;
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-bottom: 1px solid rgba(128, 128, 128, 0.18) !important;
            }
            .glass-card {
                background-color: var(--secondary-color) !important;
                backdrop-filter: blur(12px);
                border: 1px solid rgba(128, 128, 128, 0.18) !important;
                color: var(--text-light-color) !important;
            }
            .glass-card:hover {
                border-color: var(--accent-color) !important;
                box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.4);
            }
            .border-white\/10, .border-white\/5, .border-white\/20, .border-white\/15 {
                border-color: rgba(128, 128, 128, 0.18) !important;
            }

            input[type="text"], input[type="email"], input[type="tel"], input[type="password"], input[type="number"], textarea, select {
                background-color: var(--bg-dark-color) !important;
                color: var(--text-light-color) !important;
                border: 1px solid rgba(128, 128, 128, 0.25) !important;
            }
            input:focus, textarea:focus, select:focus {
                border-color: var(--accent-color) !important;
                outline: none !important;
            }
            ::placeholder {
                color: var(--text-light-color) !important;
                opacity: 0.5 !important;
            }

            .select2-container--default .select2-selection--single {
                background-color: var(--bg-dark-color) !important;
                border: 1px solid rgba(128, 128, 128, 0.25) !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: var(--text-light-color) !important;
            }
            .select2-dropdown {
                background-color: var(--secondary-color) !important;
                border: 1px solid var(--accent-color) !important;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5) !important;
            }
            .select2-search--dropdown {
                background-color: var(--secondary-color) !important;
            }
            .select2-search--dropdown .select2-search__field {
                background-color: var(--bg-dark-color) !important;
                border: 1px solid rgba(128, 128, 128, 0.3) !important;
                color: var(--text-light-color) !important;
            }
            .select2-container--default .select2-results__option {
                color: var(--text-light-color) !important;
            }
            .select2-container--default .select2-results__option--highlighted[aria-selected],
            .select2-container--default .select2-results__option--selected {
                background-color: var(--primary-color) !important;
                color: #ffffff !important;
            }
        @endif

        /* Shared Components */
        .text-gold-gradient {
            background: linear-gradient(135deg, var(--text-light-color) 0%, var(--accent-color) 50%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .bg-gold-gradient {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--primary-color) 100%) !important;
            color: #ffffff !important;
        }
        .bg-gold-gradient:hover {
            filter: brightness(1.12);
        }
        .gold-border-glow {
            border: 1px solid var(--accent-color) !important;
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.2);
        }

        .text-gold-400, .text-gold-500, .text-gold-300 {
            color: var(--accent-color) !important;
        }
        .text-wood-500, .text-wood-600, .text-wood-400, .text-wood-700 {
            color: var(--primary-color) !important;
        }
        .bg-gold-500, .bg-gold-400 {
            background-color: var(--accent-color) !important;
        }
        .bg-wood-600, .bg-wood-700, .bg-wood-800, .bg-wood-500 {
            background-color: var(--primary-color) !important;
        }
        .border-gold-500, .border-gold-400, .border-gold-500\/30, .border-gold-500\/40 {
            border-color: var(--accent-color) !important;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-dark-color);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-color);
        }

        /* Select2 Core Dimensioning */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            border-radius: 0.75rem !important;
            height: 48px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 12px !important;
            transition: all 0.2s ease-in-out !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 0.85rem !important;
            font-weight: 500 !important;
            line-height: 48px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
            {{ app()->getLocale() === 'ar' ? 'left: 12px !important; right: auto !important;' : 'right: 12px !important; left: auto !important;' }}
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: var(--accent-color) transparent transparent transparent !important;
            border-width: 6px 5px 0 5px !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent var(--accent-color) transparent !important;
            border-width: 0 5px 6px 5px !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            overflow: hidden !important;
            z-index: 9999 !important;
        }
        .select2-search--dropdown {
            padding: 10px !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 8px 12px !important;
            font-size: 0.8rem !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option {
            font-size: 0.85rem !important;
            padding: 10px 14px !important;
            transition: background 0.15s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased selection:bg-gold-500 selection:text-slate-950">

    <!-- Top Luxury Announcement & Quick Contact Bar -->
    <div class="bg-dark-950 border-b border-white/5 py-2 px-4 text-xs text-slate-400 hidden sm:block">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                @if($phone = \App\Models\Setting::get('phone'))
                    <a href="tel:{{ $phone }}" class="flex items-center gap-2 hover:text-gold-400 transition">
                        <i class="fa-solid fa-phone text-gold-500"></i>
                        <span dir="ltr">{{ $phone }}</span>
                    </a>
                @endif
                @if($email = \App\Models\Setting::get('email'))
                    <a href="mailto:{{ $email }}" class="flex items-center gap-2 hover:text-gold-400 transition">
                        <i class="fa-solid fa-envelope text-gold-500"></i>
                        <span>{{ $email }}</span>
                    </a>
                @endif
                @if($hours = \App\Models\Setting::get('working_hours_' . app()->getLocale()))
                    <span class="flex items-center gap-2 text-slate-400">
                        <i class="fa-regular fa-clock text-gold-500"></i>
                        <span>{{ $hours }}</span>
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <!-- Social Icons Top Bar (All Configured Channels from DB) -->
                <div class="flex items-center gap-3 text-slate-400">
                    @if($wa = (\App\Models\Setting::get('contact_whatsapp') ?? \App\Models\Setting::get('whatsapp') ?? \App\Models\Setting::get('contact_phone')))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank" class="hover:text-emerald-400 transition" title="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    @endif
                    @if($ig = (\App\Models\Setting::get('social_instagram') ?? \App\Models\Setting::get('instagram_url')))
                        <a href="{{ $ig }}" target="_blank" class="hover:text-pink-400 transition" title="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif
                    @if($x = (\App\Models\Setting::get('social_x') ?? \App\Models\Setting::get('twitter_url')))
                        <a href="{{ $x }}" target="_blank" class="hover:text-white transition" title="X (Twitter)">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                    @endif
                    @if($tiktok = (\App\Models\Setting::get('social_tiktok') ?? \App\Models\Setting::get('tiktok_url')))
                        <a href="{{ $tiktok }}" target="_blank" class="hover:text-cyan-400 transition" title="TikTok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    @endif
                    @if($snap = (\App\Models\Setting::get('social_snapchat') ?? \App\Models\Setting::get('snapchat_url')))
                        <a href="{{ $snap }}" target="_blank" class="hover:text-amber-300 transition" title="Snapchat">
                            <i class="fa-brands fa-snapchat"></i>
                        </a>
                    @endif
                    @if($linkedin = (\App\Models\Setting::get('social_linkedin') ?? \App\Models\Setting::get('linkedin_url')))
                        <a href="{{ $linkedin }}" target="_blank" class="hover:text-blue-400 transition" title="LinkedIn">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </a>
                    @endif
                    @if($yt = (\App\Models\Setting::get('social_youtube') ?? \App\Models\Setting::get('youtube_url')))
                        <a href="{{ $yt }}" target="_blank" class="hover:text-rose-400 transition" title="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif
                </div>

                <div class="h-3.5 w-px bg-white/10"></div>

                <!-- Luxury Language Switcher Top Bar -->
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-gold-500/30 bg-white/5 hover:bg-gold-500/15 text-slate-200 hover:text-gold-400 text-xs font-bold transition-all duration-300 shadow-xs group"
                    title="{{ app()->getLocale() === 'ar' ? 'Switch to English' : 'التحويل للغة العربية' }}">
                    <i class="fa-solid fa-globe text-gold-400 group-hover:rotate-45 transition-transform duration-300 text-[11px]"></i>
                    <span class="font-mono">{{ app()->getLocale() === 'ar' ? 'English' : 'عربي' }}</span>
                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-gold-500/20 text-gold-300 font-extrabold uppercase">{{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if($logo = \App\Models\Setting::get('site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="{{ \App\Models\Setting::get('site_name_' . app()->getLocale()) }}" class="h-12 w-auto object-contain">
                @else
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-wood-600 to-wood-800 flex items-center justify-center text-white text-xl shadow-lg shadow-wood-600/30 group-hover:scale-105 transition-transform border border-gold-500/40">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                @endif
                <div>
                    <span class="font-black text-lg sm:text-xl text-white tracking-wide block leading-tight">
                        {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان') }}
                    </span>
                    <span class="text-[11px] text-gold-400 font-medium tracking-wider block">
                        {{ \App\Models\Setting::get('site_slogan_' . app()->getLocale(), 'للأعمال الخشبية الفاخرة') }}
                    </span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold">
                <a href="{{ route('home') }}" class="transition hover:text-gold-400 {{ request()->routeIs('home') ? 'text-gold-400 font-bold' : 'text-slate-300' }}">
                    {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                </a>
                <a href="{{ route('services.index') }}" class="transition hover:text-gold-400 {{ request()->routeIs('services.*') ? 'text-gold-400 font-bold' : 'text-slate-300' }}">
                    {{ app()->getLocale() === 'ar' ? 'خدماتنا' : 'Services' }}
                </a>
                <a href="{{ route('portfolio.index') }}" class="transition hover:text-gold-400 {{ request()->routeIs('portfolio.*') ? 'text-gold-400 font-bold' : 'text-slate-300' }}">
                    {{ app()->getLocale() === 'ar' ? 'معرض الأعمال' : 'Portfolio' }}
                </a>
                <a href="{{ route('about') }}" class="transition hover:text-gold-400 {{ request()->routeIs('about') ? 'text-gold-400 font-bold' : 'text-slate-300' }}">
                    {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}
                </a>
                <a href="{{ route('contact') }}" class="transition hover:text-gold-400 {{ request()->routeIs('contact') ? 'text-gold-400 font-bold' : 'text-slate-300' }}">
                    {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                </a>

                <!-- Dynamic Navbar Custom Pages -->
                @php
                    $navPages = \App\Models\CustomPage::whereIn('placement', ['navbar', 'both'])->where('is_active', true)->orderBy('sort_order')->get();
                @endphp
                @foreach($navPages as $page)
                    <a href="{{ route('page.show', $page->slug) }}" class="transition hover:text-gold-400 text-slate-300">
                        {{ $page->title }}
                    </a>
                @endforeach
            </nav>

            <!-- Action Buttons -->
            <div class="hidden sm:flex items-center gap-3">
                <!-- Request Quote CTA Button directly to order.create -->
                <a href="{{ route('order.create') }}" class="px-5 py-2.5 rounded-xl bg-gold-gradient text-white hover:brightness-110 font-bold text-xs shadow-lg shadow-gold-500/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'طلب تفصيل مخصص' : 'Custom Quote' }}</span>
                </a>
            </div>

            <!-- Mobile Hamburger Button -->
            <button onclick="toggleMobileNav()" class="lg:hidden p-2 rounded-xl bg-white/5 text-slate-300 hover:text-white border border-white/10">
                <i class="fa-solid fa-bars text-xl"></i>
            </button>
        </div>
    </header>

    <!-- Mobile Offcanvas Menu Drawer -->
    <div id="mobileDrawer" class="fixed inset-0 z-50 bg-black/70 backdrop-blur-md hidden transition-opacity">
        <div class="fixed top-0 bottom-0 {{ app()->getLocale() === 'ar' ? 'right-0' : 'left-0' }} w-80 bg-dark-900 border-{{ app()->getLocale() === 'ar' ? 'l' : 'r' }} border-white/10 p-6 flex flex-col justify-between shadow-2xl">
            <div>
                <!-- Header in Drawer -->
                <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
                    <span class="font-bold text-white text-base">{{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان') }}</span>
                    <button onclick="toggleMobileNav()" class="p-2 text-slate-400 hover:text-white">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <!-- Nav Links -->
                <div class="space-y-3 font-semibold text-sm">
                    <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                        {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
                    </a>
                    <a href="{{ route('services.index') }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                        {{ app()->getLocale() === 'ar' ? 'خدماتنا' : 'Services' }}
                    </a>
                    <a href="{{ route('portfolio.index') }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                        {{ app()->getLocale() === 'ar' ? 'معرض الأعمال' : 'Portfolio' }}
                    </a>
                    <a href="{{ route('about') }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                        {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About Us' }}
                    </a>
                    <a href="{{ route('contact') }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                        {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                    </a>
                    @foreach($navPages as $page)
                        <a href="{{ route('page.show', $page->slug) }}" class="block py-2.5 px-4 rounded-xl hover:bg-white/5 text-slate-200">
                            {{ $page->title }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Drawer Footer -->
            <div class="space-y-3 pt-6 border-t border-white/10">
                <!-- Luxury Dual-State Language Switch Card -->
                <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" 
                    class="w-full py-3 px-4 rounded-2xl bg-white/5 border border-gold-500/30 flex items-center justify-between text-xs font-bold text-slate-200 hover:bg-gold-500/10 hover:text-gold-400 transition">
                    <span class="flex items-center gap-2">
                        <i class="fa-solid fa-globe text-gold-500 text-sm"></i>
                        <span>{{ app()->getLocale() === 'ar' ? 'Change Language / تغيير اللغة' : 'تغيير اللغة / Change Language' }}</span>
                    </span>
                    <span class="px-2 py-1 rounded-lg bg-gold-500/20 text-gold-300 font-extrabold uppercase text-[10px]">
                        {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                    </span>
                </a>

                <a href="{{ route('order.create') }}" onclick="toggleMobileNav()" class="w-full py-3 rounded-xl bg-gold-gradient text-white font-bold text-center block text-xs shadow-lg">
                    {{ app()->getLocale() === 'ar' ? 'طلب تفصيل مخصص' : 'Request Quote' }}
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <main>
        @yield('content')
    </main>

    <!-- Comprehensive Luxury Woodwork Footer -->
    <footer class="bg-dark-950 border-t border-white/10 pt-16 pb-8 text-slate-400 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/5">
            <!-- Col 1: About Platform -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-wood-600 to-wood-800 flex items-center justify-center text-white text-lg">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                    <span class="font-bold text-white text-lg">{{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان') }}</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    {{ \App\Models\Setting::get('footer_desc_' . app()->getLocale(), 'ورشة أعمال خشبية متخصصة في صناعة غرف النوم، المكاتب التنفيذية، وبوثات المعارض والديكورات والتكسيات بأعلى معايير الإتقان والحرفية.') }}
                </p>
                <!-- Social links (All Configured Channels from DB) -->
                <div class="flex items-center flex-wrap gap-2.5 pt-2">
                    @if($wa = (\App\Models\Setting::get('contact_whatsapp') ?? \App\Models\Setting::get('whatsapp') ?? \App\Models\Setting::get('contact_phone')))
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition border border-white/5" title="WhatsApp">
                            <i class="fa-brands fa-whatsapp text-xs"></i>
                        </a>
                    @endif
                    @if($ig = (\App\Models\Setting::get('social_instagram') ?? \App\Models\Setting::get('instagram_url')))
                        <a href="{{ $ig }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-pink-600 hover:text-white flex items-center justify-center transition border border-white/5" title="Instagram">
                            <i class="fa-brands fa-instagram text-xs"></i>
                        </a>
                    @endif
                    @if($x = (\App\Models\Setting::get('social_x') ?? \App\Models\Setting::get('twitter_url')))
                        <a href="{{ $x }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-slate-700 hover:text-white flex items-center justify-center transition border border-white/5" title="X (Twitter)">
                            <i class="fa-brands fa-x-twitter text-xs"></i>
                        </a>
                    @endif
                    @if($tiktok = (\App\Models\Setting::get('social_tiktok') ?? \App\Models\Setting::get('tiktok_url')))
                        <a href="{{ $tiktok }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-cyan-600 hover:text-white flex items-center justify-center transition border border-white/5" title="TikTok">
                            <i class="fa-brands fa-tiktok text-xs"></i>
                        </a>
                    @endif
                    @if($snap = (\App\Models\Setting::get('social_snapchat') ?? \App\Models\Setting::get('snapchat_url')))
                        <a href="{{ $snap }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-amber-500 hover:text-slate-950 flex items-center justify-center transition border border-white/5" title="Snapchat">
                            <i class="fa-brands fa-snapchat text-xs"></i>
                        </a>
                    @endif
                    @if($linkedin = (\App\Models\Setting::get('social_linkedin') ?? \App\Models\Setting::get('linkedin_url')))
                        <a href="{{ $linkedin }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-blue-600 hover:text-white flex items-center justify-center transition border border-white/5" title="LinkedIn">
                            <i class="fa-brands fa-linkedin-in text-xs"></i>
                        </a>
                    @endif
                    @if($yt = (\App\Models\Setting::get('social_youtube') ?? \App\Models\Setting::get('youtube_url')))
                        <a href="{{ $yt }}" target="_blank" class="w-8 h-8 rounded-xl bg-white/5 hover:bg-rose-600 hover:text-white flex items-center justify-center transition border border-white/5" title="YouTube">
                            <i class="fa-brands fa-youtube text-xs"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Col 2: Services Quick Links -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-couch text-gold-500 text-xs"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'خدمات الورشة' : 'Our Services' }}</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    @php $footerServices = \App\Models\Service::where('is_active', true)->orderBy('sort_order')->take(5)->get(); @endphp
                    @foreach($footerServices as $fs)
                        <li>
                            <a href="{{ route('services.show', $fs->slug) }}" class="hover:text-gold-400 transition flex items-center gap-2">
                                <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-wood-500"></i>
                                <span>{{ $fs->title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Col 3: Company & Policies Links -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-link text-gold-500 text-xs"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'روابط مهمة' : 'Quick Links' }}</span>
                </h4>
                <ul class="space-y-2 text-xs">
                    <li>
                        <a href="{{ route('about') }}" class="hover:text-gold-400 transition flex items-center gap-2">
                            <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-wood-500"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'من نحن وتاريخنا' : 'About Us' }}</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('portfolio.index') }}" class="hover:text-gold-400 transition flex items-center gap-2">
                            <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-wood-500"></i>
                            <span>{{ app()->getLocale() === 'ar' ? 'معرض المشاريع المنفذة' : 'Portfolio Gallery' }}</span>
                        </a>
                    </li>
                    @php
                        $footerPages = \App\Models\CustomPage::whereIn('placement', ['footer', 'both'])->where('is_active', true)->orderBy('sort_order')->get();
                    @endphp
                    @foreach($footerPages as $fp)
                        <li>
                            <a href="{{ route('page.show', $fp->slug) }}" class="hover:text-gold-400 transition flex items-center gap-2">
                                <i class="fa-solid fa-angle-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-[10px] text-wood-500"></i>
                                <span>{{ $fp->title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Col 4: Contact & Location -->
            <div class="space-y-4">
                <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-gold-500 text-xs"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}</span>
                </h4>
                <div class="space-y-2.5 text-xs text-slate-400">
                    @if($addr = \App\Models\Setting::get('address_' . app()->getLocale()))
                        <p class="flex items-start gap-2">
                            <i class="fa-solid fa-map-pin text-wood-500 mt-1"></i>
                            <span>{{ $addr }}</span>
                        </p>
                    @endif
                    @if($phone = \App\Models\Setting::get('phone'))
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-phone text-wood-500"></i>
                            <a href="tel:{{ $phone }}" dir="ltr" class="hover:text-white">{{ $phone }}</a>
                        </p>
                    @endif
                    @if($email = \App\Models\Setting::get('email'))
                        <p class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-wood-500"></i>
                            <a href="mailto:{{ $email }}" class="hover:text-white">{{ $email }}</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved.' }}</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.login') }}" class="text-slate-600 hover:text-slate-400 transition flex items-center gap-1">
                    <i class="fa-solid fa-lock text-[10px]"></i>
                    <span>{{ app()->getLocale() === 'ar' ? 'بوابة الإدارة' : 'Admin Portal' }}</span>
                </a>
            </div>
        </div>
    </footer>

    <!-- AI Joinery Assistant Floating Widget (Rendered only when enabled in Settings) -->
    @if((string)\App\Models\Setting::get('ai_enabled', '1') === '1')
        @include('frontend.partials.ai_chat_widget')
    @endif

    <!-- Direct WhatsApp Floating Action Widget -->
    @include('frontend.partials.whatsapp_widget')

    <!-- Core Scripts: jQuery & Select2 (Global Fast CDN with fallback) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('vendor/jquery/jquery.min.js') }}"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>window.jQuery && window.jQuery.fn.select2 || document.write('<script src="{{ asset('vendor/select2/select2.min.js') }}"><\/script>')</script>
    <script>
        function toggleMobileNav() {
            const drawer = document.getElementById('mobileDrawer');
            if (drawer) {
                drawer.classList.toggle('hidden');
            }
        }

        // Global Select2 Initialization on all Select fields
        $(document).ready(function() {
            if ($.fn.select2) {
                $('select').select2({
                    dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}",
                    width: '100%'
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
