<?php

namespace App\Services;

use App\Models\AiChatSession;
use App\Models\AiDesignIdea;
use App\Models\CustomOrder;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected string $apiKey;
    protected string $model;
    protected float $temperature;
    protected int $maxTokens;
    protected string $locale;

    public function __construct()
    {
        $this->apiKey = Setting::get('ai_gemini_api_key', env('GEMINI_API_KEY', '')) ?: '';
        $this->model = Setting::get('ai_model', 'gemini-1.5-flash');
        $this->temperature = (float) Setting::get('ai_temperature', '0.7');
        $this->maxTokens = (int) Setting::get('ai_max_tokens', '1000');
        $this->locale = app()->getLocale();
    }

    /**
     * Send message to Gemini and get response.
     */
    public function chat(AiChatSession $session, string $userMessage, ?string $uploadedImagePath = null): array
    {
        // 1. Check for in-chat order tracking code pattern
        $trackingOrderInfo = $this->lookupTrackingInfoIfPresent($userMessage);

        // 2. Build system instructions with live workshop knowledge
        $systemInstruction = $this->buildSystemInstruction($trackingOrderInfo);

        // 3. Build contents array with conversation history
        $contents = $this->buildConversationContents($session, $userMessage, $uploadedImagePath);

        // 4. If API key is available, attempt calling Gemini API
        if (!empty($this->apiKey)) {
            try {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

                $payload = [
                    'contents' => $contents,
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $this->temperature,
                        'maxOutputTokens' => $this->maxTokens,
                    ]
                ];

                $response = Http::timeout(25)->post($endpoint, $payload);

                if ($response->successful()) {
                    $json = $response->json();
                    $rawText = $json['candidates'][0]['content']['parts'][0]['text'] ?? '';

                    if (!empty($rawText)) {
                        return $this->processAssistantResponse($session, $rawText);
                    }
                } else {
                    Log::warning('Gemini API Response Error', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Exception: ' . $e->getMessage());
            }
        }

        // 5. Intelligent Fallback Engine (for offline local development or when API key is pending)
        return $this->generateFallbackResponse($session, $userMessage, $trackingOrderInfo);
    }

    /**
     * Build system instructions embedding workshop knowledge and strict guardrails.
     */
    protected function buildSystemInstruction(?string $trackingOrderInfo = null): string
    {
        $botName = Setting::get("ai_bot_name_{$this->locale}", 'مستشار أرتيزان الذكي');
        $botRole = Setting::get("ai_bot_role_{$this->locale}", 'مستشار تفصيل الأثاث والأعمال الخشبية');
        $basePrompt = Setting::get("ai_system_prompt_{$this->locale}", '');

        // Fetch Services Knowledge
        $services = Service::where('is_active', true)->get()->map(function ($s) {
            return "- [الخدمة #{$s->id}]: {$s->title} ({$s->short_desc})";
        })->implode("\n");

        // Fetch Design Ideas & Pinterest Inspirations
        $ideas = AiDesignIdea::where('is_active', true)->orderBy('sort_order')->take(10)->get()->map(function ($idea) {
            return "- [تصميم #{$idea->id}]: {$idea->title} | التصنيف: {$idea->category} | نوع الخشب: {$idea->wood_type} | الأبعاد: {$idea->dimensions} | السعر التقديري: {$idea->estimated_price_range} | رابط بنترست: {$idea->pinterest_url} | الوصف: {$idea->description}";
        })->implode("\n");

        $workshopPhone = Setting::get('phone', '+966500000000');
        $workshopWa = Setting::get('whatsapp', '+966500000000');

        $prompt = "{$basePrompt}\n\n";
        $prompt .= "اسمك: {$botName}\nوظيفتك: {$botRole}\n\n";
        $prompt .= "=== بيانات وخدمات ورشة أرتيزان ===\n{$services}\n\n";
        $prompt .= "=== بنك الأفكار والتصاميم المعتمدة (Pinterest & Workshop Inspirations) ===\n{$ideas}\n\n";
        $prompt .= "=== بيانات التواصل ===\nالهاتف: {$workshopPhone} | واتساب: {$workshopWa}\n\n";

        if ($trackingOrderInfo) {
            $prompt .= "=== بيانات الطلب المستفسر عنه في النظام حالياً ===\n{$trackingOrderInfo}\n\n";
        }

        $prompt .= "=== تعليمات خاصة بتوليد الأوامر التفاعلية (CRITICAL INSTRUCTIONS) ===\n";
        $prompt .= "1. عندما تقترح تصميماً من بنك الأفكار على العميل، قم بإدراج كود التصميم في نص إجابتك بهذه الصيغة بدقة: [DESIGN_CARD:id] (مثال: [DESIGN_CARD:1]) لتقوم الواجهة بعرض صورة التصميم ورابط بنترست بشكل بطاقة جذابة.\n";
        $prompt .= "2. عندما يبدي العميل رغبته في تفصيل تصميم أو طلب تسعيرة ويزودك ببياناته (الاسم ورقم الجوال والمقاسات)، قم بتأكيد استلام الطلب وأدرج كود إنشاء الطلب في نهاية إجابتك بهذه الصيغة بدقة:\n";
        $prompt .= "[ACTION:CREATE_ORDER|name=اسم_العميل|phone=رقم_الجوال|category=نوع_العمل|wood=نوع_الخشب|dimensions=المقاسات|notes=الملاحظات]\n";
        $prompt .= "3. حافظ على أسلوب راقٍ، مشجع، محترف، ومتخصص في الأخشاب. وارفض أي موضوع خارج الأثاث والنجارة والديكور بلباقة تامة.\n";

        return $prompt;
    }

    /**
     * Build the conversation contents array for Gemini API.
     */
    protected function buildConversationContents(AiChatSession $session, string $userMessage, ?string $uploadedImagePath = null): array
    {
        $contents = [];

        // Load previous 8 messages for context window
        $recentMessages = $session->messages()->orderBy('created_at', 'desc')->take(8)->get()->reverse();

        foreach ($recentMessages as $msg) {
            $role = $msg->sender === 'user' ? 'user' : 'model';
            $parts = [['text' => $msg->message]];

            if ($msg->image_path && file_exists(public_path('storage/' . $msg->image_path))) {
                $mimeType = mime_content_type(public_path('storage/' . $msg->image_path)) ?: 'image/jpeg';
                $base64 = base64_encode(file_get_contents(public_path('storage/' . $msg->image_path)));
                $parts[] = [
                    'inlineData' => [
                        'mimeType' => $mimeType,
                        'data' => $base64,
                    ]
                ];
            }

            $contents[] = [
                'role' => $role,
                'parts' => $parts,
            ];
        }

        // Append current message
        $currentParts = [['text' => $userMessage]];
        if ($uploadedImagePath && file_exists(public_path('storage/' . $uploadedImagePath))) {
            $mimeType = mime_content_type(public_path('storage/' . $uploadedImagePath)) ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents(public_path('storage/' . $uploadedImagePath)));
            $currentParts[] = [
                'inlineData' => [
                    'mimeType' => $mimeType,
                    'data' => $base64,
                ]
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => $currentParts,
        ];

        return $contents;
    }

    /**
     * Process raw assistant response, handle actions, and extract card IDs.
     */
    protected function processAssistantResponse(AiChatSession $session, string $rawText): array
    {
        $createdOrder = null;
        $suggestedIdeas = [];

        // 1. Check for [ACTION:CREATE_ORDER|...] trigger
        if (preg_match('/\[ACTION:CREATE_ORDER\|(.*?)\]/s', $rawText, $matches)) {
            $actionParamsString = $matches[1];
            $params = [];
            foreach (explode('|', $actionParamsString) as $pair) {
                $parts = explode('=', $pair, 2);
                if (count($parts) === 2) {
                    $params[trim($parts[0])] = trim($parts[1]);
                }
            }

            $customerName = $params['name'] ?? 'عميل الشات الذكي';
            $customerPhone = $params['phone'] ?? '0500000000';
            $serviceName = $params['category'] ?? 'طلب عبر المساعد الذكي';
            $woodType = $params['wood'] ?? 'خشب حسب ترشيح الورشة';
            $dimensions = $params['dimensions'] ?? 'حسب المعاينة والمخطط';
            $notes = $params['notes'] ?? 'تم إنشاء الطلب تلقائياً بواسطة مساعد الذكاء الاصطناعي';

            // Generate unique Order Number
            $orderNumber = 'ORD-' . date('Y') . '-' . str_pad(CustomOrder::count() + 1, 4, '0', STR_PAD_LEFT);

            $order = CustomOrder::create([
                'order_number' => $orderNumber,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_whatsapp' => $customerPhone,
                'customer_email' => null,
                'service_id' => null,
                'wood_type' => $woodType,
                'dimensions' => $dimensions,
                'budget_range' => 'سيتم تحديدها بعد المعاينة',
                'description' => "تفاصيل الطلب من المساعد الذكي:\nنوع العمل: {$serviceName}\nالمقاسات: {$dimensions}\nالخشب: {$woodType}\nملاحظات: {$notes}",
                'status' => 'pending',
            ]);

            $session->update([
                'order_id' => $order->id,
                'user_name' => $customerName,
                'user_phone' => $customerPhone,
            ]);

            $createdOrder = [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'tracking_url' => route('order.track', $order->order_number),
            ];

            // Clean action tag from text and add friendly confirmation
            $rawText = str_replace($matches[0], '', $rawText);
            $rawText .= "\n\n🎉 **تم تسجيل طلبك رسمياً في النظام برقم تتبع:** `{$order->order_number}`\nيمكنك تتبع مرحلة التصنيع في أي وقت عبر الرابط: [تتبع طلبك](" . route('order.track', $order->order_number) . ")\nوسيتواصل معك فريقنا الهندسي عبر الواتساب لتأكيد المخططات.";
        }

        // 2. Check for [DESIGN_CARD:id] triggers
        if (preg_match_all('/\[DESIGN_CARD:(\d+)\]/', $rawText, $cardMatches)) {
            $ideaIds = array_unique($cardMatches[1]);
            $ideas = AiDesignIdea::whereIn('id', $ideaIds)->where('is_active', true)->get();

            foreach ($ideas as $idea) {
                $suggestedIdeas[] = [
                    'id' => $idea->id,
                    'title' => $idea->title,
                    'category' => $idea->category,
                    'description' => $idea->description,
                    'wood_type' => $idea->wood_type,
                    'dimensions' => $idea->dimensions,
                    'price' => $idea->estimated_price_range,
                    'image' => $idea->image_url,
                    'pinterest_url' => $idea->pinterest_url,
                ];
            }

            // Remove card tags from pure text as cards will be rendered dynamically
            $rawText = preg_replace('/\[DESIGN_CARD:\d+\]/', '', $rawText);
        }

        return [
            'reply' => trim($rawText),
            'order' => $createdOrder,
            'suggested_ideas' => $suggestedIdeas,
        ];
    }

    /**
     * Look up order details in DB if user writes an order tracking code.
     */
    protected function lookupTrackingInfoIfPresent(string $text): ?string
    {
        if (preg_match('/ORD-\d{4}-\d{3,6}/i', $text, $matches)) {
            $orderNum = strtoupper($matches[0]);
            $order = CustomOrder::where('order_number', $orderNum)->first();

            if ($order) {
                $statusAr = match($order->status) {
                    'pending' => 'بانتظار المراجعة والتسعيرة',
                    'in_review' => 'قيد الدراسة الهندسية وتجهيز المخطط',
                    'contacted' => 'تم التواصل مع العميل واعتماد المواصفات',
                    'in_progress' => 'قيد التصنيع وقص الأخشاب في الورشة',
                    'completed' => 'مكتمل وجاهز للتسليم والتركيب',
                    'cancelled' => 'ملغي',
                    default => $order->status,
                };

                return "طلب رقم: {$order->order_number}\nاسم العميل: {$order->customer_name}\nالحالة الحالية: {$statusAr}\nنوع العمل: " . ($order->service?->title_ar ?: 'طلب مخصص') . "\nتاريخ الطلب: " . $order->created_at->format('Y-m-d');
            }
        }
        return null;
    }

    /**
     * Fallback joinery expert engine when Gemini API key is not configured yet or offline.
     */
    protected function generateFallbackResponse(AiChatSession $session, string $userMessage, ?string $trackingOrderInfo): array
    {
        $msgLower = mb_strtolower($userMessage);
        $suggestedIdeas = [];
        $createdOrder = null;

        // 1. Order Tracking Request
        if ($trackingOrderInfo) {
            $reply = "🔍 **تم العثور على بيانات طلبك في النظام:**\n\n{$trackingOrderInfo}\n\nيسعدنا دائماً خدمتك وفريقنا الحرفي يبذل أقصى جهده لإتقان عملكم!";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 2. Off-topic Guardrail
        $offTopicWords = ['طبخ', 'رياضة', 'كرة', 'سياسة', 'برمجة', 'كود', 'طقس', 'فيلم', 'مباراة', 'أخبار'];
        foreach ($offTopicWords as $word) {
            if (str_contains($msgLower, $word)) {
                return [
                    'reply' => "عذراً يا غالي! 🪵 أنا مستشار متخصص حصرياً في **استشارات الأعمال الخشبية، تفصيل غرف النوم، المكاتب، الديكورات، وبوثات المعارض** الخاصة بورشة أرتيزان. يسعدني جداً مساعدتك في أي استفسار يخص عالم الأخشاب الفاخرة!",
                    'order' => null,
                    'suggested_ideas' => []
                ];
            }
        }

        // 3. Match Bedroom inquiries
        if (str_contains($msgLower, 'غرف') || str_contains($msgLower, 'نوم') || str_contains($msgLower, 'سرير') || str_contains($msgLower, 'دريسنج')) {
            $ideas = AiDesignIdea::where('category', 'bedrooms')->orWhere('category', 'cabinets')->take(2)->get();
            foreach ($ideas as $idea) {
                $suggestedIdeas[] = [
                    'id' => $idea->id,
                    'title' => $idea->title,
                    'category' => $idea->category,
                    'description' => $idea->description,
                    'wood_type' => $idea->wood_type,
                    'dimensions' => $idea->dimensions,
                    'price' => $idea->estimated_price_range,
                    'image' => $idea->image_url,
                    'pinterest_url' => $idea->pinterest_url,
                ];
            }

            $reply = "أهلاً بك! 🛏️ غرف النوم من اختصاصاتنا الرائدة في ورشة أرتيزان. نوصي عادةً بـ **خشب البلوط الطبيعي أو خشب الجوز الأمريكي** لمتانته ولمسته الفاخرة المقاومة للخدوش.\n\nإليك بعض أرقى النماذج الإلهامية من بنك تصاميمنا. هل ترغب في تفصيل أي منها بمقاسات غرفتك الخاصة؟";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 4. Match Office inquiries
        if (str_contains($msgLower, 'مكتب') || str_contains($msgLower, 'طاولة') || str_contains($msgLower, 'اجتماعات') || str_contains($msgLower, 'شركات')) {
            $ideas = AiDesignIdea::where('category', 'offices')->orWhere('category', 'tables')->take(2)->get();
            foreach ($ideas as $idea) {
                $suggestedIdeas[] = [
                    'id' => $idea->id,
                    'title' => $idea->title,
                    'category' => $idea->category,
                    'description' => $idea->description,
                    'wood_type' => $idea->wood_type,
                    'dimensions' => $idea->dimensions,
                    'price' => $idea->estimated_price_range,
                    'image' => $idea->image_url,
                    'pinterest_url' => $idea->pinterest_url,
                ];
            }

            $reply = "المكاتب التنفيذية وطاولات الاجتماعات الملكية تعكس هيبة المكان! 🏢 نصنع المكاتب من **خشب الجوز الأمريكي الصلب (Live Edge)** مع حوامل معدنية معالجة وممرات أسلاك مخفية.\n\nتفضل بالاطلاع على هذه النماذج المختارة، ويمكننا تصميم وتنفيذ مكتب مخصص يحمل شعار شركتك بدقة CNC.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 5. Match Booths inquiries
        if (str_contains($msgLower, 'بوث') || str_contains($msgLower, 'معرض') || str_contains($msgLower, 'جناح') || str_contains($msgLower, 'فعالية')) {
            $ideas = AiDesignIdea::where('category', 'booths')->take(2)->get();
            foreach ($ideas as $idea) {
                $suggestedIdeas[] = [
                    'id' => $idea->id,
                    'title' => $idea->title,
                    'category' => $idea->category,
                    'description' => $idea->description,
                    'wood_type' => $idea->wood_type,
                    'dimensions' => $idea->dimensions,
                    'price' => $idea->estimated_price_range,
                    'image' => $idea->image_url,
                    'pinterest_url' => $idea->pinterest_url,
                ];
            }

            $reply = "أجنحة وبوثات المعارض تتطلب تصاميم جذابة ثلاثية الأبعاد وسرعة في التصنيع والتركيب الميداني في معارض الرياض وجدة! 🎪 نقوم بتنفيذ الهياكل البارامترية مع منصات الاستقبال وإضاءات الـ LED التفاعلية.\n\nإليك نماذج من أحدث أعمالنا وبنك تصاميم بنترست المقترحة.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 6. Match Wood advice inquiries
        if (str_contains($msgLower, 'خشب') || str_contains($msgLower, 'بلوط') || str_contains($msgLower, 'زان') || str_contains($msgLower, 'جوز') || str_contains($msgLower, 'تيك') || str_contains($msgLower, 'أنواع')) {
            $reply = "🪵 **دليل اختيار أنواع الأخشاب من خبراء أرتيزان:**\n\n" .
                "1. **خشب البلوط (Oak Wood)**: صلب جداً، عروق خشبية واضحة ومميزة، مثالي لغرف النوم والتكسيات الفاخرة.\n" .
                "2. **خشب الجوز الأمريكي (Walnut)**: قمة الفخامة بلونه البني الداكن الطبيعي، الخيار الأول للمكاتب التنفيذية وطاولات القصور.\n" .
                "3. **خشب الزان الألماني (Beech)**: متين وقوي وناعم الملمس، رائع للهياكل الداخلية وأرجل الكنب والكراسي.\n" .
                "4. **خشب التيك (Teak)**: ملك مقاومة الرطوبة والماء، مناسب للأماكن الرطبة والمطابخ والأثاث الخارجي.\n\n" .
                "هل لديك فكرة عمل معينة تريد معرفة الخشب الأنسب لها وتكلفتها التقديرية؟";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 7. General Hospitality Greeting & Guide
        $ideas = AiDesignIdea::where('is_active', true)->inRandomOrder()->take(2)->get();
        foreach ($ideas as $idea) {
            $suggestedIdeas[] = [
                'id' => $idea->id,
                'title' => $idea->title,
                'category' => $idea->category,
                'description' => $idea->description,
                'wood_type' => $idea->wood_type,
                'dimensions' => $idea->dimensions,
                'price' => $idea->estimated_price_range,
                'image' => $idea->image_url,
                'pinterest_url' => $idea->pinterest_url,
            ];
        }

        $reply = "أهلاً بك! في ورشة أرتيزان نحول أفكارك إلى تحف خشبية متقنة. ✨\n\nيمكنك إخباري بما تبحث عنه (مثل: غرفة نوم ماستر، مكتب تنفيذي، بوث معرض، طاولة طعام، أو تكسية جدارية) وسأقترح عليك التصاميم والمقاسات ونوع الخشب المناسب فوراً!";
        return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
    }
}
