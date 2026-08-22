<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.login_title') }} - {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
                            500: '#D4AF37',
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
<body class="min-h-screen bg-slate-950 flex items-center justify-center p-4 relative overflow-hidden selection:bg-wood-500 selection:text-white">

    <!-- Subtle Background Glows -->
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-wood-700/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gold-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Language Switcher on Top -->
        <div class="flex justify-end mb-4">
            @if(app()->getLocale() === 'ar')
                <a href="{{ route('locale.switch', 'en') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:border-wood-500 transition backdrop-blur-md">
                    <span>🇺🇸</span>
                    <span>English</span>
                </a>
            @else
                <a href="{{ route('locale.switch', 'ar') }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/80 border border-slate-800 text-xs font-semibold text-slate-300 hover:text-white hover:border-wood-500 transition backdrop-blur-md">
                    <span>🇸🇦</span>
                    <span>العربية</span>
                </a>
            @endif
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 border border-slate-800/80 backdrop-blur-xl rounded-3xl p-8 sm:p-10 shadow-2xl shadow-black/80">
            <!-- Header & Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-wood-500 to-wood-700 text-white text-2xl shadow-xl shadow-wood-600/30 mb-4 ring-4 ring-wood-500/20">
                    <i class="fa-solid fa-tree"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-white tracking-tight">
                    {{ __('admin.login_title') }}
                </h2>
                <p class="text-xs text-wood-400 font-medium mt-1">
                    {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية والديكور') }}
                </p>
            </div>

            <!-- Error Banner -->
            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-base shrink-0"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="email">
                        {{ __('admin.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@artisanwood.sa') }}" required autofocus
                            class="w-full bg-slate-950/60 border border-slate-700/70 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                            placeholder="admin@example.com">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-2" for="password">
                        {{ __('admin.password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" value="admin123456" required
                            class="w-full bg-slate-950/60 border border-slate-700/70 rounded-xl {{ app()->getLocale() === 'ar' ? 'pr-10 pl-4' : 'pl-10 pr-4' }} py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-700 text-wood-600 focus:ring-wood-500 focus:ring-offset-slate-900">
                        <span class="text-xs text-slate-400 select-none">{{ __('admin.remember_me') }}</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-wood-600 to-wood-700 hover:from-wood-500 hover:to-wood-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-wood-600/30 hover:shadow-wood-600/50 transition-all duration-200 transform active:scale-[0.99] flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>{{ __('admin.login_button') }}</span>
                </button>
            </form>

            <!-- Test Accounts Helper Box -->
            <div class="mt-8 pt-6 border-t border-slate-800/80 text-xs text-slate-400 space-y-2">
                <div class="font-bold text-slate-300 text-center mb-1">بيانات الحسابات التجريبية (Demo Accounts):</div>
                <div class="bg-slate-950/50 p-2.5 rounded-xl border border-slate-800/60 space-y-1 font-mono text-[11px]">
                    <div class="flex justify-between"><span>👑 Super Admin:</span><span class="text-wood-400">admin@artisanwood.sa</span></div>
                    <div class="flex justify-between"><span>✍️ Content Manager:</span><span class="text-wood-400">editor@artisanwood.sa</span></div>
                    <div class="flex justify-between"><span>🎧 Support:</span><span class="text-wood-400">support@artisanwood.sa</span></div>
                    <div class="text-center text-slate-500 mt-1">Pass: <strong class="text-slate-300">admin123456</strong> / <strong class="text-slate-300">editor123456</strong></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
