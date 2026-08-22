@extends('admin.layouts.master')

@section('title', __('admin.create_role'))

@section('page_icon')
    <i class="fa-solid fa-plus text-wood-600"></i>
@endsection

@section('page_title', __('admin.create_role'))
@section('page_subtitle', 'إنشاء دور جديد وتحديد مصفوفة الصلاحيات المخصصة له')

@section('page_actions')
    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-bold rounded-xl transition">
        <i class="fa-solid fa-arrow-right"></i>
        <span>{{ __('admin.roles_list') }}</span>
    </a>
@endsection

@section('content')
<form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-6">
    @csrf

    <!-- Basic Info Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-5">
        <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
            <i class="fa-solid fa-id-card-clip text-wood-600"></i>
            <span>البيانات الأساسية للدور</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name AR -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="name_ar">
                    {{ __('admin.role_name_ar') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="مثال: مشرف المعرض والخدمات">
            </div>

            <!-- Name EN -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="name_en">
                    {{ __('admin.role_name_en') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="e.g. Portfolio & Services Manager">
            </div>

            <!-- Description AR -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="description_ar">
                    {{ __('admin.role_desc_ar') }}
                </label>
                <textarea id="description_ar" name="description_ar" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="وصف مختصر للمهام والمسؤوليات">{{ old('description_ar') }}</textarea>
            </div>

            <!-- Description EN -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5" for="description_en">
                    {{ __('admin.role_desc_en') }}
                </label>
                <textarea id="description_en" name="description_en" rows="2"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 focus:ring-2 focus:ring-wood-500/20 transition"
                    placeholder="Short description of duties">{{ old('description_en') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Permissions Matrix Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-key text-wood-600"></i>
                    <span>{{ __('admin.permissions_matrix') }}</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">حدد الصلاحيات الممنوحة لهذا الدور بدقة</p>
            </div>
            <button type="button" id="toggleAllGlobal" class="px-3.5 py-1.5 rounded-lg border border-slate-300 text-xs font-bold text-slate-700 hover:bg-slate-100 transition">
                <i class="fa-solid fa-check-double ml-1"></i>
                <span>{{ __('admin.select_all') }}</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($permissionGroups as $groupName => $permissions)
                <div class="bg-slate-50/70 border border-slate-200/80 rounded-2xl p-5 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                        <span class="font-bold text-xs uppercase tracking-wider text-wood-800 flex items-center gap-2">
                            <i class="fa-solid fa-folder-closed text-wood-600"></i>
                            <span>{{ $groupName }}</span>
                        </span>
                        <label class="text-[11px] font-semibold text-slate-500 cursor-pointer flex items-center gap-1">
                            <input type="checkbox" class="group-select-all w-3.5 h-3.5 rounded text-wood-600 focus:ring-wood-500">
                            <span>{{ __('admin.all') }}</span>
                        </label>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($permissions as $permission)
                            <label class="flex items-start gap-2.5 cursor-pointer p-1.5 rounded-lg hover:bg-white transition select-none">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                    class="perm-checkbox mt-0.5 w-4 h-4 rounded border-slate-300 text-wood-600 focus:ring-wood-500"
                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <div>
                                    <div class="text-xs font-semibold text-slate-800">
                                        {{ app()->getLocale() === 'ar' ? $permission->name_ar : $permission->name_en }}
                                    </div>
                                    <div class="text-[10px] font-mono text-slate-400">
                                        {{ $permission->name }}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('admin.roles.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
            {{ __('admin.cancel') }}
        </a>
        <button type="submit" class="px-8 py-2.5 rounded-xl bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold shadow-lg shadow-wood-600/30 transition">
            <i class="fa-solid fa-floppy-disk ml-1"></i>
            <span>{{ __('admin.save') }}</span>
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    // Group select all
    $('.group-select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $(this).closest('.bg-slate-50\\/70').find('.perm-checkbox').prop('checked', isChecked);
    });

    // Global toggle all
    $('#toggleAllGlobal').on('click', function() {
        const allCheckboxes = $('.perm-checkbox');
        const anyUnchecked = allCheckboxes.filter(':not(:checked)').length > 0;
        allCheckboxes.prop('checked', anyUnchecked);
        $('.group-select-all').prop('checked', anyUnchecked);
    });
</script>
@endpush
