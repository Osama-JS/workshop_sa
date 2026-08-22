<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin User
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@artisanwood.sa'],
            [
                'name' => 'المدير العام (Super Admin)',
                'phone' => '+966500000000',
                'password' => Hash::make('admin123456'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }

        // 2. Sample Content Editor User
        $editor = User::updateOrCreate(
            ['email' => 'editor@artisanwood.sa'],
            [
                'name' => 'مدير المحتوى (Editor)',
                'phone' => '+966500000001',
                'password' => Hash::make('editor123456'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $contentRole = Role::where('name', 'content_manager')->first();
        if ($contentRole) {
            $editor->roles()->syncWithoutDetaching([$contentRole->id]);
        }

        // 3. Sample Customer Support User
        $support = User::updateOrCreate(
            ['email' => 'support@artisanwood.sa'],
            [
                'name' => 'خدمة العملاء (Support)',
                'phone' => '+966500000002',
                'password' => Hash::make('support123456'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $csRole = Role::where('name', 'customer_service')->first();
        if ($csRole) {
            $support->roles()->syncWithoutDetaching([$csRole->id]);
        }
    }
}
