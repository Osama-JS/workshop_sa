@php
    $user = auth()->user();
@endphp

<header class="h-20 bg-white border-b border-slate-200/80 px-4 md:px-8 flex items-center justify-between shadow-xs sticky top-0 z-30">
    <!-- Left: Mobile Toggle & Quick Search/Title -->
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
        <div class="hidden sm:block">
            <span class="text-xs text-slate-400 font-medium block">{{ date('l, d F Y') }}</span>
            <span class="text-sm font-semibold text-slate-700">{{ __('admin.welcome') }}، {{ $user->name ?? 'Admin' }} 👋</span>
        </div>
    </div>

    <!-- Right: Language Switcher, Notifications & User Dropdown -->
    <div class="flex items-center gap-3 md:gap-5">
        <!-- Language Switcher Button -->
        @if(app()->getLocale() === 'ar')
            <a href="{{ route('locale.switch', 'en') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-wood-600 transition shadow-2xs" title="Switch to English">
                <span class="text-base">🇺🇸</span>
                <span>English</span>
            </a>
        @else
            <a href="{{ route('locale.switch', 'ar') }}" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 hover:text-wood-600 transition shadow-2xs" title="التحويل إلى العربية">
                <span class="text-base">🇸🇦</span>
                <span>العربية</span>
            </a>
        @endif

        <!-- Quick Notifications Pill -->
        @php
            $pendingOrders = \App\Models\CustomOrder::where('status', 'pending')->count();
            $unreadMessages = \App\Models\ContactMessage::where('is_read', false)->count();
            $totalAlerts = $pendingOrders + $unreadMessages;
        @endphp

        <div class="relative">
            <button id="notifButton" onclick="document.getElementById('notifDropdown').classList.toggle('hidden')" class="relative p-2 rounded-xl text-slate-500 hover:text-wood-600 hover:bg-slate-100 transition">
                <i class="fa-regular fa-bell text-lg"></i>
                @if($totalAlerts > 0)
                    <span class="absolute top-1 {{ app()->getLocale() === 'ar' ? 'left-1' : 'right-1' }} w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white"></span>
                @endif
            </button>

            <!-- Notifications Dropdown -->
            <div id="notifDropdown" class="hidden absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 z-50 animate-in fade-in slide-in-from-top-2">
                <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 uppercase tracking-wider">{{ __('admin.menu_messages') }} & {{ __('admin.menu_orders') }}</span>
                    @if($totalAlerts > 0)
                        <span class="px-2 py-0.5 text-xs font-bold bg-rose-100 text-rose-700 rounded-full">{{ $totalAlerts }}</span>
                    @endif
                </div>
                <div class="py-2 divide-y divide-slate-50">
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-4 py-2.5 hover:bg-slate-50 text-xs transition">
                        <span class="flex items-center gap-2 text-slate-700">
                            <i class="fa-solid fa-file-signature text-wood-500"></i>
                            <span>{{ __('admin.menu_orders') }} ({{ __('admin.active') }})</span>
                        </span>
                        <span class="font-bold text-wood-700">{{ $pendingOrders }}</span>
                    </a>
                    <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between px-4 py-2.5 hover:bg-slate-50 text-xs transition">
                        <span class="flex items-center gap-2 text-slate-700">
                            <i class="fa-solid fa-envelope-open text-amber-500"></i>
                            <span>{{ __('admin.menu_messages') }} ({{ __('admin.inactive') }})</span>
                        </span>
                        <span class="font-bold text-amber-700">{{ $unreadMessages }}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Vertical Divider -->
        <div class="h-8 w-px bg-slate-200"></div>

        <!-- User Profile Dropdown Button -->
        <div class="relative">
            <button onclick="document.getElementById('userDropdown').classList.toggle('hidden')" class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-100 transition text-start">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-wood-500/20 shadow-xs">
                <div class="hidden lg:block">
                    <div class="text-sm font-bold text-slate-800 leading-tight">{{ $user->name }}</div>
                    <div class="text-xs text-wood-600 font-medium">
                        {{ $user->roles->first()?->display_name ?? 'Admin' }}
                    </div>
                </div>
                <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="userDropdown" class="hidden absolute {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-in fade-in slide-in-from-top-2">
                <div class="px-4 py-2 border-b border-slate-100 lg:hidden">
                    <div class="text-sm font-bold text-slate-800">{{ $user->name }}</div>
                    <div class="text-xs text-wood-600">{{ $user->email }}</div>
                </div>

                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 hover:bg-wood-50 hover:text-wood-700 transition">
                    <i class="fa-solid fa-user-pen text-slate-400 w-4"></i>
                    <span>{{ __('admin.profile') }}</span>
                </a>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition text-start">
                        <i class="fa-solid fa-right-from-bracket text-rose-500 w-4"></i>
                        <span>{{ __('admin.logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
