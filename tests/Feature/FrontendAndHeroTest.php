<?php

namespace Tests\Feature;

use App\Models\CustomPage;
use App\Models\HeroSlide;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontendAndHeroTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_homepage_renders_successfully(): void
    {
        $response = $this->withSession(['locale' => 'ar'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('خدمات وأعمال النجارة المخصصة');
    }

    public function test_homepage_only_shows_featured_services(): void
    {
        $featuredService = Service::create([
            'title_ar' => 'خدمة مميزة خاصة',
            'title_en' => 'Special Featured Service',
            'slug' => 'special-featured-service',
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $nonFeaturedService = Service::create([
            'title_ar' => 'خدمة عادية غير مميزة',
            'title_en' => 'Regular Non-Featured Service',
            'slug' => 'regular-non-featured-service',
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 2,
        ]);

        $res = $this->withSession(['locale' => 'ar'])->get('/');
        $res->assertStatus(200);
        $res->assertSee('خدمة مميزة خاصة');
        $res->assertDontSee('خدمة عادية غير مميزة');

        // But in /services page, both should appear
        $servicesPage = $this->withSession(['locale' => 'ar'])->get(route('services.index'));
        $servicesPage->assertStatus(200);
        $servicesPage->assertSee('خدمة مميزة خاصة');
        $servicesPage->assertSee('خدمة عادية غير مميزة');
    }

    public function test_hero_slider_and_video_mode_switching(): void
    {
        // 1. Slider mode
        Setting::set('hero_type', 'slider');
        $response = $this->withSession(['locale' => 'ar'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('heroCarousel');

        // 2. Video mode
        Setting::set('hero_type', 'video');
        Setting::set('hero_video_url', 'https://example.com/video.mp4');
        $response = $this->withSession(['locale' => 'ar'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('video.mp4');
    }

    public function test_services_frontend_pages(): void
    {
        $service = Service::first();

        // 1. Services index
        $res = $this->withSession(['locale' => 'ar'])->get(route('services.index'));
        $res->assertStatus(200);
        $res->assertSee($service->title_ar);

        // 2. Service single page
        $res = $this->withSession(['locale' => 'ar'])->get(route('services.show', $service->slug));
        $res->assertStatus(200);
        $res->assertSee($service->title_ar);
    }

    public function test_portfolio_frontend_pages(): void
    {
        $portfolio = Portfolio::first();

        // 1. Portfolio index
        $res = $this->withSession(['locale' => 'ar'])->get(route('portfolio.index'));
        $res->assertStatus(200);
        $res->assertSee($portfolio->title_ar);

        // 2. Portfolio single page
        $res = $this->withSession(['locale' => 'ar'])->get(route('portfolio.show', $portfolio->slug));
        $res->assertStatus(200);
        $res->assertSee($portfolio->title_ar);
    }

    public function test_about_us_page(): void
    {
        $res = $this->withSession(['locale' => 'ar'])->get(route('about'));
        $res->assertStatus(200);
        $res->assertSee('من نحن');
        $res->assertSee('قيمنا');
    }

    public function test_custom_page_frontend(): void
    {
        $page = CustomPage::first();
        if (!$page) {
            $page = CustomPage::create([
                'title_ar' => 'سياسة الضمان الفاخر',
                'title_en' => 'Luxury Warranty Policy',
                'slug' => 'luxury-warranty-policy',
                'content_ar' => '<p>ضمان لمدة 5 سنوات.</p>',
                'content_en' => '<p>5 years warranty.</p>',
                'placement' => 'both',
                'is_active' => true,
            ]);
        }

        $res = $this->withSession(['locale' => 'ar'])->get(route('page.show', $page->slug));
        $res->assertStatus(200);
        $res->assertSee($page->title_ar);
    }

    public function test_hero_slides_admin_crud(): void
    {
        Storage::fake('public');
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        // 1. Index
        $res = $this->actingAs($superAdmin)->get(route('admin.hero-slides.index'));
        $res->assertStatus(200);

        // 2. Store
        $res = $this->actingAs($superAdmin)->post(route('admin.hero-slides.store'), [
            'title_ar' => 'تصاميم مكاتب تنفيذية خشب طبيعي',
            'title_en' => 'Executive Office Natural Wood Designs',
            'subtitle_ar' => 'فخامة وتميز',
            'subtitle_en' => 'Luxury & Prestige',
            'btn_text_ar' => 'اطلب تسعيرة',
            'btn_text_en' => 'Get Quote',
            'btn_url' => '#custom-order',
            'image' => UploadedFile::fake()->image('hero.jpg'),
            'is_active' => '1',
            'sort_order' => 5,
        ]);
        $res->assertRedirect(route('admin.hero-slides.index'));
        $this->assertDatabaseHas('hero_slides', ['title_ar' => 'تصاميم مكاتب تنفيذية خشب طبيعي']);

        $slide = HeroSlide::where('title_ar', 'تصاميم مكاتب تنفيذية خشب طبيعي')->first();

        // 3. Edit
        $res = $this->actingAs($superAdmin)->get(route('admin.hero-slides.edit', $slide->id));
        $res->assertStatus(200);

        // 4. Update
        $res = $this->actingAs($superAdmin)->put(route('admin.hero-slides.update', $slide->id), [
            'title_ar' => 'تصاميم مكاتب تنفيذية خشب طبيعي معدل',
            'title_en' => 'Executive Office Natural Wood Designs Updated',
            'is_active' => '1',
        ]);
        $res->assertRedirect(route('admin.hero-slides.index'));
        $this->assertDatabaseHas('hero_slides', ['title_ar' => 'تصاميم مكاتب تنفيذية خشب طبيعي معدل']);

        // 5. Delete
        $res = $this->actingAs($superAdmin)->delete(route('admin.hero-slides.destroy', $slide->id));
        $res->assertRedirect(route('admin.hero-slides.index'));
        $this->assertDatabaseMissing('hero_slides', ['id' => $slide->id]);
    }
}
