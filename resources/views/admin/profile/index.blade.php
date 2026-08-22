@extends('admin.layouts.master')

@section('title', __('admin.profile'))

@section('page_icon')
    <i class="fa-solid fa-user-gear text-wood-600"></i>
@endsection

@section('page_title', __('admin.profile'))
@section('page_subtitle', 'تعديل بيانات الحساب الشخصي وتغيير كلمة المرور والصورة الرمزية')

@section('content')
<div class="max-w-4xl mx-auto">
    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Profile Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <!-- Header Avatar Section -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-100">
                <div class="relative group">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-2xl object-cover ring-4 ring-wood-500/20 shadow-md">
                    <label for="avatar_input" class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition cursor-pointer font-bold">
                        <i class="fa-solid fa-camera mr-1"></i> تغيير
                    </label>
                    <input type="file" id="avatar_input" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                </div>
                <div class="space-y-1 text-center sm:text-start">
                    <h2 class="text-lg font-bold text-slate-800">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400 font-mono">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center gap-1.5 justify-center sm:justify-start pt-1">
                        @foreach($user->roles as $role)
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-wood-100 text-wood-800 rounded-md">
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Profile Info Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="name">
                        {{ __('admin.name') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="email">
                        {{ __('admin.email') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                </div>

                <!-- Phone -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="phone">
                        {{ __('admin.phone') }}
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="pt-6 border-t border-slate-100 space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-wood-600"></i>
                    <span>تغيير كلمة المرور (اختياري)</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="current_password">
                            {{ __('admin.current_password') }}
                        </label>
                        <input type="password" id="current_password" name="current_password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="new_password">
                            {{ __('admin.new_password') }}
                        </label>
                        <input type="password" id="new_password" name="new_password"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                            placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="new_password_confirmation">
                            {{ __('admin.password_confirmation') }}
                        </label>
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
                <i class="fa-solid fa-floppy-disk ml-1"></i>
                <span>{{ __('admin.save') }}</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $(input).siblings('img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
