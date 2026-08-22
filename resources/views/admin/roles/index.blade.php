@extends('admin.layouts.master')

@section('title', __('admin.roles'))

@section('page_icon')
    <i class="fa-solid fa-shield-halved text-wood-600"></i>
@endsection

@section('page_title', __('admin.roles_list'))
@section('page_subtitle', 'إدارة الأدوار وصلاحيات الوصول للمستخدمين والمشرفين في النظام')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('roles.manage'))
        <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-plus"></i>
            <span>{{ __('admin.create_role') }}</span>
        </a>
    @endif
@endsection

@section('content')
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-bold">
                <tr>
                    <th class="px-6 py-4 text-start">#</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.role_name_ar') }} / {{ __('admin.role_name_en') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.role_name_slug') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.permissions_matrix') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.users_count') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($roles as $index => $role)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-6 py-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                <span>{{ $role->name_ar }}</span>
                                @if($role->is_system)
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded-full">
                                        {{ __('admin.system_role') }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $role->name_en }}</div>
                            @if($role->description_ar)
                                <div class="text-xs text-slate-500 mt-1 max-w-xs truncate">{{ $role->description_ar }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-wood-700 bg-wood-50/50 rounded-lg">
                            {{ $role->name }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $role->permissions_count > 0 ? 'bg-wood-100 text-wood-800' : 'bg-slate-100 text-slate-500' }}">
                                {{ $role->permissions_count }} صلاحية
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                <i class="fa-solid fa-users text-[10px] text-slate-400 ml-1"></i>
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </a>

                                @if(!$role->is_system)
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role->id) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="p-2 rounded-lg bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition confirm-delete" title="{{ __('admin.delete') }}">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 text-xs">
                            {{ __('admin.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
