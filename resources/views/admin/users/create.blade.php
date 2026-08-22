@extends('admin.layouts.master')

@section('title', __('admin.create_user'))

@section('page_icon')
    <i class="fa-solid fa-user-plus text-wood-600"></i>
@endsection

@section('page_title', __('admin.create_user'))
@section('page_subtitle', 'إضافة حساب مشرف إداري جديد وتعيين الأدوار المناسبة له')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>{{ __('admin.users_list') }}</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6">
    @csrf

    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-user text-wood-600"></i>
            <span>بيانات المشرف الإداري</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Full Name -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="name">
                    {{ __('admin.name') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="الاسم الكامل">
            </div>

            <!-- Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="email">
                    {{ __('admin.email') }} <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="user@example.com">
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="phone">
                    {{ __('admin.phone') }}
                </label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="+966 50 000 0000">
            </div>

            <!-- Roles Selector (Multi-Select2) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="roles">
                    {{ __('admin.assigned_roles') }} <span class="text-rose-500">*</span>
                </label>
                <select name="roles[]" id="roles" multiple required class="select2 w-full">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (is_array(old('roles')) && in_array($role->id, old('roles'))) ? 'selected' : '' }}>
                            {{ $role->display_name }} ({{ $role->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="password">
                    {{ __('admin.password') }} <span class="text-rose-500">*</span>
                </label>
                <input type="password" id="password" name="password" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="••••••••">
            </div>

            <!-- Password Confirmation -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="password_confirmation">
                    {{ __('admin.password_confirmation') }} <span class="text-rose-500">*</span>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="••••••••">
            </div>
        </div>

        <!-- Active Status -->
        <div class="pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded text-wood-600 focus:ring-wood-500 border-slate-300">
                <span class="text-xs font-semibold text-slate-700">{{ __('admin.active') }} (تفعيل الحساب والسماح بتسجيل الدخول)</span>
            </label>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection
