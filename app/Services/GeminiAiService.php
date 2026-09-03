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
        $aboutText = $aboutStory ? strip_tags($aboutStory->content_ar) : 'ورشة متخصصة في تفصيل أرقى غرف النوم والمكاتب التنفيذية وبوثات المعارض.';

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

        $prompt .= "=== قواعد الحماية الصارمة والسلوك (CRITICAL STRICT GUARDRAILS) ===\n";
        $prompt .= "1. أنت تعمل حصرياً كمستشار رسمي لمنصة \"{$siteName}\". لا تؤلف أو تخترع أي معلومات من رأسك، والتزم التزاماً تاماً بالبيانات والأسئلة والأجوبة الواردة في بنك المعرفة أعلاه.\n";
        if ($isFirstMessage) {
            $prompt .= "2. هذه هي أول رسالة للمستخدم في هذه المحادثة: يجب عليك أولاً أن ترحب به وتعرف عن نفسك بأنك \"{$botName}\" المساعد الذكي الرسمي لمنصة \"{$siteName}\" {$siteSlogan}، ثم تجيب على طلبه بلباقة واحترافية.\n";
        } else {
            $prompt .= "2. أجب عن استفسار العميل مباشرة بأسلوب لبق، محترف، ومتخصص.\n";
        }
        $prompt .= "3. ممنوع منعاً باتاً الإجابة عن أي موضوع عام خارج نطاق المنصة (مثل: البرمجة، السياسة، الرياضة، الطبخ، الأفلام، الطقس، الأسئلة العامة غير المتعلقة بالأخشاب والأثاث والورشة). إذا سألك المستخدم عن أي موضوع خارج نطاق المنصة، يجب أن تعتذر له بلباقة وتخبره بأن هذا خارج اختصاصك، وأنك مخصص حصرياً للمساعدة في خدمات وأعمال \"{$siteName}\".\n";
        $prompt .= "4. عندما تقترح تصميماً من بنك الأفكار على العميل، قم بإدراج كود التصميم في نص إجابتك بهذه الصيغة بدقة: [DESIGN_CARD:id] (مثال: [DESIGN_CARD:1]).\n";
        $prompt .= "5. عندما يبدي العميل رغبته في تفصيل تصميم أو طلب تسعيرة ويزودك ببياناته (الاسم ورقم الجوال والمقاسات)، قم بتأكيد استلام الطلب وأدرج كود إنشاء الطلب في نهاية إجابتك بهذه الصيغة بدقة:\n";
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

        $greetingPrefix = "";
        if ($isFirstMessage) {
            $greetingPrefix = $this->locale === 'ar'
                ? "مرحباً بك! أنا **{$botName}**، المستشار الذكي الرسمي لمنصة **{$siteName}** ({$siteSlogan}). 🪵✨\n\n"
                : "Welcome! I am **{$botName}**, the official AI Consultant for **{$siteName}** ({$siteSlogan}). 🪵✨\n\n";
        }

        $msgLower = mb_strtolower(trim($userMessage));
        $suggestedIdeas = [];

        // 1. Order Tracking Request
        if ($trackingOrderInfo) {
            $reply = $greetingPrefix . ($this->locale === 'ar'
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
                    'reply' => $greetingPrefix . $refusal,
                    'order' => null,
                    'suggested_ideas' => []
                ];
            }
        }

        // 3. Search in AI FAQs Knowledge Base (Exact and Keyword Match)
        $allFaqs = AiFaq::where('is_active', true)->orderBy('sort_order')->get();
        foreach ($allFaqs as $faq) {
            $qAr = mb_strtolower($faq->question_ar);
            $qEn = mb_strtolower($faq->question_en ?? '');
            $keywords = array_filter(array_map('trim', explode(',', mb_strtolower($faq->keywords ?? ''))));

            $matched = false;
            // Check if user question contains FAQ question core or vice versa
            if (!empty($qAr) && (str_contains($msgLower, $qAr) || str_contains($qAr, $msgLower))) {
                $matched = true;
            } elseif (!empty($qEn) && (str_contains($msgLower, $qEn) || str_contains($qEn, $msgLower))) {
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
                    'reply' => $greetingPrefix . "💡 **" . ($this->locale === 'ar' ? 'إليك تفاصيل الإجابة المعتمدة:' : 'Official Information:') . "**\n\n" . $answer,
                    'order' => null,
                    'suggested_ideas' => []
                ];
            }
        }

        // 4. Match Bedroom inquiries
        if (str_contains($msgLower, 'غرف') || str_contains($msgLower, 'نوم') || str_contains($msgLower, 'سرير') || str_contains($msgLower, 'دريسنج') || str_contains($msgLower, 'bedroom')) {
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

            $reply = $greetingPrefix . ($this->locale === 'ar'
                ? "أهلاً بك! 🛏️ غرف النوم وتكسيات الجدران من اختصاصاتنا الرائدة في **{$siteName}**. نوصي عادةً بـ **خشب البلوط الطبيعي أو خشب الجوز الأمريكي** لمتانته ولمسته الفاخرة.\n\nإليك بعض أرقى النماذج الإلهامية من بنك تصاميمنا. هل ترغب في تفصيل أي منها بمقاسات غرفتك الخاصة؟"
                : "Welcome! 🛏️ Custom bedrooms are among our primary specialties at **{$siteName}**. We typically recommend **Natural Oak or American Walnut**.\n\nHere are some featured inspirations from our design bank. Would you like a custom quote for your space?");
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 5. Match Office inquiries
        if (str_contains($msgLower, 'مكتب') || str_contains($msgLower, 'طاولة') || str_contains($msgLower, 'اجتماعات') || str_contains($msgLower, 'شركات') || str_contains($msgLower, 'office') || str_contains($msgLower, 'desk')) {
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

            $reply = $greetingPrefix . ($this->locale === 'ar'
                ? "المكاتب التنفيذية وطاولات الاجتماعات الملكية تعكس هيبة المكان! 🏢 نصنع المكاتب في **{$siteName}** من **خشب الجوز الأمريكي الصلب** مع حوامل معدنية معالجة وممرات أسلاك مخفية.\n\nتفضل بالاطلاع على هذه النماذج المختارة، ويمكننا تصميم وتنفيذ مكتب مخصص يحمل شعار شركتك بدقة CNC."
                : "Executive desks and conference tables define workplace prestige! 🏢 We craft executive offices at **{$siteName}** using solid American Walnut and custom metal frames.");
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 6. Match Booths inquiries
        if (str_contains($msgLower, 'بوث') || str_contains($msgLower, 'معرض') || str_contains($msgLower, 'جناح') || str_contains($msgLower, 'فعالية') || str_contains($msgLower, 'booth') || str_contains($msgLower, 'exhibition')) {
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

            $reply = $greetingPrefix . ($this->locale === 'ar'
                ? "أجنحة وبوثات المعارض تتطلب تصاميم جذابة وسرعة في التصنيع والتركيب الميداني في معارض الرياض وجدة! 🎪 نقوم في **{$siteName}** بتنفيذ الهياكل البارامترية مع منصات الاستقبال وإضاءات الـ LED.\n\nإليك نماذج من أحدث أعمالنا وبنك تصاميم بنترست المقترحة."
                : "Exhibition booths require striking 3D parametric structures and swift installation! 🎪 **{$siteName}** specializes in turn-key exhibition stands and reception podiums.");
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
        }

        // 7. Match Wood types advice
        if (str_contains($msgLower, 'خشب') || str_contains($msgLower, 'بلوط') || str_contains($msgLower, 'زان') || str_contains($msgLower, 'جوز') || str_contains($msgLower, 'تيك') || str_contains($msgLower, 'wood')) {
            $reply = $greetingPrefix . ($this->locale === 'ar'
                ? "🪵 **دليل اختيار أنواع الأخشاب من خبراء {$siteName}:**\n\n" .
                  "1. **خشب البلوط (Oak Wood)**: صلب جداً، عروق خشبية واضحة ومميزة، مثالي لغرف النوم والتكسيات الفاخرة.\n" .
                  "2. **خشب الجوز الأمريكي (Walnut)**: قمة الفخامة بلونه البني الداكن الطبيعي، الخيار الأول للمكاتب التنفيذية وطاولات القصور.\n" .
                  "3. **خشب الزان الألماني (Beech)**: متين وقوي وناعم الملمس، رائع للهياكل الداخلية وأرجل الكنب والكراسي.\n" .
                  "4. **خشب التيك (Teak)**: ملك مقاومة الرطوبة والماء، مناسب للأماكن الرطبة والمطابخ والأثاث الخارجي.\n\n" .
                  "هل لديك فكرة عمل معينة تريد معرفة الخشب الأنسب لها وتكلفتها التقديرية؟"
                : "🪵 **Hardwood Selection Guide from {$siteName}:**\n\n1. **Oak**: Highly durable with rich grain.\n2. **Walnut**: Deep natural luxury for executive desks.\n3. **Beech**: Sturdy structural hardwood.\n4. **Teak**: Exceptional moisture resistance.");
            return ['reply' => $reply, 'order' => null, 'suggested_ideas' => []];
        }

        // 8. General Hospitality Greeting & Guide
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

        $reply = $greetingPrefix . ($this->locale === 'ar'
            ? "أهلاً بك في منصة **{$siteName}**! نحول أفكارك إلى تحف خشبية متقنة تفخر بها. ✨\n\nيمكنك إخباري بما تبحث عنه (مثل: غرفة نوم ماستر، مكتب تنفيذي، بوث معرض، طاولة طعام، أو تكسية جدارية) وسأقترح عليك التصاميم والمقاسات ونوع الخشب المناسب فوراً!"
            : "Welcome to **{$siteName}**! We transform your ideas into masterfully crafted bespoke woodwork. ✨ How can I assist you today?");

        return ['reply' => $reply, 'order' => null, 'suggested_ideas' => $suggestedIdeas];
    }
}
