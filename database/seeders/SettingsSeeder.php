<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Identity
            ['key' => 'site_name_ar', 'value' => 'أرتيزان للأعمال الخشبية والديكور', 'group' => 'identity', 'type' => 'text', 'label_ar' => 'اسم المنشأة بالعربي', 'label_en' => 'Company Name (Arabic)'],
            ['key' => 'site_name_en', 'value' => 'Artisan Woodcraft & Joinery Studio', 'group' => 'identity', 'type' => 'text', 'label_ar' => 'اسم المنشأة بالإنجليزي', 'label_en' => 'Company Name (English)'],
            ['key' => 'site_tagline_ar', 'value' => 'إتقان في صناعة الأثاث الخشبي الفاخر والديكورات العصرية والمعارض', 'group' => 'identity', 'type' => 'text', 'label_ar' => 'الشعار اللفظي بالعربي', 'label_en' => 'Tagline (Arabic)'],
            ['key' => 'site_tagline_en', 'value' => 'Mastering Bespoke Woodcraft, Luxury Furniture & Exhibition Booths', 'group' => 'identity', 'type' => 'text', 'label_ar' => 'الشعار اللفظي بالإنجليزي', 'label_en' => 'Tagline (English)'],
            ['key' => 'site_logo', 'value' => null, 'group' => 'identity', 'type' => 'image', 'label_ar' => 'الشعار الرئيسي (Logo)', 'label_en' => 'Main Logo'],
            ['key' => 'logo_display_mode', 'value' => 'logo_only', 'group' => 'identity', 'type' => 'text', 'label_ar' => 'طريقة عرض الهوية والشعار', 'label_en' => 'Logo Display Mode'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'identity', 'type' => 'image', 'label_ar' => 'أيقونة المتصفح (Favicon)', 'label_en' => 'Favicon'],
            ['key' => 'site_footer_desc_ar', 'value' => 'ورشة متخصصة في تنفيذ أرقى أعمال النجارة والديكورات الخشبية التخصصية، غرف النوم الفاخرة، المكاتب التنفيذية، وبوثات المعارض بأحدث معايير الجودة والحرفية.', 'group' => 'identity', 'type' => 'textarea', 'label_ar' => 'وصف الفوتر بالعربي', 'label_en' => 'Footer Description (Arabic)'],
            ['key' => 'site_footer_desc_en', 'value' => 'Specialized woodworking & joinery workshop dedicated to crafting luxury custom furniture, executive offices, and exhibition booths with highest precision.', 'group' => 'identity', 'type' => 'textarea', 'label_ar' => 'وصف الفوتر بالإنجليزي', 'label_en' => 'Footer Description (English)'],

            // Colors & Theme
            ['key' => 'primary_color', 'value' => '#8B5A2B', 'group' => 'colors', 'type' => 'color', 'label_ar' => 'اللون الأساسي (Wood Warm Bronze)', 'label_en' => 'Primary Brand Color'],
            ['key' => 'secondary_color', 'value' => '#1F1B18', 'group' => 'colors', 'type' => 'color', 'label_ar' => 'اللون الثانوي (Dark Walnut Charcoal)', 'label_en' => 'Secondary Brand Color'],
            ['key' => 'accent_color', 'value' => '#D4AF37', 'group' => 'colors', 'type' => 'color', 'label_ar' => 'لون التمييز (Metallic Gold)', 'label_en' => 'Accent Color'],

            // Contact
            ['key' => 'contact_phone', 'value' => '+966 50 123 4567', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'رقم الهاتف الرئيسي', 'label_en' => 'Main Phone Number'],
            ['key' => 'contact_whatsapp', 'value' => '+966 50 123 4567', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'رقم الواتساب للاستفسارات والطلبات', 'label_en' => 'WhatsApp Number'],
            ['key' => 'contact_email', 'value' => 'info@artisanwood.sa', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'البريد الإلكتروني الرسمي', 'label_en' => 'Official Email'],
            ['key' => 'contact_address_ar', 'value' => 'المملكة العربية السعودية، الرياض، المنطقة الصناعية', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'العنوان بالعربي', 'label_en' => 'Address (Arabic)'],
            ['key' => 'contact_address_en', 'value' => 'Riyadh, Industrial Area, Kingdom of Saudi Arabia', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'العنوان بالإنجليزي', 'label_en' => 'Address (English)'],
            ['key' => 'working_hours_ar', 'value' => 'السبت - الخميس: 8:00 صباحاً - 9:00 مساءً', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'ساعات العمل بالعربي', 'label_en' => 'Working Hours (Arabic)'],
            ['key' => 'working_hours_en', 'value' => 'Sat - Thu: 8:00 AM - 9:00 PM', 'group' => 'contact', 'type' => 'text', 'label_ar' => 'ساعات العمل بالإنجليزي', 'label_en' => 'Working Hours (English)'],
            ['key' => 'google_maps_embed', 'value' => '', 'group' => 'contact', 'type' => 'textarea', 'label_ar' => 'كود خريطة جوجل (Iframe)', 'label_en' => 'Google Maps Embed'],

            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/artisanwood', 'group' => 'social', 'type' => 'text', 'label_ar' => 'رابط انستغرام', 'label_en' => 'Instagram URL'],
            ['key' => 'social_x', 'value' => 'https://x.com/artisanwood', 'group' => 'social', 'type' => 'text', 'label_ar' => 'رابط منصة X', 'label_en' => 'X (Twitter) URL'],
            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@artisanwood', 'group' => 'social', 'type' => 'text', 'label_ar' => 'رابط تيك توك', 'label_en' => 'TikTok URL'],
            ['key' => 'social_snapchat', 'value' => 'https://snapchat.com/add/artisanwood', 'group' => 'social', 'type' => 'text', 'label_ar' => 'رابط سناب شات', 'label_en' => 'Snapchat URL'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/artisanwood', 'group' => 'social', 'type' => 'text', 'label_ar' => 'رابط لينكد إن', 'label_en' => 'LinkedIn URL'],

            // SEO
            ['key' => 'seo_meta_title_ar', 'value' => 'أرتيزان للأعمال الخشبية والديكور | تفصيل غرف نوم ومكاتب وبوثات معارض', 'group' => 'seo', 'type' => 'text', 'label_ar' => 'عنوان الـ SEO بالعربي', 'label_en' => 'Meta Title (Arabic)'],
            ['key' => 'seo_meta_title_en', 'value' => 'Artisan Woodcraft & Joinery | Bespoke Furniture & Exhibition Booths', 'group' => 'seo', 'type' => 'text', 'label_ar' => 'عنوان الـ SEO بالإنجليزي', 'label_en' => 'Meta Title (English)'],
            ['key' => 'seo_meta_desc_ar', 'value' => 'أفضل ورشة نجارة وأعمال خشبية بالرياض متخصصة في صناعة غرف النوم العصرية، المكاتب التنفيذية، بوثات المعارض، وتكسيات الجدران الخشبية بأعلى دقة واحترافية.', 'group' => 'seo', 'type' => 'textarea', 'label_ar' => 'وصف الـ SEO بالعربي', 'label_en' => 'Meta Description (Arabic)'],
            ['key' => 'seo_meta_desc_en', 'value' => 'Leading bespoke woodworking workshop crafting luxury bedrooms, executive offices, exhibition booths, and custom wall paneling in Saudi Arabia.', 'group' => 'seo', 'type' => 'textarea', 'label_ar' => 'وصف الـ SEO بالإنجليزي', 'label_en' => 'Meta Description (English)'],
            ['key' => 'seo_keywords_ar', 'value' => 'ورشة نجارة, أعمال خشبية, تفصيل غرف نوم, مكاتب فخمة, بوثات معارض, ديكورات خشبية, أخشاب طبيعية, الرياض', 'group' => 'seo', 'type' => 'text', 'label_ar' => 'الكلمات المفتاحية بالعربي', 'label_en' => 'Meta Keywords (Arabic)'],
            ['key' => 'seo_keywords_en', 'value' => 'woodworking workshop, bespoke furniture, custom bedrooms, luxury offices, exhibition booths, wood wall cladding, Saudi Arabia', 'group' => 'seo', 'type' => 'text', 'label_ar' => 'الكلمات المفتاحية بالإنجليزي', 'label_en' => 'Meta Keywords (English)'],

            // SMTP Settings
            ['key' => 'mail_mailer', 'value' => 'smtp', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'بروتوكول الإرسال', 'label_en' => 'Mail Driver'],
            ['key' => 'mail_host', 'value' => 'smtp.mailtrap.io', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'خادم البريد (Host)', 'label_en' => 'SMTP Host'],
            ['key' => 'mail_port', 'value' => '2525', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'منفذ البريد (Port)', 'label_en' => 'SMTP Port'],
            ['key' => 'mail_username', 'value' => '', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'اسم مستخدم البريد', 'label_en' => 'SMTP Username'],
            ['key' => 'mail_password', 'value' => '', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'كلمة مرور البريد', 'label_en' => 'SMTP Password'],
            ['key' => 'mail_encryption', 'value' => 'tls', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'نوع التشفير (tls / ssl)', 'label_en' => 'Mail Encryption'],
            ['key' => 'mail_from_address', 'value' => 'notifications@artisanwood.sa', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'عنوان بريد المرسل', 'label_en' => 'From Address'],
            ['key' => 'mail_from_name', 'value' => 'ورشة أرتيزان للأعمال الخشبية', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'اسم المرسل', 'label_en' => 'From Name'],
            ['key' => 'notification_receiver_email', 'value' => 'admin@artisanwood.sa', 'group' => 'smtp', 'type' => 'text', 'label_ar' => 'البريد المستلم للإشعارات والطلبات الجديدة', 'label_en' => 'Admin Notification Receiver Email'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
