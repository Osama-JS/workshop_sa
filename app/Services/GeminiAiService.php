<?php

namespace App\Services;

use App\Models\AboutSection;
use App\Models\AiChatSession;
use App\Models\AiDesignIdea;
use App\Models\AiFaq;
use App\Models\CustomOrder;
use App\Models\CustomPage;
use App\Models\Portfolio;
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

        // 2. Check if this is the first assistant message in session to introduce himself
        $isFirstAssistantMessage = $session->messages()->where('sender', 'assistant')->count() === 0;

        // 3. Build system instructions with live workshop knowledge & strict guardrails
        $systemInstruction = $this->buildSystemInstruction($trackingOrderInfo, $isFirstAssistantMessage);

        // 4. Build contents array with conversation history
        $contents = $this->buildConversationContents($session, $userMessage, $uploadedImagePath);

        // 5. If API key is available, attempt calling Gemini API
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
                        $processed = $this->processAssistantResponse($session, $rawText);
                        $session->recordUsage(200);
                        return $processed;
                    }
                } else {
                    Log::warning('Gemini API Response Error', ['status' => $response->status(), 'body' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error('Gemini API Exception: ' . $e->getMessage());
            }
        }

        // 6. Intelligent Fallback Engine (for offline local development or when API key is pending)
        $fallback = $this->generateFallbackResponse($session, $userMessage, $trackingOrderInfo, $isFirstAssistantMessage);
        $session->recordUsage(100);
        return $fallback;
    }

    /**
     * Build system instructions embedding workshop knowledge, Q&A bank, and strict guardrails.
     */
    protected function buildSystemInstruction(?string $trackingOrderInfo = null, bool $isFirstMessage = false): string
    {
        $siteName = Setting::get("site_name_{$this->locale}", ($this->locale === 'ar' ? 'أرتيزان للأعمال الخشبية' : 'Artisan Woodcraft'));
        $siteSlogan = Setting::get("site_slogan_{$this->locale}", ($this->locale === 'ar' ? 'للأعمال الخشبية الفاخرة' : 'Luxury Bespoke Woodcraft'));
        $botName = Setting::get("ai_bot_name_{$this->locale}", ($this->locale === 'ar' ? "مستشار {$siteName} الذكي" : "{$siteName} AI Consultant"));
        $botRole = Setting::get("ai_bot_role_{$this->locale}", ($this->locale === 'ar' ? "مستشار تفصيل الأثاث والأعمال الخشبية لـ {$siteName}" : "Luxury Woodwork & Joinery Consultant for {$siteName}"));
        $basePrompt = Setting::get("ai_system_prompt_{$this->locale}", '');

        // Fetch Services Knowledge
        $services = Service::where('is_active', true)->get()->map(function ($s) {
            $title = $this->locale === 'en' && !empty($s->title_en) ? $s->title_en : $s->title_ar;
            $desc = $this->locale === 'en' && !empty($s->short_desc_en) ? $s->short_desc_en : $s->short_desc_ar;
            return "- [الخدمة #{$s->id}]: {$title} - {$desc}";
        })->implode("\n");

        // Fetch FAQs & Knowledge Base Q&A
        $faqs = AiFaq::where('is_active', true)->orderBy('sort_order')->get()->map(function ($f) {
            $q = $this->locale === 'en' && !empty($f->question_en) ? $f->question_en : $f->question_ar;
            $a = $this->locale === 'en' && !empty($f->answer_en) ? $f->answer_en : $f->answer_ar;
            return "سؤال: {$q}\nإجابة: {$a}";
        })->implode("\n---\n");

        // Fetch Design Ideas & Pinterest Inspirations
        $ideas = AiDesignIdea::where('is_active', true)->orderBy('sort_order')->take(10)->get()->map(function ($idea) {
            $title = $this->locale === 'en' && !empty($idea->title_en) ? $idea->title_en : $idea->title_ar;
            $desc = $this->locale === 'en' && !empty($idea->description_en) ? $idea->description_en : $idea->description_ar;
            return "- [تصميم #{$idea->id}]: {$title} | التصنيف: {$idea->category} | نوع الخشب: {$idea->wood_type} | الأبعاد: {$idea->dimensions} | السعر التقديري: {$idea->estimated_price_range} | رابط بنترست: {$idea->pinterest_url} | الوصف: {$desc}";
        })->implode("\n");

        // Fetch About Us Details
        $aboutStory = AboutSection::where('section_key', 'story')->first();
        $aboutText = $aboutStory ? strip_tags($aboutStory->content_ar) : 'ورشة متخصصة في تفصيل أرقى غرف النوم والمكاتب التنفيذية وبوثات المعارض والأعمال الخشبية المخصصة بأحدث مكائن الـ CNC والحرفية اليدوية العريقة.';

        $phone = Setting::get('contact_phone') ?: Setting::get('phone', '+966500000000');
        $whatsapp = Setting::get('contact_whatsapp') ?: Setting::get('whatsapp', '+966500000000');
        $email = Setting::get('contact_email') ?: Setting::get('email', 'info@artisanwood.sa');
        $workingHours = Setting::get("working_hours_{$this->locale}", 'السبت - الخميس: 9:00 ص - 10:00 م');
        $address = Setting::get("address_{$this->locale}", 'المملكة العربية السعودية');

        $prompt = "{$basePrompt}\n\n";
        $prompt .= "=== الهوية الرسمية للمنصة ===\n";
        $prompt .= "اسم المنصة الرسمي: {$siteName}\n";
        $prompt .= "الشعار اللفظي: {$siteSlogan}\n";
        $prompt .= "اسمك: {$botName}\n";
        $prompt .= "وظيفتك: {$botRole}\n";
        $prompt .= "بيانات التواصل: الهاتف: {$phone} | واتساب: {$whatsapp} | البريد: {$email}\n";
        $prompt .= "ساعات العمل: {$workingHours} | العنوان: {$address}\n\n";

        $prompt .= "=== نبذة عن المنصة (About Us) ===\n{$aboutText}\n\n";
        $prompt .= "=== خدمات وأعمال المنصة المتاحة ===\n{$services}\n\n";
        $prompt .= "=== بنك الأسئلة والأجوبة المعتمدة (Official FAQ Knowledge Base) ===\n{$faqs}\n\n";
        $prompt .= "=== بنك الأفكار والتصاميم المعتمدة (Pinterest & Workshop Inspirations) ===\n{$ideas}\n\n";

        if ($trackingOrderInfo) {
            $prompt .= "=== بيانات الطلب المستفسر عنه في النظام حالياً ===\n{$trackingOrderInfo}\n\n";
        }

        $prompt .= "=== قواعد الحماية الصارمة والسلوك والاحترافية (CRITICAL STRICT RULES) ===\n";
        $prompt .= "1. أنت تعمل حصرياً كمستشار رسمي لمنصة \"{$siteName}\". لا تؤلف أو تخترع أي معلومات من رأسك، والتزم التزاماً تاماً بالبيانات والأسئلة والأجوبة الواردة في بنك المعرفة أعلاه.\n";
        $prompt .= "2. إذا سألك العميل 'من أنت؟' أو 'عرفني بنفسك'، أجب بتعريف احترافي عن نفسك ووظيفتك وإمكانياتك في مساعدة العميل، دون إدراج بطاقات تصاميم إلا إذا طلب هو ذلك.\n";
        $prompt .= "3. إذا سألك العميل عن المنصة أو اسمها 'من هي {$siteName}؟' أو 'من أنتم؟' أو 'نبذة عن الورشة'، قدم نبذة وافية وراقية تشمل اختصاصات المنصة، جودتها، عنوانها، ورقم التواصل، دون إدراج بطاقات تصاميم إلا إذا طلب ذلك.\n";
        $prompt .= "4. لا تقم بإدراج بطاقات التصاميم [DESIGN_CARD:id] إطلاقاً إلا إذا طلب العميل صراحةً رؤية تصاميم أو صور أو أفكار أو نماذج ملهمة (مثل: أريد تصاميم، اقترح علي أفكار، اعرض نماذج، صور غرف نوم).\n";
        $prompt .= "5. ممنوع منعاً باتاً الإجابة عن أي موضوع عام خارج نطاق المنصة (مثل: البرمجة، السياسة، الرياضة، الطبخ، الأفلام، الطقس، الأسئلة العامة غير المتعلقة بالأخشاب والأثاث والورشة). إذا سألك المستخدم عن أي موضوع خارج نطاق المنصة، يجب أن تعتذر له بلباقة وتخبره بأن هذا خارج اختصاصك، وأنك مخصص حصرياً للمساعدة في خدمات وأعمال \"{$siteName}\".\n";
        $prompt .= "6. عندما يبدي العميل رغبته في تفصيل تصميم أو طلب تسعيرة ويزودك ببياناته (الاسم ورقم الجوال والمقاسات)، قم بتأكيد استلام الطلب وأدرج كود إنشاء الطلب في نهاية إجابتك بهذه الصيغة بدقة:\n";
        $prompt .= "[ACTION:CREATE_ORDER|name=اسم_العميل|phone=رقم_الجوال|category=نوع_العمل|wood=نوع_الخشب|dimensions=المقاسات|notes=الملاحظات]\n";

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
     * Fallback joinery expert engine with self-introduction, strict guardrails, and Q&A bank integration.
     */
    protected function generateFallbackResponse(AiChatSession $session, string $userMessage, ?string $trackingOrderInfo, bool $isFirstMessage = false): array
    {
        $siteName = Setting::get("site_name_{$this->locale}", ($this->locale === 'ar' ? 'أرتيزان للأعمال الخشبية' : 'Artisan Woodcraft'));
        $siteSlogan = Setting::get("site_slogan_{$this->locale}", ($this->locale === 'ar' ? 'للأعمال الخشبية الفاخرة' : 'Luxury Bespoke Woodcraft'));
        $botName = Setting::get("ai_bot_name_{$this->locale}", ($this->locale === 'ar' ? "مستشار {$siteName} الذكي" : "{$siteName} AI Consultant"));
        $botRole = Setting::get("ai_bot_role_{$this->locale}", ($this->locale === 'ar' ? "مستشار تفصيل الأثاث والأعمال الخشبية لـ {$siteName}" : "Luxury Woodwork & Joinery Consultant for {$siteName}"));

        $phone = Setting::get('contact_phone') ?: Setting::get('phone', '+966500000000');
        $whatsapp = Setting::get('contact_whatsapp') ?: Setting::get('whatsapp', '+966500000000');
        $email = Setting::get('contact_email') ?: Setting::get('email', 'info@artisanwood.sa');
        $workingHours = Setting::get("working_hours_{$this->locale}", 'السبت - الخميس: 9:00 ص - 10:00 م');
        $address = Setting::get("address_{$this->locale}", 'المملكة العربية السعودية');

        $aboutStory = AboutSection::where('section_key', 'story')->first();
        $aboutText = $aboutStory ? strip_tags($aboutStory->content_ar) : 'نحن ورشة رائدة متخصصة في ابتكار وتصنيع أرقى الأثاث الخشبي المخصص، الديكورات الجدارية، غرف النوم الماستر، المكاتب التنفيذية، وأجنحة المعارض بأحدث التقنيات وأفضل أنواع الأخشاب الطبيعية.';

        $msgLower = mb_strtolower(trim($userMessage));
        $cleanMsg = preg_replace('/[^\p{L}\p{N}\s]/u', '', $msgLower);
        $siteNameLower = mb_strtolower($siteName);

        // Check if user explicitly asked for designs/ideas/pictures
        $isAskingForDesigns = str_contains($msgLower, 'تصميم') || str_contains($msgLower, 'أفكار') || 
                               str_contains($msgLower, 'افكار') || str_contains($msgLower, 'تصاميم') || 
                               str_contains($msgLower, 'صور') || str_contains($msgLower, 'نماذج') || 
                               str_contains($msgLower, 'design') || str_contains($msgLower, 'idea') || 
                               str_contains($msgLower, 'photo') || str_contains($msgLower, 'catalog');

        // 1. Order Tracking Request
        if ($trackingOrderInfo) {
            $reply = ($this->locale === 'ar'
                ? "🔍 **تم العثور على بيانات طلبك في النظام:**\n\n{$trackingOrderInfo}\n\nيسعدنا دائماً خدمتكم وفريقنا الحرفي يبذل أقصى جهده لإتقان عملكم!"
                : "🔍 **Your order details were found in our system:**\n\n{$trackingOrderInfo}\n\nWe are delighted to serve you!");
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 2. Strict Off-Topic Guardrail (Polite Refusal)
        $offTopicWords = [
            'طبخ', 'وصفة', 'أكل', 'مطعم', 'رياضة', 'كرة', 'مباراة', 'دوري', 'كريستيانو', 'ميسي',
            'سياسة', 'حرب', 'انتخابات', 'رئيس', 'برمجة', 'كود', 'php', 'javascript', 'python', 'html',
            'طقس', 'جو', 'مطر', 'فيلم', 'مسلسل', 'سينما', 'أخبار', 'سهم', 'تداول', 'عملات',
            'cooking', 'recipe', 'football', 'soccer', 'politics', 'programming', 'code', 'weather', 'movie'
        ];

        foreach ($offTopicWords as $word) {
            if (str_contains($msgLower, $word)) {
                $refusal = $this->locale === 'ar'
                    ? "أهلاً بك يا غالي! أعتذر منك، أنا مستشار ذكي متخصص حصرياً في **الأعمال الخشبية الفاخرة، تفصيل غرف النوم، المكاتب التنفيذية، بوثات المعارض، والديكورات** الخاصة بمنصة **{$siteName}**.\n\nيسعدني جداً الإجابة على أي استفسار يخص خدماتنا أو مساعدتك في تفصيل طلبك الخشبي!"
                    : "Hello! I apologize, but I am an AI consultant specialized exclusively in **bespoke woodwork, luxury bedrooms, executive offices, exhibition booths, and custom joinery** for **{$siteName}**.\n\nI would be delighted to assist you with any questions about our woodwork services!";
                return [
                    'reply' => $refusal,
                    'order' => null,
                    'suggested_ideas' => []
                ];
            }
        }

        // 3. User Asking "Who are you?" ("من أنت؟", "عرفني بنفسك", "ما هي وظيفتك")
        if (str_contains($cleanMsg, 'من انت') || str_contains($cleanMsg, 'من أنت') || 
            str_contains($cleanMsg, 'عرفني عن نفسك') || str_contains($cleanMsg, 'عرفني بنفسك') || 
            str_contains($cleanMsg, 'ما هي وظيفتك') || str_contains($cleanMsg, 'ما وظيفتك') || 
            str_contains($cleanMsg, 'who are you') || str_contains($cleanMsg, 'what is your job')) {
            
            $reply = $this->locale === 'ar'
                ? "أهلاً بك! أنا **{$botName}**، المستشار الذكي والمهندس الحرفي لمنصة **{$siteName}** ({$siteSlogan}). 🪵✨\n\n**يسعدني تقديم الخدمات التالية لك:**\n1. 🪚 **استشارات النجارة والتفصيل**: مساعدتك في اختيار أفضل التصاميم ونوع الخشب المناسب لمشروعك.\n2. 🪵 **دليل أنواع الأخشاب**: توضيح الفروقات والأسعار ومقاومة الأخشاب (بلوط، جوز، زان، تيك).\n3. 🔍 **تتبع حالة الطلبات**: معرفة مرحلة تصنيع طلبك بمجرد إدخال رقم الطلب.\n4. 📝 **استقبال طلبات التفصيل**: تدوين مواصفات طلبك وإرساله للفريق الهندسي لتجهيز المخططات والتسعيرة.\n\nكيف يمكنني خدمتك في مشروعك اليوم؟"
                : "Welcome! I am **{$botName}**, the official AI Consultant for **{$siteName}** ({$siteSlogan}). 🪵✨\n\n**How can I assist you today?**\n- Woodwork & custom joinery consultations.\n- Recommending premium wood types (Oak, Walnut, Beech, Teak).\n- Tracking your custom manufacturing order.\n- Processing custom quote requests for our engineering workshop.\n\nHow can I help you with your project today?";

            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 4. User Asking "Who is the platform / About Us?" ("من هي المنصة", "من انتم", "عن الورشة", "ما هي ورشة...", "نبذة عنكم")
        $isAskingAboutPlatform = str_contains($cleanMsg, 'من هي المنصة') || str_contains($cleanMsg, 'ما هي المنصة') ||
                                 str_contains($cleanMsg, 'ماهي المنصة') || str_contains($cleanMsg, 'من المنصة') ||
                                 str_contains($cleanMsg, 'من هي منصة') || str_contains($cleanMsg, 'ما هي منصة') ||
                                 str_contains($cleanMsg, 'ماهي منصة') ||
                                 str_contains($cleanMsg, 'من هي الورشة') || str_contains($cleanMsg, 'ما هي الورشة') ||
                                 str_contains($cleanMsg, 'ماهي الورشة') || str_contains($cleanMsg, 'من هي ورشة') ||
                                 str_contains($cleanMsg, 'ما هي ورشة') || str_contains($cleanMsg, 'ماهي ورشة') ||
                                 str_contains($cleanMsg, 'من هي الشركة') || str_contains($cleanMsg, 'ما هي الشركة') ||
                                 str_contains($cleanMsg, 'ماهي الشركة') || str_contains($cleanMsg, 'من هي شركة') ||
                                 str_contains($cleanMsg, 'ما هي شركة') || str_contains($cleanMsg, 'ماهي شركة') ||
                                 str_contains($cleanMsg, 'من هي أرتيزان') || str_contains($cleanMsg, 'ما هي أرتيزان') ||
                                 str_contains($cleanMsg, 'ماهي أرتيزان') || str_contains($cleanMsg, 'ما هو أرتيزان') ||
                                 str_contains($cleanMsg, 'من هو أرتيزان') ||
                                 str_contains($cleanMsg, 'من انتم') || str_contains($cleanMsg, 'من أنتم') || 
                                 str_contains($cleanMsg, 'من نحن') || 
                                 str_contains($cleanMsg, 'عن المنصة') || str_contains($cleanMsg, 'عن الورشة') || 
                                 str_contains($cleanMsg, 'عن الشركة') || str_contains($cleanMsg, 'عن أرتيزان') ||
                                 str_contains($cleanMsg, 'نبذة عن') || str_contains($cleanMsg, 'نبذه عن') ||
                                 str_contains($cleanMsg, 'قصتكم') || str_contains($cleanMsg, 'قصة المنصة') ||
                                 str_contains($cleanMsg, 'about us') || str_contains($cleanMsg, 'who are we');

        if ($isAskingAboutPlatform) {
            $reply = $this->locale === 'ar'
                ? "🏛️ **منصة {$siteName} ({$siteSlogan}):**\n\n{$aboutText}\n\n" .
                  "**✨ ما يميزنا:**\n" .
                  "- دقة تنفيذ متناهية باستخدام أحدث مكائن CNC وحرفية يدوية عريقة.\n" .
                  "- ضمان يصل إلى 10 سنوات على الهياكل الخشبية والإكسسوارات الألمانية.\n" .
                  "- فريق هندسي متكامل لرفع المقاسات وإعداد المخططات ثلاثية الأبعاد (3D).\n\n" .
                  "📍 **الموقع:** {$address}\n" .
                  "⏰ **ساعات العمل:** {$workingHours}\n" .
                  "📞 **للتواصل والاستفسار:** {$phone} | واتساب: {$whatsapp}"
                : "🏛️ **{$siteName} ({$siteSlogan}):**\n\n{$aboutText}\n\n📍 **Location:** {$address}\n⏰ **Working Hours:** {$workingHours}\n📞 **Contact:** {$phone} | WhatsApp: {$whatsapp}";

            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 5. Search in AI FAQs Knowledge Base (Exact and Keyword Match)
        $allFaqs = AiFaq::where('is_active', true)->orderBy('sort_order')->get();
        foreach ($allFaqs as $faq) {
            $qAr = mb_strtolower($faq->question_ar);
            $qEn = mb_strtolower($faq->question_en ?? '');
            $keywords = array_filter(array_map('trim', explode(',', mb_strtolower($faq->keywords ?? ''))));

            $matched = false;
            // Check if user question matches FAQ core
            if (!empty($qAr) && (str_contains($msgLower, $qAr) || (mb_strlen($cleanMsg) >= 6 && str_contains($qAr, $cleanMsg)))) {
                $matched = true;
            } elseif (!empty($qEn) && (str_contains($msgLower, $qEn) || (mb_strlen($cleanMsg) >= 6 && str_contains($qEn, $cleanMsg)))) {
                $matched = true;
            } else {
                // Check keywords
                foreach ($keywords as $kw) {
                    if (!empty($kw) && mb_strlen($kw) >= 3 && str_contains($msgLower, $kw)) {
                        $matched = true;
                        break;
                    }
                }
            }

            if ($matched) {
                $answer = $this->locale === 'en' && !empty($faq->answer_en) ? $faq->answer_en : $faq->answer_ar;
                return [
                    'reply' => "💡 **" . ($this->locale === 'ar' ? 'إليك تفاصيل الإجابة المعتمدة:' : 'Official Information:') . "**\n\n" . $answer,
                    'order' => null,
                    'suggested_ideas' => []
                ];
            }
        }

        // 6. Match Bedroom inquiries
        if (str_contains($msgLower, 'غرف') || str_contains($msgLower, 'نوم') || str_contains($msgLower, 'سرير') || str_contains($msgLower, 'دريسنج') || str_contains($msgLower, 'bedroom')) {
            $suggestedIdeas = [];
            if ($isAskingForDesigns) {
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
            }

            $reply = $this->locale === 'ar'
                ? "أهلاً بك! 🛏️ غرف النوم الماستر والدريسنج روم من أهم اختصاصاتنا في **{$siteName}**. نقوم بتفصيل الهياكل من خشب البلوط أو الجوز الطبيعي مع إضاءات LED مدمجة وتقسيمات ذكية.\n\nهل تود استعراض بعض التصاميم الملهمة أو تود تزويدنا بالمقاسات التقريبية لغرفتك لتزويدك بالتسعيرة؟"
                : "Welcome! 🛏️ Master bedrooms and walk-in closets are key specialties at **{$siteName}**. We craft solid structures using natural Oak or Walnut with integrated LED profiles.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 7. Match Office inquiries
        if (str_contains($msgLower, 'مكتب') || str_contains($msgLower, 'طاولة') || str_contains($msgLower, 'اجتماعات') || str_contains($msgLower, 'شركات') || str_contains($msgLower, 'office') || str_contains($msgLower, 'desk')) {
            $suggestedIdeas = [];
            if ($isAskingForDesigns) {
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
            }

            $reply = $this->locale === 'ar'
                ? "المكاتب التنفيذية وطاولات الاجتماعات الملكية تعكس هيبة المكان! 🏢 نصنع المكاتب في **{$siteName}** من **خشب الجوز الأمريكي الصلب** مع حوامل معدنية معالجة وممرات أسلاك مخفية.\n\nهل لديك مقاسات أو تصميم محدد ترغب في تنفيذه، أو تود استعراض تصاميمنا المقترحة؟"
                : "Executive desks and conference tables define workplace prestige! 🏢 We craft executive offices at **{$siteName}** using solid American Walnut.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 8. Match Booths inquiries
        if (str_contains($msgLower, 'بوث') || str_contains($msgLower, 'معرض') || str_contains($msgLower, 'جناح') || str_contains($msgLower, 'فعالية') || str_contains($msgLower, 'booth') || str_contains($msgLower, 'exhibition')) {
            $suggestedIdeas = [];
            if ($isAskingForDesigns) {
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
            }

            $reply = $this->locale === 'ar'
                ? "أجنحة وبوثات المعارض تتطلب تصاميم جذابة وسرعة في التصنيع والتركيب الميداني! 🎪 نقوم في **{$siteName}** بتنفيذ الهياكل البارامترية مع منصات الاستقبال وإضاءات الـ LED وشاشات العرض.\n\nما هي مساحة الجناح المطلوب وموعد المعرض؟"
                : "Exhibition booths require striking 3D parametric structures and swift installation! 🎪 **{$siteName}** specializes in turn-key exhibition stands.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 9. Match Wood types advice
        if (str_contains($msgLower, 'خشب') || str_contains($msgLower, 'بلوط') || str_contains($msgLower, 'زان') || str_contains($msgLower, 'جوز') || str_contains($msgLower, 'تيك') || str_contains($msgLower, 'wood')) {
            $reply = $this->locale === 'ar'
                ? "🪵 **دليل اختيار أنواع الأخشاب من خبراء {$siteName}:**\n\n" .
                  "1. **خشب البلوط (Oak Wood)**: صلب جداً، عروق خشبية واضحة ومميزة، مثالي لغرف النوم والتكسيات الفاخرة.\n" .
                  "2. **خشب الجوز الأمريكي (Walnut)**: قمة الفخامة بلونه البني الداكن الطبيعي، الخيار الأول للمكاتب التنفيذية وطاولات القصور.\n" .
                  "3. **خشب الزان الألماني (Beech)**: متين وقوي وناعم الملمس، رائع للهياكل الداخلية وأرجل الكنب والكراسي.\n" .
                  "4. **خشب التيك (Teak)**: ملك مقاومة الرطوبة والماء، مناسب للأماكن الرطبة والمطابخ والأثاث الخارجي.\n\n" .
                  "هل لديك فكرة عمل معينة تريد معرفة الخشب الأنسب لها وتكلفتها التقديرية؟"
                : "🪵 **Hardwood Selection Guide from {$siteName}:**\n\n1. **Oak**: Highly durable with rich grain.\n2. **Walnut**: Deep natural luxury for executive desks.\n3. **Beech**: Sturdy structural hardwood.\n4. **Teak**: Exceptional moisture resistance.";
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 10. General Hospitality Greeting & Guide (No designs dumped by default)
        $reply = $this->locale === 'ar'
            ? "أهلاً بك في منصة **{$siteName}** ({$siteSlogan})! 🪵✨\n\nأنا **{$botName}**، يسعدني مساعدتك في كل ما يخص تفصيل الأثاث الفاخر، غرف النوم، المكاتب التنفيذية، بوثات المعارض، وتكسيات الجدران.\n\nكيف يمكنني مساعدتك في مشروعك اليوم؟"
            : "Welcome to **{$siteName}** ({$siteSlogan})! 🪵✨\n\nI am **{$botName}**, here to assist you with bespoke woodwork, luxury bedrooms, executive desks, and exhibition booths. How can I help you today?";

        return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
    }
}
