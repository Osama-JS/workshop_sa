<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\AiDesignIdea;
use App\Models\CustomOrder;
use App\Models\Setting;
use App\Services\GeminiAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    protected GeminiAiService $aiService;

    public function __construct(GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Initialize or resume AI Chat session with metadata and quick prompt chips.
     */
    public function init(Request $request): JsonResponse
    {
        $sessionToken = $request->input('session_token') ?: (string) Str::uuid();

        $session = AiChatSession::firstOrCreate(
            ['session_token' => $sessionToken],
            ['visitor_ip' => $request->ip()]
        );

        $locale = app()->getLocale();
        $botName = Setting::get("ai_bot_name_{$locale}", 'مستشار أرتيزان الذكي');
        $botRole = Setting::get("ai_bot_role_{$locale}", 'مستشار تفصيل الأثاث والأعمال الخشبية');
        $welcomeMsg = Setting::get("ai_welcome_msg_{$locale}", 'أهلاً بك في ورشة أرتيزان للأعمال الخشبية الفاخرة! 🪵✨ كيف يمكنني مساعدتك اليوم؟');
        $aiEnabled = (bool) Setting::get('ai_enabled', '1');

        $quickChips = [
            $locale === 'ar' ? '🛏️ أفكار لغرف نوم ماستر' : '🛏️ Master Bedroom Ideas',
            $locale === 'ar' ? '🏢 تصميم مكاتب تنفيذية' : '🏢 Executive Office Desks',
            $locale === 'ar' ? '🎪 تفصيل بوث لمعرض' : '🎪 Exhibition Booths',
            $locale === 'ar' ? '🪵 ما الفرق بين خشب البلوط والجوز؟' : '🪵 Oak vs Walnut Wood',
            $locale === 'ar' ? '🔍 تتبع حالة طلبي' : '🔍 Track My Order',
        ];

        // Fetch previous messages for this session
        $messages = $session->messages()->orderBy('created_at', 'asc')->get()->map(function ($msg) {
            return [
                'id' => $msg->id,
                'sender' => $msg->sender,
                'message' => $msg->message,
                'image_url' => $msg->image_path ? asset('storage/' . $msg->image_path) : null,
                'metadata' => $msg->metadata,
                'created_at' => $msg->created_at->format('H:i'),
            ];
        });

        $quota = $session->getDailyQuotaInfo();

        return response()->json([
            'success' => true,
            'enabled' => $aiEnabled,
            'session_token' => $session->session_token,
            'bot' => [
                'name' => $botName,
                'role' => $botRole,
                'welcome_message' => $welcomeMsg,
            ],
            'quick_chips' => $quickChips,
            'messages' => $messages,
            'quota' => $quota,
        ]);
    }

    /**
     * Send user message to AI Assistant and receive streaming/instant reply.
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required_without:image|nullable|string|max:2000',
            'session_token' => 'required|string',
            'image' => 'nullable|image|max:10240', // max 10MB
        ]);

        $session = AiChatSession::firstOrCreate(
            ['session_token' => $request->session_token],
            ['visitor_ip' => $request->ip()]
        );

        $quota = $session->getDailyQuotaInfo();

        if ($quota['is_exhausted']) {
            $exhaustedMsg = app()->getLocale() === 'ar'
                ? 'لقد استهلكت رصيد الاستفسارات اليومي المجاني (' . $quota['max_limit'] . ' استفسار). يسعد فريقنا البشري خدمتك ومتابعة طلبك فوراً عبر واتساب أو الاتصال المباشر!'
                : 'You have reached today\'s daily inquiry limit (' . $quota['max_limit'] . ' messages). Our human team is delighted to assist you directly via WhatsApp or phone!';
            
            return response()->json([
                'success' => true,
                'reply' => $exhaustedMsg,
                'suggested_ideas' => [],
                'order' => null,
                'quota' => $quota,
                'created_at' => now()->format('H:i'),
            ]);
        }

        $uploadedImagePath = null;
        if ($request->hasFile('image')) {
            $uploadedImagePath = $request->file('image')->store('ai_chat_uploads', 'public');
        }

        $userText = $request->input('message') ?: ($uploadedImagePath ? (app()->getLocale() === 'ar' ? 'أود الاستفسار عن تفصيل هذا التصميم الخشبي المرفق' : 'I would like to inquire about this woodwork design') : '');

        if ((string) Setting::get('ai_enabled', '1') !== '1') {
            return response()->json([
                'success' => false,
                'reply' => app()->getLocale() === 'ar' 
                    ? 'المساعد الذكي معطل مؤقتاً لأعمال الصيانة والتحديث. يرجى التواصل معنا عبر واتساب أو نموذج الطلبات المخصصة.' 
                    : 'The AI Assistant is temporarily deactivated for maintenance. Please contact us via WhatsApp or custom orders form.',
                'suggested_ideas' => [],
                'quota' => $quota,
            ]);
        }

        // 1. Save User Message
        AiChatMessage::create([
            'ai_chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userText,
            'image_path' => $uploadedImagePath,
        ]);

        // 2. Call Gemini AI Service
        $result = $this->aiService->chat($session, $userText, $uploadedImagePath);

        // 3. Save Assistant Message
        $assistantMsg = AiChatMessage::create([
            'ai_chat_session_id' => $session->id,
            'sender' => 'assistant',
            'message' => $result['reply'],
            'metadata' => [
                'suggested_ideas' => $result['suggested_ideas'] ?? [],
                'order' => $result['order'] ?? null,
            ],
        ]);

        $updatedQuota = $session->getDailyQuotaInfo();

        return response()->json([
            'success' => true,
            'reply' => $result['reply'],
            'suggested_ideas' => $result['suggested_ideas'] ?? [],
            'order' => $result['order'] ?? null,
            'message_id' => $assistantMsg->id,
            'created_at' => now()->format('H:i'),
            'quota' => $updatedQuota,
        ]);
    }

    /**
     * Quick 1-click custom order submission from suggested Pinterest/Design card in chat.
     */
    public function orderFromIdea(Request $request): JsonResponse
    {
        $request->validate([
            'session_token' => 'required|string',
            'idea_id' => 'required|exists:ai_design_ideas,id',
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:30',
            'custom_dimensions' => 'nullable|string|max:150',
            'custom_notes' => 'nullable|string|max:1000',
        ]);

        $session = AiChatSession::firstOrCreate(
            ['session_token' => $request->session_token],
            ['visitor_ip' => $request->ip()]
        );

        $idea = AiDesignIdea::findOrFail($request->idea_id);

        $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(CustomOrder::count() + 1, 4, '0', STR_PAD_LEFT);

        $order = CustomOrder::create([
            'order_number' => $orderNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_whatsapp' => $request->customer_phone,
            'customer_email' => null,
            'service_id' => null,
            'wood_type' => $idea->wood_type ?: 'خشب حسب مواصفات التصميم',
            'dimensions' => $request->custom_dimensions ?: ($idea->dimensions ?: 'حسب أبعاد التصميم'),
            'budget_range' => $idea->estimated_price_range ?: 'سيتم تحديدها بعد المعاينة',
            'description' => "طلب تفصيل بناءً على فكرة تصميم من الذكاء الاصطناعي:\nالتصميم: {$idea->title_ar}\nرابط بنترست: {$idea->pinterest_url}\nالمقاسات: " . ($request->custom_dimensions ?: $idea->dimensions) . "\nملاحظات العميل: " . ($request->custom_notes ?: 'لا توجد'),
            'status' => 'pending',
        ]);

        $session->update([
            'order_id' => $order->id,
            'user_name' => $request->customer_name,
            'user_phone' => $request->customer_phone,
        ]);

        // Post system confirmation in the chat session
        $confirmationText = "🎉 **تم تسجيل طلبك بنجاح للتصميم (" . $idea->title . ")**\nرقم التتبع: `{$order->order_number}`\nسيتواصل معكم مهندسونا في أقرب وقت عبر الواتساب لتنسيق موعد أخذ المقاسات الدقيقة وبدء التنفيذ.";

        AiChatMessage::create([
            'ai_chat_session_id' => $session->id,
            'sender' => 'assistant',
            'message' => $confirmationText,
            'metadata' => [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'tracking_url' => route('order.track', $order->order_number),
                ]
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => $confirmationText,
            'order_number' => $order->order_number,
            'tracking_url' => route('order.track', $order->order_number),
        ]);
    }

    /**
     * Clear current chat history for the user session.
     */
    public function clear(Request $request): JsonResponse
    {
        if ($request->session_token) {
            $session = AiChatSession::where('session_token', $request->session_token)->first();
            if ($session) {
                $session->messages()->delete();
                $session->update(['total_messages' => 0]);
            }
        }

        return response()->json(['success' => true]);
    }
}
