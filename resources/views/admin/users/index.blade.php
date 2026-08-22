@extends('admin.layouts.master')

@section('title', __('admin.users'))

@section('page_icon')
    <i class="fa-solid fa-users-gear text-wood-600"></i>
@endsection

@section('page_title', __('admin.users_list'))
@section('page_subtitle', 'إدارة حسابات المشرفين الإداريين والأدوار المسندة لهم')

@section('page_actions')
    @if(auth()->user()->isSuperAdmin() || auth()->user()->hasPermission('users.manage'))
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-wood-600 hover:bg-wood-700 text-white text-xs font-bold rounded-xl shadow-md shadow-wood-600/30 transition">
            <i class="fa-solid fa-user-plus"></i>
            <span>{{ __('admin.create_user') }}</span>
        </a>
    @endif
@endsection

@section('content')
<!-- Filter & Search Bar -->
<div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs mb-5">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Search Query -->
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
        </div>

        <!-- Role Filter -->
        <div>
            <select name="role" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                <option value="">{{ __('admin.all') }} - الأدوار</option>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}" {{ request('role') == $r->id ? 'selected' : '' }}>
                        {{ $r->display_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <select name="status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition">
                <option value="">{{ __('admin.all') }} - {{ __('admin.status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
            </select>
        </div>

        <!-- Submit & Reset -->
        <div class="flex items-center gap-2">
            <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-filter ml-1"></i> تصفية
            </button>
            @if(request()->anyFilled(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="إعادة تعيين">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-start text-xs sm:text-sm">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase text-[11px] font-bold">
                <tr>
                    <th class="px-6 py-4 text-start">{{ __('admin.name') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.assigned_roles') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.phone') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.status') }}</th>
                    <th class="px-6 py-4 text-start">{{ __('admin.last_login') }}</th>
                    <th class="px-6 py-4 text-center">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $u)
                    <tr class="hover:bg-slate-50/80 transition">
                        <!-- Name & Avatar -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $u->avatar_url }}" alt="{{ $u->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-slate-100">
                                <div>
                                    <div class="font-bold text-slate-800 text-sm flex items-center gap-1.5">
                                        <span>{{ $u->name }}</span>
                                        @if($u->id === auth()->id())
                                            <span class="px-1.5 py-0.5 text-[10px] bg-wood-100 text-wood-800 font-bold rounded">أنت</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Roles -->
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($u->roles as $role)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $role->is_system ? 'bg-amber-100 text-amber-900 border border-amber-300/50' : 'bg-wood-100 text-wood-800' }}">
                                        {{ $role->display_name }}
                                    </span>
                                @empty
                                    <span class="text-xs text-slate-400">بدون دور</span>
                                @endforelse
                            </div>
                        </td>

                        <!-- Phone -->
                        <td class="px-6 py-4 text-xs font-mono text-slate-600">
                            {{ $u->phone ?: '-' }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @if($u->id === auth()->id())
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ __('admin.active') }}
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.users.toggle-status', $u->id) }}">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold transition cursor-pointer {{ $u->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-rose-100 text-rose-800 hover:bg-rose-200' }}" title="انقر لتغيير الحالة">
                                        {{ $u->is_active ? __('admin.active') : __('admin.inactive') }}
                                    </button>
                                </form>
                            @endif
                        </td>

                        <!-- Last Login -->
                        <td class="px-6 py-4 text-xs text-slate-500">
                            @if($u->last_login_at)
                                <div>{{ $u->last_login_at->diffForHumans() }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $u->last_login_ip }}</div>
                            @else
                                <span class="text-slate-400">{{ __('admin.never_logged_in') }}</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 rounded-lg bg-slate-100 hover:bg-wood-50 text-slate-600 hover:text-wood-700 transition" title="{{ __('admin.edit') }}">
                                    <i class="fa-solid fa-user-pen text-xs"></i>
                                </a>

                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}" class="inline">
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

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
