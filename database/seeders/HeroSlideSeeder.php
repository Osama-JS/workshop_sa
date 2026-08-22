<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create permissions
        $permView = Permission::firstOrCreate(['name' => 'hero.view'], [
            'name_ar' => 'عرض شرائح وقسم البداية',
            'name_en' => 'View Hero Slides',
            'group' => 'hero',
            'group_ar' => 'قسم البداية والشرائح',
            'group_en' => 'Hero Banner & Slides',
        ]);

        $permManage = Permission::firstOrCreate(['name' => 'hero.manage'], [
            'name_ar' => 'إدارة وتعديل قسم البداية والشرائح',
            'name_en' => 'Manage Hero Slides',
            'group' => 'hero',
            'group_ar' => 'قسم البداية والشرائح',
            'group_en' => 'Hero Banner & Slides',
        ]);

        // Attach to super_admin and content_manager
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->permissions()->syncWithoutDetaching([$permView->id, $permManage->id]);
        }

        $contentManagerRole = Role::where('name', 'content_manager')->first();
        if ($contentManagerRole) {
            $contentManagerRole->permissions()->syncWithoutDetaching([$permView->id, $permManage->id]);
        }

        // 2. Sample slides
        if (HeroSlide::count() === 0) {
            HeroSlide::create([
                'title_ar' => 'حرفية متوارثة وإتقان بلا حدود في عالم النجارة الفاخرة',
                'title_en' => 'Timeless Craftsmanship & Bespoke Luxury Woodwork',
                'subtitle_ar' => 'ورشة أرتيزان للأعمال الخشبية',
                'subtitle_en' => 'Artisan Saudi Woodcraft & Joinery',
                'description_ar' => 'نصمم ونصنع أثاث غرف النوم، المكاتب التنفيذية، بوثات المعارض، والتكسيات الجدارية بأجود أنواع الأخشاب الطبيعية والمعالجة.',
                'description_en' => 'We design and craft bespoke bedroom suites, executive offices, exhibition booths, and wall paneling using the finest hardwoods.',
                'btn_text_ar' => 'طلب تفصيل مخصص',
                'btn_text_en' => 'Request Custom Quote',
                'btn_url' => '#custom-order',
                'secondary_btn_text_ar' => 'استكشف معرض الأعمال',
                'secondary_btn_text_en' => 'Explore Portfolio',
                'secondary_btn_url' => '#portfolio',
                'is_active' => true,
                'sort_order' => 1,
            ]);

            HeroSlide::create([
                'title_ar' => 'ديكورات داخلية وبوثات معارض استثنائية تعكس هويتك',
                'title_en' => 'Exceptional Interior Joinery & Custom Exhibition Booths',
                'subtitle_ar' => 'تصاميم معمارية فريدة',
                'subtitle_en' => 'Architectural Wood Aesthetics',
                'description_ar' => 'حلول خشبية متكاملة للمشاريع السكنية والتجارية والفندقية وفق أعلى معايير الجودة والضمان المعتمد.',
                'description_en' => 'Integrated woodwork solutions for residential, commercial, and hospitality projects with top certified quality.',
                'btn_text_ar' => 'تصفح خدماتنا',
                'btn_text_en' => 'Browse Services',
                'btn_url' => '#services',
                'secondary_btn_text_ar' => 'تواصل معنا مباشرة',
                'secondary_btn_text_en' => 'Contact Us',
                'secondary_btn_url' => '#contact',
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }
    }
}
