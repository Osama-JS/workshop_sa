<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Define all permissions
        $permissions = [
            // Dashboard & Analytics
            [
                'name' => 'analytics.view',
                'name_ar' => 'عرض الإحصائيات وتقارير الزيارات',
                'name_en' => 'View Analytics & Visitor Reports',
                'group' => 'analytics',
                'group_ar' => 'الإحصائيات والتقارير',
                'group_en' => 'Analytics & Reports',
            ],

            // Services
            [
                'name' => 'services.view',
                'name_ar' => 'عرض قائمة الخدمات',
                'name_en' => 'View Services List',
                'group' => 'services',
                'group_ar' => 'إدارة الخدمات',
                'group_en' => 'Services Management',
            ],
            [
                'name' => 'services.create',
                'name_ar' => 'إضافة خدمة جديدة',
                'name_en' => 'Create New Service',
                'group' => 'services',
                'group_ar' => 'إدارة الخدمات',
                'group_en' => 'Services Management',
            ],
            [
                'name' => 'services.edit',
                'name_ar' => 'تعديل الخدمات',
                'name_en' => 'Edit Services',
                'group' => 'services',
                'group_ar' => 'إدارة الخدمات',
                'group_en' => 'Services Management',
            ],
            [
                'name' => 'services.delete',
                'name_ar' => 'حذف الخدمات',
                'name_en' => 'Delete Services',
                'group' => 'services',
                'group_ar' => 'إدارة الخدمات',
                'group_en' => 'Services Management',
            ],

            // Portfolios / Gallery
            [
                'name' => 'portfolios.view',
                'name_ar' => 'عرض معرض الأعمال',
                'name_en' => 'View Portfolio Gallery',
                'group' => 'portfolios',
                'group_ar' => 'معرض الأعمال',
                'group_en' => 'Portfolio Management',
            ],
            [
                'name' => 'portfolios.create',
                'name_ar' => 'إضافة عمل / مشروع جديد',
                'name_en' => 'Create New Project',
                'group' => 'portfolios',
                'group_ar' => 'معرض الأعمال',
                'group_en' => 'Portfolio Management',
            ],
            [
                'name' => 'portfolios.edit',
                'name_ar' => 'تعديل مشاريع المعرض',
                'name_en' => 'Edit Projects',
                'group' => 'portfolios',
                'group_ar' => 'معرض الأعمال',
                'group_en' => 'Portfolio Management',
            ],
            [
                'name' => 'portfolios.delete',
                'name_ar' => 'حذف مشاريع من المعرض',
                'name_en' => 'Delete Projects',
                'group' => 'portfolios',
                'group_ar' => 'معرض الأعمال',
                'group_en' => 'Portfolio Management',
            ],

            // Custom Orders
            [
                'name' => 'orders.view',
                'name_ar' => 'عرض ومتابعة طلبات التفصيل المخصصة',
                'name_en' => 'View Custom Orders',
                'group' => 'orders',
                'group_ar' => 'طلبات التفصيل المخصص',
                'group_en' => 'Custom Orders',
            ],
            [
                'name' => 'orders.edit',
                'name_ar' => 'تحديث حالة الطلب وإضافة ملاحظات',
                'name_en' => 'Update Order Status & Notes',
                'group' => 'orders',
                'group_ar' => 'طلبات التفصيل المخصص',
                'group_en' => 'Custom Orders',
            ],
            [
                'name' => 'orders.delete',
                'name_ar' => 'حذف طلبات مخصصة',
                'name_en' => 'Delete Custom Orders',
                'group' => 'orders',
                'group_ar' => 'طلبات التفصيل المخصص',
                'group_en' => 'Custom Orders',
            ],

            // Contact Messages
            [
                'name' => 'messages.view',
                'name_ar' => 'عرض رسائل الزوار والتواصل',
                'name_en' => 'View Contact Messages',
                'group' => 'messages',
                'group_ar' => 'رسائل التواصل',
                'group_en' => 'Contact Messages',
            ],
            [
                'name' => 'messages.reply',
                'name_ar' => 'الرد وتحديث حالة الرسائل',
                'name_en' => 'Reply & Update Messages',
                'group' => 'messages',
                'group_ar' => 'رسائل التواصل',
                'group_en' => 'Contact Messages',
            ],
            [
                'name' => 'messages.delete',
                'name_ar' => 'حذف رسائل التواصل',
                'name_en' => 'Delete Contact Messages',
                'group' => 'messages',
                'group_ar' => 'رسائل التواصل',
                'group_en' => 'Contact Messages',
            ],

            // Custom Pages
            [
                'name' => 'pages.view',
                'name_ar' => 'عرض الصفحات المخصصة',
                'name_en' => 'View Custom Pages',
                'group' => 'pages',
                'group_ar' => 'الصفحات والمحتوى',
                'group_en' => 'Custom Pages',
            ],
            [
                'name' => 'pages.create',
                'name_ar' => 'إنشاء صفحة جديدة',
                'name_en' => 'Create Custom Page',
                'group' => 'pages',
                'group_ar' => 'الصفحات والمحتوى',
                'group_en' => 'Custom Pages',
            ],
            [
                'name' => 'pages.edit',
                'name_ar' => 'تعديل الصفحات المخصصة',
                'name_en' => 'Edit Custom Pages',
                'group' => 'pages',
                'group_ar' => 'الصفحات والمحتوى',
                'group_en' => 'Custom Pages',
            ],
            [
                'name' => 'pages.delete',
                'name_ar' => 'حذف الصفحات المخصصة',
                'name_en' => 'Delete Custom Pages',
                'group' => 'pages',
                'group_ar' => 'الصفحات والمحتوى',
                'group_en' => 'Custom Pages',
            ],

            // About Us & Testimonials
            [
                'name' => 'about.edit',
                'name_ar' => 'تعديل بيانات صفحة من نحن والرؤية والرسالة',
                'name_en' => 'Edit About Us, Vision & Mission',
                'group' => 'about',
                'group_ar' => 'صفحة من نحن والتقييمات',
                'group_en' => 'About Us & Testimonials',
            ],
            [
                'name' => 'testimonials.manage',
                'name_ar' => 'إدارة آراء وتقييمات العملاء',
                'name_en' => 'Manage Client Testimonials',
                'group' => 'about',
                'group_ar' => 'صفحة من نحن والتقييمات',
                'group_en' => 'About Us & Testimonials',
            ],

            // Settings & Mail
            [
                'name' => 'settings.view',
                'name_ar' => 'عرض إعدادات الموقع والهوية',
                'name_en' => 'View Settings & Identity',
                'group' => 'settings',
                'group_ar' => 'الإعدادات العامة والهوية',
                'group_en' => 'Settings & Identity',
            ],
            [
                'name' => 'settings.edit',
                'name_ar' => 'تعديل إعدادات الهوية والألوان والـ SEO والبريد',
                'name_en' => 'Edit Identity, Colors, SEO & Mail Settings',
                'group' => 'settings',
                'group_ar' => 'الإعدادات العامة والهوية',
                'group_en' => 'Settings & Identity',
            ],

            // Users & Roles (RBAC Management)
            [
                'name' => 'users.manage',
                'name_ar' => 'إدارة المستخدمين الإداريين والحسابات',
                'name_en' => 'Manage Admin Users & Accounts',
                'group' => 'users',
                'group_ar' => 'المستخدمين والأدوار',
                'group_en' => 'Users & Roles Management',
            ],
            [
                'name' => 'roles.manage',
                'name_ar' => 'إدارة الأدوار وتعيين مصفوفة الصلاحيات',
                'name_en' => 'Manage Roles & Permission Matrix',
                'group' => 'users',
                'group_ar' => 'المستخدمين والأدوار',
                'group_en' => 'Users & Roles Management',
            ],
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm['name']], $perm);
        }

        // 1. Super Admin Role
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'name_ar' => 'المدير العام (صلاحيات كاملة)',
                'name_en' => 'Super Administrator',
                'description_ar' => 'يملك كافة الصلاحيات للتحكم بالمنصة وإدارة المشرفين والإعدادات',
                'description_en' => 'Has full access to all system features, settings and users',
                'is_system' => true,
            ]
        );
        // Super Admin gets all permissions
        $superAdminRole->permissions()->sync(Permission::all());

        // 2. Content Manager Role
        $contentManagerRole = Role::updateOrCreate(
            ['name' => 'content_manager'],
            [
                'name_ar' => 'مدير المحتوى والمعرض',
                'name_en' => 'Content & Portfolio Manager',
                'description_ar' => 'مسؤول عن إدارة الخدمات والمشاريع والصفحات وآراء العملاء',
                'description_en' => 'Responsible for managing services, portfolio, pages and testimonials',
                'is_system' => false,
            ]
        );
        $contentPermissions = Permission::whereIn('group', ['services', 'portfolios', 'pages', 'about'])->get();
        $contentManagerRole->permissions()->sync($contentPermissions);

        // 3. Customer Service Role
        $customerServiceRole = Role::updateOrCreate(
            ['name' => 'customer_service'],
            [
                'name_ar' => 'خدمة العملاء والمبيعات',
                'name_en' => 'Customer Service & Sales',
                'description_ar' => 'متابعة وتحديث طلبات التفصيل المخصصة والرد على رسائل واستفسارات الزوار',
                'description_en' => 'Handles custom woodwork orders and visitor inquiries',
                'is_system' => false,
            ]
        );
        $csPermissions = Permission::whereIn('group', ['orders', 'messages'])->get();
        $customerServiceRole->permissions()->sync($csPermissions);
    }
}
