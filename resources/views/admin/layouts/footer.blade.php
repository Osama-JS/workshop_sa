<footer class="bg-white border-t border-slate-200 py-3 px-6 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
    <div>
        &copy; {{ date('Y') }} <strong>{{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}</strong>. {{ __('admin.login_subtitle') }}.
    </div>
    <div class="flex items-center gap-4 text-slate-400">
        <span>{{ config('app.name') }} v1.0</span>
        <span>•</span>
        <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
    </div>
</footer>
