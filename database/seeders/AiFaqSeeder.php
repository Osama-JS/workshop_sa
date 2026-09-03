<?php

namespace Database\Seeders;

use App\Models\AiFaq;
use Illuminate\Database\Seeder;

class AiFaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question_ar' => 'ما هي أنواع الأخشاب المستخدمة في تصنيع الأثاث والديكور لديكم؟',
                'question_en' => 'What types of wood do you use in furniture and decor manufacturing?',
                'answer_ar' => 'نعتمد حصرياً على أجود أنواع الأخشاب الطبيعية المستوردة والمعالجة حرارياً، ومن أبرزها: خشب البلوط الأمريكي والأوروبي (Oak) لصلابته ومقاومته العالية، خشب الجوز الأمريكي (Walnut) لمظهره الفاخر وعروقه الطبيعية، خشب الزان الألماني (Beech) للهياكل القوية، وخشب التيك (Teak) للمقاومة الفائقة للرطوبة، بالإضافة إلى خشب الـ MDF المقاوم للرطوبة والمكسو بقشور الأخشاب الطبيعية للتكسيات الجدارية.',
                'answer_en' => 'We exclusively use premium imported and kiln-dried natural hardwoods: American & European Oak, American Walnut, German Beech, and Water-resistant Teak, alongside high-density moisture-resistant MDF veneered with genuine wood slices for wall paneling.',
                'category' => 'materials',
                'keywords' => 'خشب, بلوط, جوز, زان, تيك, طبيعي, قشرة, mdf, مواد',
                'sort_order' => 1,
            ],
            [
                'question_ar' => 'كم تستغرق مدة تفصيل وتصنيع الطلبات المخصصة؟',
                'question_en' => 'How long does custom manufacturing take?',
                'answer_ar' => 'تتراوح مدة التنفيذ عادةً بين 10 إلى 25 يوم عمل بحسب حجم المشروع ودقة التفاصيل الهندسية. تبدأ المدة رسمياً بعد اعتماد المخطط التنفيذي ثلاثي الأبعاد (3D) واختيار عينات الأخشاب والدهانات.',
                'answer_en' => 'Manufacturing typically takes 10 to 25 business days depending on project scope and technical specifications, starting once 3D shop drawings and material finishes are approved.',
                'category' => 'orders',
                'keywords' => 'مدة, وقت, تسليم, كم يستغرق, متى يجهز, استلام',
                'sort_order' => 2,
            ],
            [
                'question_ar' => 'هل تقدمون ضماناً على الأثاث والأعمال الخشبية المنفذة؟',
                'question_en' => 'Do you provide a warranty on custom furniture and woodwork?',
                'answer_ar' => 'نعم، نقدم ضماناً شاملاً يصل إلى 5 سنوات على الهياكل الخشبية وجودة التصنيع، وضماناً ضد التقوس أو عيوب الصنعة، بالإضافة إلى ضمان الوكيل المعتمد على المفصلات والإكسسوارات الهيدروليكية (مثل Blum و Hettich الألمانية).',
                'answer_en' => 'Yes, we provide a comprehensive warranty up to 5 years against manufacturing defects and structural warping, along with official manufacturer warranties on German soft-close fittings (Blum & Hettich).',
                'category' => 'warranty',
                'keywords' => 'ضمان, كفالة, جودة, صيانة, خربان, بلوم, blum',
                'sort_order' => 3,
            ],
            [
                'question_ar' => 'هل توفرون خدمة المعاينة ورفع المقاسات الميدانية؟',
                'question_en' => 'Do you provide on-site measurement and consultation?',
                'answer_ar' => 'نعم، بعد التواصل المبدئي يقوم مهندسونا المتخصصون بزيارة موقع العميل لرفع المقاسات بدقة بأجهزة الليزر وتقديم الاستشارات الفنية واقتراح أفضل توزيع للأثاث والديكور.',
                'answer_en' => 'Yes, our engineering team conducts on-site laser measurements and provides technical consultations for optimal space planning and custom woodwork layout.',
                'category' => 'services',
                'keywords' => 'معاينة, زيارة, مقاسات, ليزر, موقع, مهندس',
                'sort_order' => 4,
            ],
            [
                'question_ar' => 'كيف يمكنني تتبع حالة طلبي بعد التعاقد؟',
                'question_en' => 'How can I track my custom order progress?',
                'answer_ar' => 'يمكنك تتبع مرحلة طلبك مباشرة من خلال صفحة "تتبع الطلب" في الموقع عبر إدخال كود التتبع الخاص بك (مثال: ORD-2026-0001)، أو كتابة رقم الطلب هنا في المحادثة وسأقوم بجلب تفاصيل وحالة تصنيع طلبك فوراً!',
                'answer_en' => 'You can track your order anytime via our "Track Order" page using your reference code (e.g. ORD-2026-0001), or simply share your order number right here in this chat to see live manufacturing updates.',
                'category' => 'orders',
                'keywords' => 'تتبع, حالة, رقم الطلب, طلب, وين وصل',
                'sort_order' => 5,
            ],
            [
                'question_ar' => 'هل تقومون بتنفيذ أجنحة وبوثات المعارض والفعاليات؟',
                'question_en' => 'Do you build custom exhibition booths and event pavilions?',
                'answer_ar' => 'نعم وباحترافية عالية، نمتلك قسماً متخصصاً في تصنيع وتركيب بوثات المعارض في مركز الرياض الدولي للمعارض، واجهة الرياض، ومراكز المعارض بالمملكة، بما يشمل الهياكل الخشبية ثلاثية الأبعاد، منصات الاستقبال، وإضاءات LED.',
                'answer_en' => 'Yes, we have a specialized division for custom exhibition stands and pavilions across major Saudi convention centers, providing CNC structures, reception counters, and integrated LED displays.',
                'category' => 'booths',
                'keywords' => 'بوث, معرض, معارض, جناح, بارامترك, فعاليات',
                'sort_order' => 6,
            ],
            [
                'question_ar' => 'ما هي آلية الدفع والدفعات المعتمدة؟',
                'question_en' => 'What are the accepted payment methods and milestones?',
                'answer_ar' => 'نعتمد نظام الدفعات المرن: دفعة أولى مقدمة 50% عند توقيع العقد واعتماد المخططات للبدء في تجهيز الأخشاب وقصها، 40% عند الانتهاء من التصنيع وقبل خروج العمل للدهان والتشطيب، و10% المتبقية بعد التسليم والتركيب النهائي في موقعك.',
                'answer_en' => 'We adopt a structured milestone schedule: 50% upon contract signing and 3D approval, 40% upon fabrication before finishing, and the remaining 10% upon final delivery and installation.',
                'category' => 'pricing',
                'keywords' => 'دفع, دفعة, اقساط, كاش, تحويل, عربون, سعر',
                'sort_order' => 7,
            ],
        ];

        foreach ($faqs as $faq) {
            AiFaq::updateOrCreate(
                ['question_ar' => $faq['question_ar']],
                $faq
            );
        }
    }
}
