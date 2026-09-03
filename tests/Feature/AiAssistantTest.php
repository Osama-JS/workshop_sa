<?php

namespace Tests\Feature;

use App\Models\AiDesignIdea;
use App\Models\AiFaq;
use App\Models\CustomOrder;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        app()->setLocale('ar');
        $this->seed();
    }

    public function test_ai_chat_init_returns_valid_session_chips_and_quota()
    {
        $response = $this->getJson(route('ai.chat.init'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'enabled',
                'session_token',
                'bot' => ['name', 'role', 'welcome_message'],
                'quick_chips',
                'messages',
                'quota' => ['max_limit', 'used_today', 'remaining', 'percent', 'is_exhausted'],
            ])
            ->assertJson(['success' => true]);

        $this->assertEquals(25, $response->json('quota.max_limit'));
        $this->assertEquals(25, $response->json('quota.remaining'));
    }

    public function test_ai_chat_first_message_includes_self_introduction()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'من أنت؟',
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('المستشار الذكي', $reply);
        $this->assertEmpty($response->json('suggested_ideas'));
    }

    public function test_ai_chat_answers_about_the_platform_richly_without_designs()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'من هي منصة أرتيزان للأعمال الخشبية؟',
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('أرتيزان للأعمال الخشبية', $reply);
        $this->assertStringContainsString('ساعات العمل', $reply);
        $this->assertEmpty($response->json('suggested_ideas'));
    }

    public function test_ai_chat_send_answers_bedroom_inquiries_with_suggested_ideas()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'أبحث عن أفكار وتصاميم لغرف نوم ماستر فخمة بمقاس كبير',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'reply',
                'suggested_ideas',
                'quota',
            ])
            ->assertJson(['success' => true]);

        $this->assertNotEmpty($response->json('suggested_ideas'));
        $this->assertEquals(24, $response->json('quota.remaining'));
    }

    public function test_ai_chat_maintains_conversational_continuity_across_turns()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        // Turn 1: User asks about bedrooms
        $res1 = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'أود تفصيل غرفة نوم ماستر فاخرة',
        ]);
        $res1->assertStatus(200);

        // Turn 2: User asks follow-up price question
        $res2 = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'كم سعرها التقديري؟',
        ]);
        $res2->assertStatus(200);
        $reply2 = $res2->json('reply');
        $this->assertStringContainsString('غرف النوم', $reply2);
        $this->assertStringContainsString('9,500', $reply2);
    }

    public function test_ai_chat_recognizes_user_name_and_avoids_repeated_welcome_greetings()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        // Turn 1: User introduces their name
        $res1 = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'اسمي أسامة',
        ]);
        $res1->assertStatus(200);
        $reply1 = $res1->json('reply');
        $this->assertStringContainsString('أستاذ أسامة', $reply1);

        // Turn 2: User sends a general thank you
        $res2 = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'شكراً لك',
        ]);
        $res2->assertStatus(200);
        $reply2 = $res2->json('reply');
        $this->assertStringContainsString('أستاذ أسامة', $reply2);
        $this->assertStringNotContainsString('أنا مستشار', $reply2);
    }

    public function test_ai_chat_answers_from_ai_faq_knowledge_base()
    {
        AiFaq::create([
            'question_ar' => 'ما هي مدة الضمان على المفصلات الألمانية؟',
            'question_en' => 'What is the warranty period on German hinges?',
            'answer_ar' => 'نقدم ضماناً لمدة 10 سنوات على كافة المفصلات الألمانية الأصلية من شركة بلوم.',
            'answer_en' => 'We offer a 10-year warranty on all original German Blum hinges.',
            'category' => 'warranty',
            'keywords' => 'مفصلات, بلوم, blum, كفالة',
            'is_active' => true,
        ]);

        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'ما هي مدة الضمان على المفصلات الألمانية؟',
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('10 سنوات', $reply);
        $this->assertStringContainsString('بلوم', $reply);
    }

    public function test_ai_chat_guardrail_declines_off_topic_questions()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'ما هي أفضل طريقة لطبخ البيتزا والباستا الإيطالية؟',
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('متخصص حصرياً', $reply);
    }

    public function test_ai_chat_order_tracking_lookup()
    {
        $order = CustomOrder::create([
            'order_number' => 'ORD-2026-9999',
            'customer_name' => 'سعد المنصور',
            'customer_phone' => '0555555555',
            'status' => 'in_progress',
            'description' => 'تفصيل طاولة اجتماعات',
        ]);

        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'أريد معرفة حالة طلبي ORD-2026-9999',
        ]);

        $response->assertStatus(200);
        $reply = $response->json('reply');
        $this->assertStringContainsString('ORD-2026-9999', $reply);
        $this->assertStringContainsString('سعد المنصور', $reply);
    }

    public function test_ai_chat_one_click_order_creation_from_design_idea()
    {
        $idea = AiDesignIdea::first();
        $this->assertNotNull($idea);

        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.order-idea'), [
            'session_token' => $token,
            'idea_id' => $idea->id,
            'customer_name' => 'خالد بن فيصل',
            'customer_phone' => '0512345678',
            'custom_dimensions' => '5x4 متر',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'order_number',
                'tracking_url',
            ]);

        $this->assertDatabaseHas('custom_orders', [
            'customer_name' => 'خالد بن فيصل',
            'customer_phone' => '0512345678',
        ]);
    }

    public function test_admin_can_crud_ai_faqs()
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        // 1. Create FAQ
        $createRes = $this->actingAs($superAdmin)->post(route('admin.ai-faqs.store'), [
            'question_ar' => 'هل تقومون بالتوصيل لجميع مناطق المملكة؟',
            'question_en' => 'Do you deliver across all Saudi regions?',
            'answer_ar' => 'نعم، نوفر الشحن والتركيب في الرياض وكافة مناطق المملكة.',
            'answer_en' => 'Yes, we deliver and install in Riyadh and all KSA regions.',
            'category' => 'services',
            'keywords' => 'توصيل, شحن, مناطق, خارج الرياض',
            'is_active' => '1',
            'sort_order' => 1,
        ]);

        $createRes->assertRedirect(route('admin.ai-faqs.index'));
        $this->assertDatabaseHas('ai_faqs', [
            'question_ar' => 'هل تقومون بالتوصيل لجميع مناطق المملكة؟',
        ]);

        $faq = AiFaq::where('question_ar', 'هل تقومون بالتوصيل لجميع مناطق المملكة؟')->first();

        // 2. Update FAQ
        $updateRes = $this->actingAs($superAdmin)->put(route('admin.ai-faqs.update', $faq->id), [
            'question_ar' => 'هل تقومون بالتوصيل والتركيب لجميع مناطق المملكة؟',
            'answer_ar' => 'نعم، نوفر أسطول توصيل وتركيب لكافة مدن المملكة مجاناً للطلبات الكبيرة.',
            'category' => 'services',
            'is_active' => '1',
            'sort_order' => 2,
        ]);

        $updateRes->assertRedirect(route('admin.ai-faqs.index'));
        $this->assertDatabaseHas('ai_faqs', [
            'id' => $faq->id,
            'question_ar' => 'هل تقومون بالتوصيل والتركيب لجميع مناطق المملكة؟',
        ]);

        // 3. Delete FAQ
        $deleteRes = $this->actingAs($superAdmin)->delete(route('admin.ai-faqs.destroy', $faq->id));
        $deleteRes->assertRedirect(route('admin.ai-faqs.index'));
        $this->assertDatabaseMissing('ai_faqs', [
            'id' => $faq->id,
        ]);
    }

    public function test_admin_can_crud_ai_design_ideas()
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        // 1. Create Idea
        $response = $this->actingAs($superAdmin)->post(route('admin.ai-ideas.store'), [
            'title_ar' => 'طاولة قهوة فاخرة من السنديان',
            'title_en' => 'Luxury Oak Coffee Table',
            'category' => 'tables',
            'description_ar' => 'طاولة قهوة خشبية بتشطيب مطفي مع رف سفلي',
            'wood_type' => 'خشب سنديان طبيعي',
            'dimensions' => '120×70 سم',
            'estimated_price_range' => '3,500 - 5,000 ريال',
            'is_active' => '1',
            'sort_order' => 10,
        ]);

        $response->assertRedirect(route('admin.ai-ideas.index'));
        $this->assertDatabaseHas('ai_design_ideas', [
            'title_ar' => 'طاولة قهوة فاخرة من السنديان',
            'category' => 'tables',
        ]);

        $idea = AiDesignIdea::where('title_ar', 'طاولة قهوة فاخرة من السنديان')->first();

        // 2. Update Idea
        $updateRes = $this->actingAs($superAdmin)->put(route('admin.ai-ideas.update', $idea->id), [
            'title_ar' => 'طاولة قهوة فاخرة من السنديان المعدل',
            'category' => 'tables',
            'wood_type' => 'خشب سنديان أمريكي',
            'is_active' => '1',
            'sort_order' => 5,
        ]);

        $updateRes->assertRedirect(route('admin.ai-ideas.index'));
        $this->assertDatabaseHas('ai_design_ideas', [
            'id' => $idea->id,
            'title_ar' => 'طاولة قهوة فاخرة من السنديان المعدل',
        ]);

        // 3. Delete Idea
        $deleteRes = $this->actingAs($superAdmin)->delete(route('admin.ai-ideas.destroy', $idea->id));
        $deleteRes->assertRedirect(route('admin.ai-ideas.index'));
        $this->assertDatabaseMissing('ai_design_ideas', [
            'id' => $idea->id,
        ]);
    }

    public function test_admin_can_view_ai_chat_logs()
    {
        $superAdmin = User::where('email', 'admin@artisanwood.sa')->first();

        // Trigger a conversation to create a log
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');
        $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'مرحباً، أود معرفة أسعار غرف النوم',
        ]);

        $indexRes = $this->actingAs($superAdmin)->get(route('admin.ai-logs.index'));
        $indexRes->assertStatus(200)
            ->assertSee(__('admin.ai_logs_list'));
    }

    public function test_ai_widget_is_hidden_when_disabled_in_settings()
    {
        // 1. When enabled, widget is present
        Setting::set('ai_enabled', '1');
        $resEnabled = $this->get(route('home'));
        $resEnabled->assertStatus(200)->assertSee('id="artisanAiWidget"', false);

        // 2. When disabled, widget is completely absent
        Setting::set('ai_enabled', '0');
        $resDisabled = $this->get(route('home'));
        $resDisabled->assertStatus(200)->assertDontSee('id="artisanAiWidget"', false);
    }
}
