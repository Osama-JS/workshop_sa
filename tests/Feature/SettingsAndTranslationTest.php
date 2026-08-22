<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsAndTranslationTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_settings_page_renders_successfully_for_super_admin(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->actingAs($superAdmin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('بيانات المنشأة والهوية العامة');
    }

    public function test_updating_settings_works_and_persists_in_database(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->actingAs($superAdmin)->put('/admin/settings', [
            'group' => 'identity',
            'site_name_ar' => 'ورشة الفخامة الخشبية المعدلة',
            'site_name_en' => 'Luxury Woodcraft Updated',
            'primary_color' => '#990000',
        ]);

        $response->assertRedirect();
        $this->assertEquals('ورشة الفخامة الخشبية المعدلة', Setting::get('site_name_ar'));
        $this->assertEquals('#990000', Setting::get('primary_color'));
    }

    public function test_translation_endpoint_returns_json_response(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->actingAs($superAdmin)->postJson('/admin/translate', [
            'text' => 'غرفة نوم ماستر خشب طبيعي',
            'from' => 'ar',
            'to' => 'en',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'original', 'translated']);
        $this->assertTrue($response->json('success'));
    }

    public function test_send_test_mail_endpoint(): void
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        $response = $this->actingAs($superAdmin)->postJson('/admin/settings/send-test-mail', [
            'test_email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }
}
