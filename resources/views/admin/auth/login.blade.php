<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>{{ __('admin.login_title') }} - {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), (app()->getLocale() === 'ar' ? 'أرتيزان للأعمال الخشبية' : 'Artisan Woodcraft')) }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wood: {
                            50: '#fbf8f5',
                            100: '#f5efe8',
                            200: '#ebdfd2',
                            300: '#dec7b0',
                            400: '#cba888',
                            500: '#b88b64',
                            600: '#9d7049',
                            700: '#815638',
                            800: '#69452e',
                            900: '#553926',
                            950: '#2d1c12',
                        },
                        gold: {
                            400: '#E5C158',
                            500: '#D4AF37',
                            600: '#B89325',
                        }
                    },
                    fontFamily: {
                        sans: {{ app()->getLocale() === 'ar' ? "['Cairo', 'sans-serif']" : "['Outfit', 'sans-serif']" }},
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen bg-slate-950 flex flex-col justify-center items-center p-4 sm:p-6 lg:p-8 relative overflow-x-hidden selection:bg-gold-500 selection:text-slate-950 font-sans">

    <!-- Ambient Glowing Backdrops -->
    <div class="fixed -top-32 -right-32 w-72 sm:w-96 h-72 sm:h-96 bg-wood-700/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="fixed -bottom-32 -left-32 w-72 sm:w-96 h-72 sm:h-96 bg-gold-500/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-[420px] mx-auto relative z-10 my-auto py-4">
        
        <!-- Top Navigation Bar (Back to Home & Language Switcher) -->
        <div class="flex items-center justify-between mb-4 px-1 gap-2">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-[11px] font-bold text-slate-400 hover:text-gold-400 hover:border-gold-500/30 transition backdrop-blur-md">
                <i class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} text-[10px]"></i>
                <span>{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</span>
            </a>

            @if(app()->getLocale() === 'ar')
                <a href="{{ route('locale.switch', 'en') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-[11px] font-bold text-slate-300 hover:text-gold-400 hover:border-gold-500/30 transition backdrop-blur-md">
                    <span>🇺🇸</span>
                    <span class="font-mono">English</span>
                </a>
            @else
                <a href="{{ route('locale.switch', 'ar') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-[11px] font-bold text-slate-300 hover:text-gold-400 hover:border-gold-500/30 transition backdrop-blur-md">
                    <span>🇸🇦</span>
                    <span>العربية</span>
                </a>
            @endif
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 border border-slate-800/80 backdrop-blur-xl rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/80">
            
            <!-- Header & Logo -->
            @php
                $siteLogo = \App\Models\Setting::get('site_logo');
                $siteName = \App\Models\Setting::get('site_name_' . app()->getLocale(), (app()->getLocale() === 'ar' ? 'أرتيزان للأعمال الخشبية والديكور' : 'Artisan Woodcraft'));
                $siteTagline = \App\Models\Setting::get('site_tagline_' . app()->getLocale()) ?: \App\Models\Setting::get('site_slogan_' . app()->getLocale(), (app()->getLocale() === 'ar' ? 'للأعمال الخشبية الفاخرة' : 'Luxury Bespoke Woodcraft'));
            @endphp
            <div class="text-center mb-6 sm:mb-8">
                @if($siteLogo)
                    <div class="mb-3.5 flex justify-center">
                        <img src="{{ storage_asset($siteLogo) }}" alt="{{ $siteName }}" class="h-12 sm:h-14 max-w-[200px] w-auto object-contain drop-shadow-md">
                    </div>
                @else
                    <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-br from-wood-600 to-wood-800 text-white text-xl sm:text-2xl shadow-xl shadow-wood-600/30 mb-3 sm:mb-4 ring-2 ring-gold-500/30">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                @endif

                <h2 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                    {{ __('admin.login_title') }}
                </h2>
                <p class="text-[11px] sm:text-xs text-gold-400 font-semibold mt-1">
                    {{ $siteName }}
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    {{ $siteTagline }}
                </p>
            </div>

            <!-- Error Banners -->
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-start gap-2.5 leading-relaxed">
                    <i class="fa-solid fa-circle-exclamation text-sm shrink-0 mt-0.5"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-start gap-2.5 leading-relaxed">
                    <i class="fa-solid fa-circle-exclamation text-sm shrink-0 mt-0.5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-start gap-2.5 leading-relaxed">
                    <i class="fa-solid fa-circle-check text-sm shrink-0 mt-0.5"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <!-- Email Input -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5" for="email">
                        {{ __('admin.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-slate-500 text-sm">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                            class="w-full bg-slate-950/70 border border-slate-700/80 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-3.5' : 'pl-10 pr-3.5' }} py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="name@domain.com">
                    </div>
                </div>

                <!-- Password Input with Toggle Visibility -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-300" for="password">
                            {{ __('admin.password') }}
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-slate-500 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="w-full bg-slate-950/70 border border-slate-700/80 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-10' : 'pl-10 pr-10' }} py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-gold-500 focus:ring-2 focus:ring-gold-500/20 transition"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center text-slate-500 hover:text-gold-400 transition" title="Show / Hide Password">
                            <i id="passwordToggleIcon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-gold-600 focus:ring-gold-500 focus:ring-offset-slate-900 cursor-pointer">
                        <span class="text-xs text-slate-400 select-none">{{ __('admin.remember_me') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-wood-600 via-wood-700 to-wood-800 hover:from-wood-500 hover:to-wood-700 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-lg shadow-wood-600/30 hover:shadow-wood-600/50 transition-all duration-200 transform active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>{{ __('admin.login_button') }}</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-[11px] text-slate-500 mt-6">
            © {{ date('Y') }} {{ $siteName }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved.' }}
        </p>
    </div>

    <!-- Password Show/Hide JavaScript -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('passwordToggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
