@php
    $currentRoute = Route::currentRouteName();
    $user = auth()->user();
@endphp

<aside id="sidebar" class="fixed md:static inset-y-0 {{ app()->getLocale() === 'ar' ? 'right-0 translate-x-full md:translate-x-0' : 'left-0 -translate-x-full md:translate-x-0' }} z-50 w-72 bg-dark-900 text-slate-300 flex flex-col transition-transform duration-300 ease-in-out shadow-2xl border-{{ app()->getLocale() === 'ar' ? 'l' : 'r' }} border-dark-800">
    <!-- Brand Header -->
    <div class="h-20 flex items-center justify-between px-6 bg-dark-950 border-b border-dark-800/80">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-wood-500 to-wood-700 flex items-center justify-center text-white shadow-lg shadow-wood-600/30 group-hover:scale-105 transition-transform">
                <i class="fa-solid fa-tree text-lg"></i>
            </div>
            <div>
                <span class="font-bold text-base text-white tracking-wide block leading-tight">
                    {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان') }}
                </span>
                <span class="text-xs text-wood-400 font-medium tracking-wider uppercase">
                    {{ __('admin.dashboard') }}
                </span>
            </div>
        </a>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5 custom-scrollbar">
        <!-- Main Section -->
        <div class="px-3 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            {{ __('admin.menu_main') }}
        </div>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ $currentRoute === 'admin.dashboard' ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center text-lg {{ $currentRoute === 'admin.dashboard' ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.dashboard') }}</span>
        </a>

        <!-- Custom Orders -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('orders.view')))
        <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.orders') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <div class="flex items-center gap-3.5">
                <i class="fa-solid fa-file-signature w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.orders') ? 'text-white' : 'text-wood-400' }}"></i>
                <span>{{ __('admin.menu_orders') }}</span>
            </div>
            @php $pendingCount = \App\Models\CustomOrder::where('status', 'pending')->count(); @endphp
            <span id="sidebarPendingOrdersBadge" class="px-2 py-0.5 text-xs font-bold bg-gold-500 text-slate-950 rounded-full {{ $pendingCount > 0 ? '' : 'hidden' }}">{{ $pendingCount }}</span>
        </a>
        @endif

        <!-- Contact Messages -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('messages.view')))
        <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.messages') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <div class="flex items-center gap-3.5">
                <i class="fa-solid fa-envelope-open-text w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.messages') ? 'text-white' : 'text-wood-400' }}"></i>
                <span>{{ __('admin.menu_messages') }}</span>
            </div>
            @php $unreadCount = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
            <span id="sidebarUnreadMessagesBadge" class="px-2 py-0.5 text-xs font-bold bg-rose-500 text-white rounded-full {{ $unreadCount > 0 ? '' : 'hidden' }}">{{ $unreadCount }}</span>
        </a>
        @endif

        <!-- Hero Slides & Banner -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('hero.view')))
        <a href="{{ route('admin.hero-slides.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.hero-slides') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-panorama w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.hero-slides') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_hero_slides') }}</span>
        </a>
        @endif

        <!-- Services -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('services.view')))
        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.services') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-couch w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.services') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_services') }}</span>
        </a>
        @endif

        <!-- Portfolio -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('portfolios.view')))
        <a href="{{ route('admin.portfolios.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.portfolios') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-images w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.portfolios') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_portfolio') }}</span>
        </a>
        @endif

        <!-- Custom Pages -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('pages.view')))
        <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.pages') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-file-lines w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.pages') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_pages') }}</span>
        </a>
        @endif

        <!-- About Us & Testimonials -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('about.edit')))
        <a href="{{ route('admin.about.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.about') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-address-card w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.about') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_about') }}</span>
        </a>
        @endif

        @if($user && ($user->isSuperAdmin() || $user->hasPermission('testimonials.manage')))
        <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.testimonials') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-quote-left w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.testimonials') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_testimonials') }}</span>
        </a>
        @endif

        <!-- Section: AI Assistant & Knowledge Base -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('orders.view') || $user->hasPermission('settings.view')))
        <div class="pt-4 px-3 pb-2 text-xs font-semibold text-gold-400 uppercase tracking-wider flex items-center gap-1.5">
            <i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i>
            <span>{{ __('admin.menu_ai_hub') }}</span>
        </div>

        <a href="{{ route('admin.ai-ideas.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ai-ideas') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-lightbulb w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.ai-ideas') ? 'text-white' : 'text-gold-400' }}"></i>
            <span>{{ __('admin.menu_ai_ideas') }}</span>
        </a>

        <a href="{{ route('admin.ai-logs.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.ai-logs') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-comments w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.ai-logs') ? 'text-white' : 'text-gold-400' }}"></i>
            <span>{{ __('admin.menu_ai_logs') }}</span>
        </a>
        @endif

        <!-- Section: Administration & RBAC -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('users.manage') || $user->hasPermission('roles.manage')))
        <div class="pt-4 px-3 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            {{ __('admin.menu_users') }}
        </div>

        @if($user->isSuperAdmin() || $user->hasPermission('users.manage'))
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.users') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-users-gear w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.users') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.users') }}</span>
        </a>
        @endif

        @if($user->isSuperAdmin() || $user->hasPermission('roles.manage'))
        <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.roles') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-shield-halved w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.roles') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.roles') }}</span>
        </a>
        @endif
        @endif

        <!-- Settings -->
        @if($user && ($user->isSuperAdmin() || $user->hasPermission('settings.view')))
        <div class="pt-4 px-3 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
            {{ __('admin.settings') }}
        </div>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl font-medium transition-all duration-200 {{ str_starts_with($currentRoute, 'admin.settings') ? 'bg-wood-600 text-white shadow-lg shadow-wood-700/40 font-semibold' : 'text-slate-300 hover:bg-dark-800 hover:text-white' }}">
            <i class="fa-solid fa-sliders w-5 text-center text-lg {{ str_starts_with($currentRoute, 'admin.settings') ? 'text-white' : 'text-wood-400' }}"></i>
            <span>{{ __('admin.menu_settings') }}</span>
        </a>
        @endif
    </div>

    <!-- Live Site Button at Footer of Sidebar -->
    <div class="p-4 bg-dark-950 border-t border-dark-800/80">
        <a href="{{ url('/') }}" target="_blank" class="flex items-center justify-center gap-2.5 w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-wood-700 to-wood-800 text-white text-sm font-medium hover:from-wood-600 hover:to-wood-700 shadow-md transition-all">
            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            <span>{{ __('admin.menu_view_site') }}</span>
        </a>
    </div>
</aside>
