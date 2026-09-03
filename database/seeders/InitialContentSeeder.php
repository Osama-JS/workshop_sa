<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use App\Models\CustomPage;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InitialContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Services
        $servicesData = [
            [
                'title_ar' => 'تصنيع وتفصيل غرف النوم الفاخرة',
                'title_en' => 'Luxury Custom Bedroom Manufacturing',
                'slug' => 'custom-bedroom-manufacturing',
                'short_desc_ar' => 'تصميم وتنفيذ غرف نوم ماستر، خزائن ملابس ذكية (Walk-in Closets)، وتسريحات مخصصة بأجود أنواع الأخشاب الطبيعية.',
                'short_desc_en' => 'Design and manufacturing of master bedrooms, walk-in closets, and bespoke vanities using premium natural wood.',
                'content_ar' => '<p>نقدم خدمة تفصيل غرف النوم المتكاملة وفق أعلى معايير الحرفية والجمال. نستخدم خشب الجوز، السنديان، والبلوط مع آليات إغلاق هيدروليكية ذكية وإضاءات مخفية متطورة لتمنحك غرفة أحلام تدوم لعقود.</p>',
                'content_en' => '<p>We provide comprehensive bespoke bedroom solutions adhering to the highest craftsmanship standards. We utilize Walnut, Oak, and Teak combined with smart soft-close mechanisms and ambient LED integrations.</p>',
                'icon' => 'bed',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title_ar' => 'المكاتب التنفيذية وقاعات الاجتماعات',
                'title_en' => 'Executive Offices & Boardrooms',
                'slug' => 'executive-offices-boardrooms',
                'short_desc_ar' => 'تفصيل مكاتب الرؤساء التنفيذيين، طاولات الاجتماعات الكبرى، وتجهيزات الشركات بمظهر فخم يعكس الهيبة والاحترافية.',
                'short_desc_en' => 'Custom executive desks, large boardroom tables, and corporate woodwork crafted to reflect elegance and prestige.',
                'content_ar' => '<p>نصمم المكاتب التنفيذية المرموقة التي تجمع بين راحة الاستخدام وجمال الخشب الطبيعي المصقول مع مسارات كابلات ومنافذ شحن ذكية مخفية، مصممة خصيصاً للشركات والجهات الراقية.</p>',
                'content_en' => '<p>We engineer prestigious executive workspaces combining ergonomic functionality with polished natural wood aesthetics and concealed cable management systems.</p>',
                'icon' => 'briefcase',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title_ar' => 'تصميم وتنفيذ بوثات المعارض والفعاليات',
                'title_en' => 'Exhibition Booths & Event Pavilions',
                'slug' => 'exhibition-booths-pavilions',
                'short_desc_ar' => 'بناء وتجهيز أجنحة المعارض ومنصات العرض التفاعلية بدقة هندسية عالية والتزام صارم بمواعيد التسليم.',
                'short_desc_en' => 'Building and furnishing custom exhibition booths and interactive display pavilions with precision engineering.',
                'content_ar' => '<p>نمتلك الخبرة والقدرة التشغيلية لبناء أجنحة المعارض التجارية والبوثات المتطورة بأشكال معمارية خشبية ثلاثية الأبعاد تجذب الزوار وتبرز قوة علامتك التجارية في كبرى المؤتمرات والمعارض.</p>',
                'content_en' => '<p>We possess the manufacturing capability to construct exhibition stands and interactive booths with 3D wooden architectural elements that elevate brand presence.</p>',
                'icon' => 'store',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'title_ar' => 'التكسيات الجدارية والديكورات الخشبية',
                'title_en' => 'Wood Wall Cladding & Architectural Features',
                'slug' => 'wood-wall-cladding-features',
                'short_desc_ar' => 'تكسيات جدارية عازلة ومموجة، فواصل وقواطع بارتشن (Partitions)، وسقفيات خشبية عصرية.',
                'short_desc_en' => 'Acoustic and fluted wall paneling, aesthetic partitions, and modern architectural wooden ceiling features.',
                'content_ar' => '<p>تحويل المساحات الفارغة إلى لوحات فنية عبر التكسيات الخشبية العصرية (Wood Slats & Panels) والبانوهات الكلاسيكية والقواطع الجمالية ذات الطابع الهندسي المبتكر.</p>',
                'content_en' => '<p>Transforming interiors into masterpieces with modern acoustic wooden slats, classic wall mouldings, and custom architectural dividers.</p>',
                'icon' => 'layers',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'title_ar' => 'الأبواب الفاخرة والمداخل الخشبية',
                'title_en' => 'Luxury Custom Doors & Entrances',
                'slug' => 'luxury-custom-doors',
                'short_desc_ar' => 'أبواب خشبية صلبة بارتفاعات شاهقة وتصاميم محورية (Pivot Doors) مخصصة للقصور والفلل والمشاريع الراقية.',
                'short_desc_en' => 'Solid grand entrance doors and oversized pivot systems designed for palaces, villas, and upscale projects.',
                'content_ar' => '<p>تصنيع أبواب المداخل الرئيسية والأبواب الداخلية بخشب صلب معالج ضد الرطوبة والحرارة، ومزودة بأقفال ذكية ومفصلات محورية فائقة التحمل.</p>',
                'content_en' => '<p>Crafting weather-treated solid hardwood main entrance doors and internal doors equipped with heavy-duty pivot hinges and smart locks.</p>',
                'icon' => 'door-closed',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($servicesData as $serviceItem) {
            $service = Service::updateOrCreate(['slug' => $serviceItem['slug']], $serviceItem);

            // Create sample portfolio for each service
            Portfolio::updateOrCreate(
                ['slug' => 'project-' . $service->slug],
                [
                    'title_ar' => 'مشروع تنفيذ ' . $service->title_ar . ' - فيلا خاصة',
                    'title_en' => 'Bespoke Project: ' . $service->title_en . ' - Private Villa',
                    'service_id' => $service->id,
                    'description_ar' => 'تم تنفيذ هذا المشروع بأعلى درجات الإتقان باستخدام خشب الجوز الأمريكي الطبيعي والتشطيبات الإيطالية المقاومة للخدش.',
                    'description_en' => 'Executed with exceptional craftsmanship utilizing natural American Walnut and premium Italian scratch-resistant finishes.',
                    'client_name' => 'عميل راقي - الرياض',
                    'completion_date' => now()->subMonths(rand(1, 10)),
                    'location' => 'الرياض، المملكة العربية السعودية',
                    'is_featured' => true,
                    'is_active' => true,
                    'sort_order' => $service->sort_order,
                ]
            );
        }

        // 2. About Sections
        $aboutSections = [
            [
                'section_key' => 'about',
                'title_ar' => 'من نحن - ورشة أرتيزان للأعمال الخشبية الفاخرة',
                'title_en' => 'About Us - Artisan Luxury Woodworking Workshop',
                'subtitle_ar' => 'صرح سعودي رائد في هندسة وتفصيل الخشب الطبيعي والديكورات الراقية',
                'subtitle_en' => 'A leading Saudi powerhouse in bespoke timber engineering and luxury interior decor',
                'content_ar' => '<p>نحن في ورشة أرتيزان نفخر بكوننا أحد أبرز الصروح المتخصصة في النجارة المعمارية وتفصيل الأثاث الخشبي الفاخر في المملكة العربية السعودية. نعتمد على كوادر فنية وحرفيين ذوي خبرات عريقة، ونوظف أحدث ما توصلت إليه التكنولوجيا لنلبي تطلعات القصور والمكاتب والمعارض والمشاريع التجارية الكبرى بدقة متناهية وجودة تفوق التوقعات.</p>',
                'content_en' => '<p>At Artisan Workshop, we take immense pride in being one of the premier workshops dedicated to architectural joinery and luxury custom woodwork in Saudi Arabia. Combining master artisan hands with state-of-the-art CNC technology, we craft bespoke furniture and timber interiors that exceed expectations for residences, commercial spaces, and exhibition stands.</p>',
                'meta_data' => null,
                'sort_order' => 1,
            ],
            [
                'section_key' => 'story',
                'title_ar' => 'قصتنا وشغفنا بالخشب',
                'title_en' => 'Our Story & Woodcraft Passion',
                'subtitle_ar' => 'أكثر من عقد ونصف في تحويل كتل الخشب الخام إلى تحف معمارية',
                'subtitle_en' => 'Over 15 years turning raw timber into timeless architectural masterpieces',
                'content_ar' => '<p>تأسست ورشة أرتيزان برؤية واضحة: إعادة تعريف مفهوم النجارة والديكور الخشبي في المملكة العربية السعودية. نمزج بين الحرفية اليدوية الأصيلة وأحدث مكائن الـ CNC لتقديم تصاميم حصرية تلبي تطلعات عشاق الفخامة.</p>',
                'content_en' => '<p>Artisan Workshop was established with a clear vision: redefine woodworking and joinery standards in Saudi Arabia. We blend authentic handcraftsmanship with advanced CNC precision machinery.</p>',
                'meta_data' => null,
                'sort_order' => 2,
            ],
            [
                'section_key' => 'vision_mission',
                'title_ar' => 'رؤيتنا ورسالتنا',
                'title_en' => 'Our Vision & Mission',
                'subtitle_ar' => 'الجودة المطلقة والدقة الهندسية في كل تفصيل',
                'subtitle_en' => 'Absolute quality and engineering precision in every detail',
                'content_ar' => '<p><strong>رؤيتنا:</strong> أن نكون الخيار الأول والوجهة الموثوقة للأفراد والشركات في تنفيذ المشاريع الخشبية الفاخرة.<br><strong>رسالتنا:</strong> تقديم حلول خشبية مخصصة تجمع بين المتانة العالية والجمال الخالد مع الالتزام التام بالمواعيد والمواصفات.</p>',
                'content_en' => '<p><strong>Our Vision:</strong> To be the premier destination for luxury bespoke woodwork in the region.<br><strong>Our Mission:</strong> Delivering tailor-made timber solutions marrying structural durability with timeless beauty on exact schedules.</p>',
                'meta_data' => null,
                'sort_order' => 3,
            ],
            [
                'section_key' => 'values',
                'title_ar' => 'قيمنا ومبادئ عملنا الراسخة',
                'title_en' => 'Our Enduring Core Values',
                'subtitle_ar' => 'المبادئ السامية التي تحكم كل مرحلة في ورشتنا',
                'subtitle_en' => 'The guiding principles behind every creation in our workshop',
                'content_ar' => null,
                'content_en' => null,
                'meta_data' => [
                    [
                        'title_ar' => 'الإتقان والجودة المطلقة',
                        'title_en' => 'Uncompromising Quality',
                        'icon' => 'fa-solid fa-gem',
                        'desc_ar' => 'اختيار أرقى أخشاب الزان، البلوط، والجوز المعالج بأعلى المعايير العالمية لضمان الديمومة والفخامة.',
                        'desc_en' => 'Selecting the finest seasoned oak, walnut, and beech wood adhering to international luxury standards.'
                    ],
                    [
                        'title_ar' => 'الحرفية والابتكار',
                        'title_en' => 'Craftsmanship & Innovation',
                        'icon' => 'fa-solid fa-wand-magic-sparkles',
                        'desc_ar' => 'المزج الخلاق بين المهارة اليدوية التراثية الأصيلة ودقة الماكينات الرقمية الحديثة لابتكار تفاصيل فريدة.',
                        'desc_en' => 'Seamlessly combining traditional artisanal woodwork with state-of-the-art CNC machining precision.'
                    ],
                    [
                        'title_ar' => 'الالتزام والشفافية',
                        'title_en' => 'Commitment & Trust',
                        'icon' => 'fa-solid fa-handshake-simple',
                        'desc_ar' => 'احترام المواعيد المحددة للتسليم والوضوح الكامل في كل مرحلة من مراحل التصميم والتركيب.',
                        'desc_en' => 'Strict adherence to project timelines and absolute transparency throughout design, manufacturing, and installation.'
                    ],
                    [
                        'title_ar' => 'الاستدامة والأصالة',
                        'title_en' => 'Sustainability & Heritage',
                        'icon' => 'fa-solid fa-tree',
                        'desc_ar' => 'الاعتماد على مصادر أخشاب مستدامة ومعتمدة بيئياً، والحفاظ على أصالة الحرفة السعودية العريقة.',
                        'desc_en' => 'Sourcing certified eco-friendly timber and preserving the authentic Saudi craft heritage with timeless appeal.'
                    ]
                ],
                'sort_order' => 4,
            ],
            [
                'section_key' => 'stats',
                'title_ar' => 'إنجازات وأرقام تتحدث عنا',
                'title_en' => 'Key Milestones & Achievements',
                'subtitle_ar' => 'ثقة بنيناها على مدار سنوات من العمل المتقن',
                'subtitle_en' => 'Trust earned through years of dedicated craftsmanship',
                'content_ar' => null,
                'content_en' => null,
                'meta_data' => [
                    ['number' => '15+', 'label_ar' => 'عاماً من الخبرة والحرفية', 'label_en' => 'Years of Craftsmanship'],
                    ['number' => '450+', 'label_ar' => 'مشروعاً ناجحاً تم تسليمه', 'label_en' => 'Completed Projects'],
                    ['number' => '85+', 'label_ar' => 'بوث معرض تم تنفيذه', 'label_en' => 'Exhibition Stands Built'],
                    ['number' => '99%', 'label_ar' => 'نسبة رضا العملاء', 'label_en' => 'Client Satisfaction Rate'],
                ],
                'sort_order' => 5,
            ],
        ];

        foreach ($aboutSections as $sec) {
            AboutSection::updateOrCreate(['section_key' => $sec['section_key']], $sec);
        }

        // 3. Testimonials
        $testimonials = [
            [
                'client_name_ar' => 'المهندس / خالد السبيعي',
                'client_name_en' => 'Eng. Khaled Al-Subaie',
                'client_position_ar' => 'مالك فيلا سكنية',
                'client_position_en' => 'Villa Owner',
                'company_ar' => 'الرياض',
                'company_en' => 'Riyadh',
                'rating' => 5,
                'comment_ar' => 'تعاملت مع ورشة أرتيزان في تفصيل غرف النوم والبانوهات الجدارية للفيلا بالكامل، العمل فاق توقعاتي من حيث جودة الخشب والتشطيب والالتزام بالموعد المحدد.',
                'comment_en' => 'Contracted Artisan for complete villa bedrooms and wood wall paneling. The execution exceeded expectations in timber quality, finish, and timely delivery.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'client_name_ar' => 'أ. فهد الشمري',
                'client_name_en' => 'Fahad Al-Shammari',
                'client_position_ar' => 'مدير العمليات',
                'client_position_en' => 'Operations Director',
                'company_ar' => 'شركة ريادة للمعارض',
                'company_en' => 'Riyada Events & Expos',
                'rating' => 5,
                'comment_ar' => 'قاموا ببناء بوث الشركة في معرض جايتكس بدقة هندسية عالية وسرعة استثنائية، فريق محترف بكل ما تعنيه الكلمة.',
                'comment_en' => 'They built our Gitex exhibition stand with incredible engineering precision and speed. True professionals.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'client_name_ar' => 'سارة العتيبي',
                'client_name_en' => 'Sarah Al-Otaibi',
                'client_position_ar' => 'مهندسة ديكور داخلي',
                'client_position_en' => 'Interior Designer',
                'company_ar' => 'استوديو التصميم المعماري',
                'company_en' => 'Architectural Design Studio',
                'rating' => 5,
                'comment_ar' => 'أفضل ورشة نجارة أتعامل معها لتنفيذ تصاميمي المعقدة للمكاتب التنفيذية والخزائن الذكية، دقة في تنفيذ المخططات بدون أي أخطاء.',
                'comment_en' => 'The best joinery workshop I collaborate with for executing complex executive office and walk-in closet designs.',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['client_name_ar' => $t['client_name_ar']], $t);
        }

        // 4. Custom Pages
        $customPages = [
            [
                'title_ar' => 'سياسة الضمان والجودة',
                'title_en' => 'Warranty & Quality Policy',
                'slug' => 'warranty-policy',
                'placement' => 'footer',
                'content_ar' => '<h3>ضمان الجودة الحرفية</h3><p>نضمن جميع أعمالنا الخشبية المصنعة في ورشتنا ضد عيوب التصنيع والتجميع لمدة تصل إلى 5 سنوات، ونلتزم بتقديم الدعم والصيانة لعملائنا الكرام لضمان استمرارية رونق الأثاث.</p>',
                'content_en' => '<h3>Craftsmanship Warranty</h3><p>We guarantee all bespoke woodwork manufactured in our workshop against manufacturing defects for up to 5 years, providing dedicated maintenance support.</p>',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title_ar' => 'الشروط والأحكام',
                'title_en' => 'Terms & Conditions',
                'slug' => 'terms-and-conditions',
                'placement' => 'footer',
                'content_ar' => '<h3>شروط التعاقد والتنفيذ</h3><p>تتم كافة أعمال التفصيل والتصنيع بناءً على المخططات والمقاسات المعتمدة من قبل العميل، ويتم الاتفاق على جدول زمني محدد للدفعات ومراحل التسليم والتركيب.</p>',
                'content_en' => '<h3>Execution Terms</h3><p>All bespoke woodwork is fabricated according to client-approved design drawings and dimensions, following clear timeline schedules.</p>',
                'is_active' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($customPages as $cp) {
            CustomPage::updateOrCreate(['slug' => $cp['slug']], $cp);
        }
    }
}
