<?php

namespace Database\Seeders;

use App\Models\AiDesignIdea;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class AiAssistantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed AI Settings
        $aiSettings = [
            'ai_enabled' => '1',
            'ai_gemini_api_key' => env('GEMINI_API_KEY', ''),
            'ai_model' => 'gemini-1.5-flash',
            'ai_bot_name_ar' => 'مستشار أرتيزان الذكي',
            'ai_bot_name_en' => 'Artisan AI Wood Consultant',
            'ai_bot_role_ar' => 'مهندس ومستشار حرفي متخصص في تفصيل الأعمال الخشبية والديكور',
            'ai_bot_role_en' => 'Luxury Woodwork & Joinery Engineering Consultant',
            'ai_welcome_msg_ar' => 'أهلاً بك في ورشة أرتيزان للأعمال الخشبية الفاخرة! 🪵✨ أنا مستشارك الحرفي والهندسي. يسعدني مساعدتك في اختيار أفضل التصاميم الخشبية، تقديم أفكار لغرف النوم والمكاتب والبوثات، مقارنة أنواع الخشب، أو رفع طلب تفصيل مخصص لك فوراً!',
            'ai_welcome_msg_en' => 'Welcome to Artisan Luxury Woodwork Workshop! 🪵✨ I am your AI Joinery Consultant. How can I assist you with custom designs, bedrooms, offices, booths, wood selection, or instant custom order quotation?',
            'ai_temperature' => '0.7',
            'ai_max_tokens' => '1000',
            'ai_daily_message_limit' => '25',
            'ai_system_prompt_ar' => 'أنت مستشار هندسي وحرفي ذكي ومحترف لورشة "أرتيزان للأعمال الخشبية الفاخرة" في المملكة العربية السعودية.
مهمتك الأساسية هي:
1. الترحيب بالعملاء بلباقة واحترافية وبلهجة سعودية راقية أو عربية فصحى دافئة.
2. الإجابة بدقة عن كل ما يخص صناعة الأثاث المخصص، غرف النوم، المكاتب التنفيذية، بوثات المعارض، التكسيات الجدارية، وأنواع الأخشاب (بلوط، جوز، زان، تيك، سويدي).
3. تقديم نصائح عملية حول الأبعاد والمقاسات وطريقة الصيانة ونوع الخشب المناسب لطبيعة المكان.
4. عرض وتوصية أفكار وتصاميم بنترست والخدمات المتاحة في قاعدة معرفة الموقع.
5. إذا أعجب العميل بأي تصميم أو رغب في تنفيذ عمل مخصص، اعرض عليه تسجيل طلب تفصيل رسمي واطلب منه (اسمه ورقم جواله والمقاسات التقديرية) لتسجيل طلبه فوراً.
6. إذا استفسر العميل عن حالة طلب سابق برقم الطلب (مثل ORD-2026-XXXX)، ابحث عنه واعرض حالته ومرحلته.
7. قيود صارمة (STRICT GUARDRAILS): إذا سألك العميل عن أي موضوع ليس له أي علاقة بالأعمال الخشبية أو الديكور أو خدمات الورشة (مثل البرمجة، السياسة، الرياضة، الطبخ، إلخ)، اعتذر له بأدب وأخبره بأنك متخصص حصرياً في استشارات وتفصيل الأعمال الخشبية والديكور لورشة أرتيزان.',
            'ai_system_prompt_en' => 'You are the professional AI Joinery & Woodwork Consultant for "Artisan Luxury Woodwork Workshop" in Saudi Arabia.
Your core mission:
1. Welcome clients politely with high-end hospitality.
2. Provide expert advice on bespoke furniture, bedrooms, executive desks, exhibition booths, wall claddings, and hardwood varieties (Oak, Walnut, Beech, Teak, Pine).
3. Recommend Pinterest design inspirations and workshop portfolio items based on user preferences.
4. When a user is interested in crafting a design, offer to register a custom order by gathering their name, phone/whatsapp, and estimated dimensions.
5. If the user provides an order tracking code (e.g. ORD-2026-XXXX), report their order status.
6. STRICT GUARDRAIL: If asked about any topic unrelated to woodwork, joinery, furniture, or workshop services, politely decline and state that you are exclusively specialized in Artisan workshop woodworking solutions.',
        ];

        foreach ($aiSettings as $key => $val) {
            Setting::set($key, $val);
        }

        // 2. Seed Initial Pinterest & Design Inspirations
        $ideas = [
            [
                'title_ar' => 'غرفة نوم ماستر ملكية بتكسيات خشب بلوط طبيعي',
                'title_en' => 'Royal Master Bedroom with Natural Oak Fluted Paneling',
                'category' => 'bedrooms',
                'description_ar' => 'تصميم عصري متكامل يشمل سرير كينج 200×200 سم مع خلفية سرير بتكسيات خشبية مموجة، تسريحة معلقة بمرآة LED ذكية، و2 كومودينو، خشب بلوط طبيعي مع تشطيب ألماني مطفي.',
                'description_en' => 'King size bed set with fluted natural oak headboard, floating vanity with smart LED mirror, and nightstands with matte finish.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=luxury%20oak%20bedroom%20design',
                'image' => null,
                'wood_type' => 'خشب بلوط طبيعي (Natural White Oak)',
                'dimensions' => 'غرفة 5 × 4 متر (سرير 200×200 سم)',
                'estimated_price_range' => '22,000 - 32,000 ريال',
                'tags' => 'غرف نوم, بلوط, ماستر, تكسيات جدارية, فخامة',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title_ar' => 'مكتب تنفيذي ملكي من خشب الجوز الأمريكي مع حواف طبيعية (Live Edge)',
                'title_en' => 'Executive Live Edge American Walnut Desk',
                'category' => 'offices',
                'description_ar' => 'طاولة مكتب للمدراء التنفيذيين مصنوعة من قطعة واحدة صلبة من خشب الجوز الأمريكي، بأرجل معدنية مخصصة باللون الأسود المطفي مع وحدة أدراج مخفية وقنوات توصيل كهرباء مدمجة.',
                'description_en' => 'Solid single-slab American walnut executive desk with matte black architectural base and hidden cable management.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=live%20edge%20walnut%20executive%20desk',
                'image' => null,
                'wood_type' => 'خشب جوز أمريكي صلب (Solid American Walnut)',
                'dimensions' => 'طول 2.40 متر × عرض 1.10 متر',
                'estimated_price_range' => '16,000 - 24,000 ريال',
                'tags' => 'مكاتب تنفيذية, جوز أمريكي, Live Edge, فخامة, شركات',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title_ar' => 'بوث معرض تفاعلي بهياكل خشبية ثلاثية الأبعاد (3D Wooden Booth)',
                'title_en' => 'Parametric 3D Wooden Exhibition Pavilion',
                'category' => 'booths',
                'description_ar' => 'جناح معرض تجاري بمعمارية بارامترية خشبية ثلاثية الأبعاد، مزود بمنصات استقبال ومنطقة اجتماعات VIP مع إضاءات موجهة وشاشات عرض تفاعلية.',
                'description_en' => 'Parametric 3D wooden exhibition booth with reception counters, VIP meeting lounge, and integrated smart media screens.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=parametric%20wood%20exhibition%20booth',
                'image' => null,
                'wood_type' => 'MDF مقسى بقشرة خشب سنديان معالج لمقاومة الحريق',
                'dimensions' => 'مساحة 6 × 6 متر (ارتفاع 4 متر)',
                'estimated_price_range' => '45,000 - 85,000 ريال',
                'tags' => 'بوثات معارض, فعاليات الرياض, بارامتريك, ديكور معارض',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title_ar' => 'طاولة طعام ملكية لعشرة أشخاص من خشب التيك والزان مع كراسي منجدة',
                'title_en' => 'Royal 10-Seater Teak & Beech Dining Table Set',
                'category' => 'tables',
                'description_ar' => 'طاولة طعام بتصميم نيو كلاسيك مصنوعة من أجود أخشاب التيك الإندونيسي المقاوم مع هيكل من الزان الألماني و10 كراسي بتنجيد مخملي فاخر وتطعيمات نحاسية.',
                'description_en' => 'Neo-classical dining table crafted from premium Indonesian teak with German beech frame and 10 velvet upholstered chairs.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=luxury%20wooden%20dining%20table%2010%20seats',
                'image' => null,
                'wood_type' => 'خشب تيك فاخر وزان ألماني (Teak & German Beech)',
                'dimensions' => 'طول 3.20 متر × عرض 1.20 متر',
                'estimated_price_range' => '18,000 - 28,000 ريال',
                'tags' => 'طاولات طعام, تيك, زان, ضيافة, قصور',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title_ar' => 'تكسيات جدارية مودرن مع ديكور شاشة ومكتبة أرفف مخفية',
                'title_en' => 'Modern Fluted Wall Paneling & Media Console Unit',
                'category' => 'wall_cladding',
                'description_ar' => 'تكسية جدار كاملة بشرائح خشبية هندسية مدمجة مع بديل الرخام، وحدة تلفزيون معلقة مع إضاءة LED بروفايل مخفية ورفوف عرض للتحف.',
                'description_en' => 'Full wall architectural wood cladding integrated with marble slabs, floating TV unit, and concealed LED profile lighting.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=modern%20wood%20wall%20slats%20tv%20unit',
                'image' => null,
                'wood_type' => 'خشب سويدي معالج وقشرة بلوط طبيعي',
                'dimensions' => 'عرض الجدار 4.5 متر × ارتفاع 3 متر',
                'estimated_price_range' => '12,000 - 18,000 ريال',
                'tags' => 'تكسيات جدارية, ديكور تلفزيون, إضاءة مخفية, مودرن',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'title_ar' => 'خزانة ملابس دريسنج روم مفتوحة (Walk-in Closet) مع إضاءة أرفف ذكية',
                'title_en' => 'Luxury Custom Walk-in Dressing Closet with Sensor LED',
                'category' => 'cabinets',
                'description_ar' => 'غرفة دريسنج روم مخصصة حسب المقاس مع تقسيمات ذكية للأحذية والحقائب والملابس المعلقة، أبواب زجاجية مضلعة فريملس مع إضاءات استشعار تلقائية.',
                'description_en' => 'Bespoke walk-in dressing room with smart organizers, fluted glass aluminum doors, and motion-sensor LED shelving.',
                'pinterest_url' => 'https://www.pinterest.com/search/pins/?q=luxury%20walk%20in%20closet%20wood%20led',
                'image' => null,
                'wood_type' => 'خشب ألماني مقاوم للرطوبة مكسو بقشرة جوز طبيعي',
                'dimensions' => 'مساحة الغرفة 3.5 × 3 متر',
                'estimated_price_range' => '25,000 - 38,000 ريال',
                'tags' => 'دريسنج روم, خزائن ملابس, زجاج مضلع, إضاءة ذكية',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($ideas as $idea) {
            AiDesignIdea::updateOrCreate(
                ['title_ar' => $idea['title_ar']],
                $idea
            );
        }
    }
}
