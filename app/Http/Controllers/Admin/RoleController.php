<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissionGroups = Permission::all()->groupBy(function ($item) {
            return app()->getLocale() === 'ar' ? $item->group_ar : $item->group_en;
        });

        return view('admin.roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:100', 'unique:roles,name'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $slug = !empty($validated['name']) 
            ? Str::slug($validated['name'], '_') 
            : Str::slug($validated['name_en'], '_');

        // Ensure unique slug
        $originalSlug = $slug;
        $count = 1;
        while (Role::where('name', $slug)->exists()) {
            $slug = "{$originalSlug}_{$count}";
            $count++;
        }

        $role = Role::create([
            'name' => $slug,
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        return redirect()->route('admin.roles.index')->with('success', __('admin.role_created'));
    }

    public function edit(Role $role)
    {
        $permissionGroups = Permission::all()->groupBy(function ($item) {
            return app()->getLocale() === 'ar' ? $item->group_ar : $item->group_en;
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update([
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'description_ar' => $validated['description_ar'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        } else {
            $role->permissions()->detach();
        }

        return redirect()->route('admin.roles.index')->with('success', __('admin.role_updated'));
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', __('admin.cannot_delete_system_role'));
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', __('admin.role_deleted'));
    }
}
