<?php

namespace Tests\Feature;

use App\Models\AboutSection;
use App\Models\CustomPage;
use App\Models\Portfolio;
use App\Models\PortfolioAttachment;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected function getSuperAdmin(): User
    {
        return User::where('email', 'admin@artisanwood.sa')->first();
    }

    public function test_services_crud_workflow(): void
    {
        Storage::fake('public');
        $admin = $this->getSuperAdmin();

        // 1. Index
        $res = $this->actingAs($admin)->get(route('admin.services.index'));
        $res->assertStatus(200);

        // 2. Create Page
        $res = $this->actingAs($admin)->get(route('admin.services.create'));
        $res->assertStatus(200);

        // 3. Store
        $res = $this->actingAs($admin)->post(route('admin.services.store'), [
            'title_ar' => 'تفصيل مكاتب خشب فاخرة',
            'title_en' => 'Luxury Wood Office Desks',
            'short_desc_ar' => 'تصاميم مكتبية راقية',
            'short_desc_en' => 'Bespoke executive desks',
            'content_ar' => '<p>محتوى الخدمة بالعربي</p>',
            'content_en' => '<p>Service content in English</p>',
            'icon' => 'briefcase',
            'is_active' => '1',
            'is_featured' => '1',
            'sort_order' => 1,
            'image' => UploadedFile::fake()->image('service.jpg')
        ]);
        $res->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['title_ar' => 'تفصيل مكاتب خشب فاخرة']);

        $service = Service::where('title_ar', 'تفصيل مكاتب خشب فاخرة')->first();

        // 4. Edit
        $res = $this->actingAs($admin)->get(route('admin.services.edit', $service->id));
        $res->assertStatus(200);

        // 5. Update
        $res = $this->actingAs($admin)->put(route('admin.services.update', $service->id), [
            'title_ar' => 'تفصيل مكاتب خشبية معدل',
            'title_en' => 'Luxury Wood Office Desks Updated',
            'is_active' => '1',
        ]);
        $res->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['title_ar' => 'تفصيل مكاتب خشبية معدل']);

        // 6. Destroy
        $res = $this->actingAs($admin)->delete(route('admin.services.destroy', $service->id));
        $res->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }

    public function test_portfolio_and_attachments_workflow(): void
    {
        Storage::fake('public');
        $admin = $this->getSuperAdmin();
        $service = Service::first();

        // 1. Index
        $res = $this->actingAs($admin)->get(route('admin.portfolios.index'));
        $res->assertStatus(200);

        // 2. Store with attachments
        $res = $this->actingAs($admin)->post(route('admin.portfolios.store'), [
            'title_ar' => 'مشروع فيلا حي النرجس',
            'title_en' => 'Al-Narjis Villa Project',
            'service_id' => $service->id,
            'client_name' => 'شركة ريادة العقارية',
            'location' => 'الرياض',
            'main_image' => UploadedFile::fake()->image('cover.jpg'),
            'gallery_images' => [
                UploadedFile::fake()->image('pic1.jpg'),
                UploadedFile::fake()->image('pic2.jpg')
            ],
            'pdf_documents' => [
                UploadedFile::fake()->create('blueprint.pdf', 100, 'application/pdf')
            ],
            'is_active' => '1',
            'is_featured' => '1'
        ]);
        $res->assertRedirect(route('admin.portfolios.index'));

        $portfolio = Portfolio::where('title_ar', 'مشروع فيلا حي النرجس')->first();
        $this->assertNotNull($portfolio);
        $this->assertEquals(3, $portfolio->attachments()->count()); // 2 images + 1 pdf

        // 3. Delete single attachment
        $attachment = $portfolio->attachments()->first();
        $res = $this->actingAs($admin)->delete(route('admin.portfolios.attachments.destroy', $attachment->id));
        $res->assertRedirect();
        $this->assertDatabaseMissing('portfolio_attachments', ['id' => $attachment->id]);
    }

    public function test_custom_pages_crud_workflow(): void
    {
        $admin = $this->getSuperAdmin();

        // 1. Store
        $res = $this->actingAs($admin)->post(route('admin.pages.store'), [
            'title_ar' => 'سياسة الخصوصية والشروط',
            'title_en' => 'Privacy Policy & Terms',
            'placement' => 'footer',
            'content_ar' => '<p>شروط الاستخدام...</p>',
            'content_en' => '<p>Terms of service...</p>',
            'is_active' => '1',
        ]);
        $res->assertRedirect(route('admin.pages.index'));

        $page = CustomPage::where('title_ar', 'سياسة الخصوصية والشروط')->first();
        $this->assertNotNull($page);
        $this->assertEquals('footer', $page->placement);

        // 2. Update
        $res = $this->actingAs($admin)->put(route('admin.pages.update', $page->id), [
            'title_ar' => 'سياسة الخصوصية المحدثة',
            'title_en' => 'Updated Privacy Policy',
            'placement' => 'both',
            'is_active' => '1',
        ]);
        $res->assertRedirect(route('admin.pages.index'));
        $this->assertEquals('both', $page->fresh()->placement);
    }

    public function test_about_us_update(): void
    {
        $admin = $this->getSuperAdmin();

        $res = $this->actingAs($admin)->put(route('admin.about.update'), [
            'about' => [
                'title_ar' => 'من نحن - ورشة أرتيزان الفاخرة',
                'title_en' => 'About Us - Artisan Luxury Woodwork',
                'subtitle_ar' => 'صرح سعودي رائد في الديكور الخشبي',
                'subtitle_en' => 'Leading Saudi Woodwork Powerhouse',
                'content_ar' => '<p>نبذة تعريفية شاملة...</p>',
                'content_en' => '<p>Comprehensive overview...</p>',
            ],
            'story' => [
                'title_ar' => 'قصة ورشتنا الفاخرة',
                'title_en' => 'Our Luxury Workshop Story',
                'content_ar' => 'بدأنا منذ أكثر من عقد...',
                'content_en' => 'We started over a decade ago...',
            ],
            'vision' => [
                'title_ar' => 'رؤيتنا نحو العالمية',
                'title_en' => 'Our Global Vision',
                'content_ar' => 'تقديم أرقى التحف الخشبية',
                'content_en' => 'Delivering premium woodwork',
            ],
            'values' => [
                'title_ar' => 'قيمنا ومبادئنا الراسخة',
                'title_en' => 'Our Enduring Core Values',
                'subtitle_ar' => 'المبادئ السامية التي تحكم عملنا',
                'subtitle_en' => 'Our Guiding Principles',
                'items' => [
                    [
                        'title_ar' => 'الإتقان والجودة',
                        'title_en' => 'Uncompromising Quality',
                        'icon' => 'fa-solid fa-gem',
                        'desc_ar' => 'اختيار أفضل الأخشاب',
                        'desc_en' => 'Selecting finest woods',
                    ],
                    [
                        'title_ar' => 'الحرفية والابتكار',
                        'title_en' => 'Craftsmanship & Innovation',
                        'icon' => 'fa-solid fa-wand-magic-sparkles',
                        'desc_ar' => 'تصاميم يدوية ورقمية',
                        'desc_en' => 'Artisanal & CNC designs',
                    ]
                ]
            ],
            'stats' => [
                'counters' => [
                    ['number' => '20+', 'label_ar' => 'سنة من الخبرة', 'label_en' => 'Years Experience'],
                    ['number' => '500+', 'label_ar' => 'مشروع منجز', 'label_en' => 'Completed Projects']
                ]
            ]
        ]);

        $res->assertRedirect();

        $about = AboutSection::where('section_key', 'about')->first();
        $this->assertEquals('من نحن - ورشة أرتيزان الفاخرة', $about->title_ar);

        $story = AboutSection::where('section_key', 'story')->first();
        $this->assertEquals('قصة ورشتنا الفاخرة', $story->title_ar);

        $values = AboutSection::where('section_key', 'values')->first();
        $this->assertEquals('قيمنا ومبادئنا الراسخة', $values->title_ar);
        $this->assertCount(2, $values->meta_data);

        $stats = AboutSection::where('section_key', 'stats')->first();
        $this->assertCount(2, $stats->meta_data);
    }

    public function test_testimonials_crud_workflow(): void
    {
        $admin = $this->getSuperAdmin();

        $res = $this->actingAs($admin)->post(route('admin.testimonials.store'), [
            'client_name_ar' => 'سلطان القحطاني',
            'client_name_en' => 'Sultan Al-Qahtani',
            'position_ar' => 'مالك قصر خاص',
            'position_en' => 'Private Residence Owner',
            'rating' => 5,
            'comment_ar' => 'شغل ممتاز ودقة متناهية في المواعيد وجودة الخشب رائعة.',
            'comment_en' => 'Exceptional quality and timely delivery.',
            'is_active' => '1',
        ]);

        $res->assertRedirect(route('admin.testimonials.index'));
        $this->assertDatabaseHas('testimonials', ['client_name_ar' => 'سلطان القحطاني', 'rating' => 5]);
    }
}
