<?php

namespace Tests\Feature;

use App\Models\AiDesignIdea;
use App\Models\CustomOrder;
use App\Models\Role;
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
        $this->seed();
    }

    public function test_ai_chat_init_returns_valid_session_and_chips()
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
            ])
            ->assertJson(['success' => true]);
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
            ])
            ->assertJson(['success' => true]);

        $this->assertNotEmpty($response->json('suggested_ideas'));
    }

    public function test_ai_chat_guardrail_declines_off_topic_questions()
    {
        $sessionRes = $this->getJson(route('ai.chat.init'));
        $token = $sessionRes->json('session_token');

        $response = $this->postJson(route('ai.chat.send'), [
            'session_token' => $token,
            'message' => 'ما هي أفضل وصفة لطبخ البيتزا الإيطالية؟',
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
}
