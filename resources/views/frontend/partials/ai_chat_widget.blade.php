@if((string)\App\Models\Setting::get('ai_enabled', '1') === '1')
<!-- =========================================================================
     ARTISAN LUXURY AI JOINERY CONSULTANT - FLOATING CHAT WIDGET
     ========================================================================= -->
<div id="artisanAiWidget" class="fixed z-50 {{ app()->getLocale() === 'ar' ? 'bottom-6 left-6' : 'bottom-6 right-6' }} font-sans" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    <!-- 1. Floating Pulse Trigger Button -->
    <div id="aiWidgetLauncher" class="relative group">
        <!-- Floating Tooltip Notification (Dismissible) -->
        <div id="aiWelcomeBubble" class="absolute bottom-16 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-64 p-3 rounded-2xl bg-dark-900 border border-gold-500/40 shadow-2xl text-xs space-y-1.5 hidden sm:block animate-bounce duration-1000 transition-all">
            <div class="flex items-center justify-between">
                <span class="font-bold text-gold-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-sparkles text-[11px]"></i>
                    <span id="aiBubbleTitle">{{ \App\Models\Setting::get('ai_bot_name_' . app()->getLocale(), 'مستشار أرتيزان الذكي') }}</span>
                </span>
                <button type="button" onclick="dismissAiBubble(event); document.getElementById('aiWelcomeBubble')?.remove();" class="text-slate-400 hover:text-rose-400 text-xs p-1 cursor-pointer" title="{{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <p class="text-[11px] text-slate-300 leading-relaxed" id="aiBubbleSnippet">
                {{ app()->getLocale() === 'ar' ? 'تبحث عن تصميم لغرفة نوم، مكتب، أو بوث؟ استشرني فوراً!' : 'Looking for bespoke bedroom, office, or booth ideas? Ask me now!' }}
            </p>
        </div>

        <button onclick="if(typeof toggleAiChat==='function') toggleAiChat(); else { document.getElementById('aiChatWindow').classList.toggle('hidden'); }" id="aiToggleBtn" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gold-gradient text-slate-950 shadow-2xl shadow-gold-500/40 hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center text-2xl relative border-2 border-white/40">
            <i id="aiIconOpen" class="fa-solid fa-wand-magic-sparkles"></i>
            <i id="aiIconClose" class="fa-solid fa-xmark hidden"></i>
            <!-- Online Green Status Indicator Dot -->
            <span class="absolute top-0.5 right-0.5 w-4 h-4 bg-emerald-500 border-2 border-dark-950 rounded-full animate-pulse"></span>
        </button>
    </div>

    <!-- 2. AI Chat Main Drawer / Window -->
    <div id="aiChatWindow" class="fixed sm:absolute bottom-0 sm:bottom-20 {{ app()->getLocale() === 'ar' ? 'left-0 sm:left-0' : 'right-0 sm:right-0' }} w-full sm:w-[410px] h-[100dvh] sm:h-[620px] max-h-[100dvh] sm:max-h-[640px] rounded-none sm:rounded-3xl glass-card flex flex-col shadow-2xl border border-gold-500/30 overflow-hidden hidden transition-all duration-300 z-50">

        <!-- Chat Header -->
        <div class="px-5 py-4 bg-dark-950/90 border-b border-white/10 flex items-center justify-between relative select-none">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-wood-600 to-wood-800 flex items-center justify-center text-white text-lg shadow-md border border-gold-500/40">
                        <i class="fa-solid fa-robot"></i>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-dark-950 rounded-full"></span>
                </div>
                <div>
                    <h3 id="aiHeaderBotName" class="font-bold text-white text-sm leading-tight">
                        {{ \App\Models\Setting::get('ai_bot_name_' . app()->getLocale(), 'مستشار أرتيزان الذكي') }}
                    </h3>
                    <p class="text-[10px] text-gold-400 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
                        <span>{{ app()->getLocale() === 'ar' ? 'مهندس ديكور ومستشار أخشاب متصل' : 'Online Woodwork Consultant' }}</span>
                    </p>
                </div>
            </div>

            <!-- Header Actions -->
            <div class="flex items-center gap-1 text-slate-400">
                <button type="button" onclick="clearAiChat()" class="p-2 rounded-xl hover:bg-white/10 hover:text-white transition text-xs" title="{{ app()->getLocale() === 'ar' ? 'مسح المحادثة' : 'Clear Chat' }}">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button type="button" onclick="if(typeof closeAiChat==='function') closeAiChat(); else { document.getElementById('aiChatWindow').classList.add('hidden'); document.getElementById('aiIconOpen')?.classList.remove('hidden'); document.getElementById('aiIconClose')?.classList.add('hidden'); }" class="p-2 rounded-xl hover:bg-rose-500/20 hover:text-rose-400 transition text-sm font-bold flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 cursor-pointer" title="{{ app()->getLocale() === 'ar' ? 'إغلاق المحادثة' : 'Close' }}">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Chat Messages Scroll Area -->
        <div id="aiChatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 text-xs scroll-smooth bg-dark-900/60">
            <!-- Loading initial state -->
            <div id="aiChatLoadingInit" class="text-center py-8 text-slate-400 space-y-2">
                <i class="fa-solid fa-circle-notch fa-spin text-gold-500 text-2xl"></i>
                <p>{{ app()->getLocale() === 'ar' ? 'جاري الاتصال بالمستشار الذكي...' : 'Connecting to AI Consultant...' }}</p>
            </div>
        </div>

        <!-- Quick Suggestions Chips Bar -->
        <div id="aiQuickChips" class="px-3 py-2 bg-dark-950/40 border-t border-white/5 flex items-center gap-2 overflow-x-auto no-scrollbar hidden">
            <!-- Injected via JavaScript -->
        </div>

        <!-- Image Preview if selected -->
        <div id="aiImagePreviewContainer" class="px-4 py-2 bg-dark-950/80 border-t border-white/10 flex items-center justify-between hidden">
            <div class="flex items-center gap-2">
                <img id="aiImagePreviewThumb" src="" alt="preview" class="w-10 h-10 object-cover rounded-lg border border-gold-500/40">
                <span id="aiImagePreviewName" class="text-[11px] text-slate-300 truncate max-w-[200px]"></span>
            </div>
            <button onclick="removeSelectedAiImage()" class="text-rose-400 hover:text-rose-300 p-1 text-xs">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Chat Input Form -->
        <div class="p-3 bg-dark-950/90 border-t border-white/10">
            <form id="aiChatForm" onsubmit="sendAiMessage(event)" class="flex items-end gap-2">
                <!-- File upload button -->
                <label for="aiImageUploadInput" class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-gold-400 cursor-pointer transition border border-white/10 flex-shrink-0" title="{{ app()->getLocale() === 'ar' ? 'إرفاق صورة أو مخطط' : 'Attach Photo/Plan' }}">
                    <i class="fa-solid fa-paperclip text-sm"></i>
                    <input type="file" id="aiImageUploadInput" accept="image/*" class="hidden" onchange="handleAiImageSelect(this)">
                </label>

                <!-- Textarea -->
                <div class="flex-1 relative">
                    <textarea id="aiMessageInput" rows="1" placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب سؤالك أو استفسارك هنا...' : 'Type your question or design idea...' }}"
                        class="w-full bg-dark-900 border border-white/10 rounded-2xl px-3.5 py-2.5 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500 transition resize-none max-h-24 leading-relaxed"
                        onkeydown="handleAiInputKeydown(event)"></textarea>
                </div>

                <!-- Send Button -->
                <button type="submit" id="aiSendBtn" class="w-10 h-10 rounded-2xl bg-gold-gradient text-slate-950 flex items-center justify-center text-sm shadow-md hover:brightness-110 active:scale-95 transition flex-shrink-0 font-bold">
                    <i class="fa-solid fa-paper-plane {{ app()->getLocale() === 'ar' ? 'rotate-180' : '' }}"></i>
                </button>
            </form>
            <div class="flex items-center justify-between px-1 pt-1.5 text-[10px] text-slate-500">
                <span>{{ app()->getLocale() === 'ar' ? 'مدعوم بـ Google Gemini' : 'Powered by Google Gemini' }}</span>
                <span>{{ app()->getLocale() === 'ar' ? 'أرتيزان للأعمال الخشبية' : 'Artisan Woodwork' }}</span>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     AI CHAT JAVASCRIPT CONTROLLER
     ========================================================================= -->
@push('scripts')
<script>
    const AI_CHAT_ROUTES = {
        init: "{{ route('ai.chat.init') }}",
        send: "{{ route('ai.chat.send') }}",
        orderIdea: "{{ route('ai.chat.order-idea') }}",
        clear: "{{ route('ai.chat.clear') }}"
    };

    let aiSessionToken = localStorage.getItem('artisan_ai_session_token') || '';
    let isAiChatOpen = false;
    let isAiResponding = false;
    let selectedAiImageFile = null;

    // Open Chat Window
    function openAiChat() {
        const win = document.getElementById('aiChatWindow');
        const iconOpen = document.getElementById('aiIconOpen');
        const iconClose = document.getElementById('aiIconClose');
        const bubble = document.getElementById('aiWelcomeBubble');

        isAiChatOpen = true;
        win.classList.remove('hidden');
        if (iconOpen) iconOpen.classList.add('hidden');
        if (iconClose) iconClose.classList.remove('hidden');
        if (bubble) bubble.classList.add('hidden');

        initAiSession();
        setTimeout(() => {
            document.getElementById('aiMessageInput')?.focus();
            scrollAiToBottom();
        }, 120);
    }

    // Close Chat Window
    function closeAiChat() {
        const win = document.getElementById('aiChatWindow');
        const iconOpen = document.getElementById('aiIconOpen');
        const iconClose = document.getElementById('aiIconClose');

        isAiChatOpen = false;
        if (win) win.classList.add('hidden');
        if (iconOpen) iconOpen.classList.remove('hidden');
        if (iconClose) iconClose.classList.add('hidden');
    }

    // Toggle Chat Window
    function toggleAiChat() {
        if (isAiChatOpen) {
            closeAiChat();
        } else {
            openAiChat();
        }
    }

    // Dismiss Initial Bubble
    function dismissAiBubble(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const bubble = document.getElementById('aiWelcomeBubble');
        if (bubble) {
            bubble.style.setProperty('display', 'none', 'important');
            bubble.remove();
        }
    }

    // Escape Key Listener to Close Chat
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isAiChatOpen) {
            closeAiChat();
        }
    });

    // Initialize or Resume AI Chat
    async function initAiSession() {
        const loadingInit = document.getElementById('aiChatLoadingInit');
        try {
            const res = await fetch(`${AI_CHAT_ROUTES.init}?session_token=${encodeURIComponent(aiSessionToken)}`);
            const data = await res.json();

            if (data.success) {
                aiSessionToken = data.session_token;
                localStorage.setItem('artisan_ai_session_token', aiSessionToken);

                // Update Bot Name & Role
                document.getElementById('aiHeaderBotName').textContent = data.bot.name;

                // Render Quick Chips
                renderQuickChips(data.quick_chips);

                // Render Messages
                const container = document.getElementById('aiChatMessages');
                container.innerHTML = '';

                // Add Welcome Message if no history
                if (!data.messages || data.messages.length === 0) {
                    appendBotMessage(data.bot.welcome_message, [], null, '{{ now()->format("H:i") }}');
                } else {
                    data.messages.forEach(msg => {
                        if (msg.sender === 'user') {
                            appendUserMessage(msg.message, msg.image_url, msg.created_at);
                        } else {
                            appendBotMessage(msg.message, msg.metadata?.suggested_ideas || [], msg.metadata?.order || null, msg.created_at);
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error initializing AI Chat:', error);
            const container = document.getElementById('aiChatMessages');
            container.innerHTML = `<div class="p-3 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-center">تعذر الاتصال بالمستشار الذكي حالياً. يرجى المحاولة لاحقاً.</div>`;
        } finally {
            if (loadingInit) loadingInit.remove();
            scrollAiToBottom();
        }
    }

    // Render Quick Chips
    function renderQuickChips(chips) {
        const container = document.getElementById('aiQuickChips');
        if (!chips || chips.length === 0) {
            container.classList.add('hidden');
            return;
        }
        container.classList.remove('hidden');
        container.innerHTML = chips.map(chip => `
            <button type="button" onclick="sendQuickChip('${chip.replace(/'/g, "\\'")}')" class="px-3 py-1.5 rounded-full bg-white/5 hover:bg-gold-500/20 text-slate-300 hover:text-gold-400 border border-white/10 hover:border-gold-500/40 text-[11px] whitespace-nowrap transition">
                ${chip}
            </button>
        `).join('');
    }

    function sendQuickChip(text) {
        document.getElementById('aiMessageInput').value = text;
        sendAiMessage(new Event('submit'));
    }

    // Handle Input Enter Key
    function handleAiInputKeydown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendAiMessage(e);
        }
    }

    // Image Upload Handlers
    function handleAiImageSelect(input) {
        if (input.files && input.files[0]) {
            selectedAiImageFile = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('aiImagePreviewThumb').src = e.target.result;
                document.getElementById('aiImagePreviewName').textContent = selectedAiImageFile.name;
                document.getElementById('aiImagePreviewContainer').classList.remove('hidden');
            };
            reader.readAsDataURL(selectedAiImageFile);
        }
    }

    function removeSelectedAiImage() {
        selectedAiImageFile = null;
        document.getElementById('aiImageUploadInput').value = '';
        document.getElementById('aiImagePreviewContainer').classList.add('hidden');
    }

    // Send AI Message
    async function sendAiMessage(e) {
        e.preventDefault();
        if (isAiResponding) return;

        const input = document.getElementById('aiMessageInput');
        const text = input.value.trim();
        const hasImage = !!selectedAiImageFile;

        if (!text && !hasImage) return;

        const imageThumbSrc = hasImage ? document.getElementById('aiImagePreviewThumb').src : null;

        // Append User Message to UI
        const nowTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        appendUserMessage(text, imageThumbSrc, nowTime);

        // Reset input and attachments
        input.value = '';
        removeSelectedAiImage();
        scrollAiToBottom();

        // Show Typing Indicator
        isAiResponding = true;
        showTypingIndicator();

        const formData = new FormData();
        formData.append('session_token', aiSessionToken);
        formData.append('message', text);
        if (selectedAiImageFile) {
            formData.append('image', selectedAiImageFile);
        }

        try {
            const res = await fetch(AI_CHAT_ROUTES.send, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();
            removeTypingIndicator();

            if (data.success) {
                appendBotMessage(data.reply, data.suggested_ideas || [], data.order || null, data.created_at);
            } else {
                appendBotMessage('عذراً، حدث خطأ أثناء معالجة الرد. يرجى المحاولة مرة أخرى.', [], null, nowTime);
            }
        } catch (error) {
            console.error('Error in sendAiMessage:', error);
            removeTypingIndicator();
            appendBotMessage('حدث خطأ في الاتصال. يرجى التأكد من اتصال الإنترنت والمحاولة ثانية.', [], null, nowTime);
        } finally {
            isAiResponding = false;
            scrollAiToBottom();
        }
    }

    // UI Message Builders
    function appendUserMessage(text, imageUrl, time) {
        const container = document.getElementById('aiChatMessages');
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex items-end gap-2 justify-end';

        let imgHtml = imageUrl ? `<div class="mb-2"><img src="${imageUrl}" class="rounded-xl max-h-40 object-cover border border-white/20"></div>` : '';
        let textHtml = text ? `<p class="whitespace-pre-line leading-relaxed">${escapeHtml(text)}</p>` : '';

        msgDiv.innerHTML = `
            <div class="max-w-[85%] space-y-1">
                <div class="p-3.5 rounded-2xl rounded-bl-sm bg-gold-gradient text-slate-950 font-medium shadow-md">
                    ${imgHtml}
                    ${textHtml}
                </div>
                <span class="text-[10px] text-slate-400 block text-left px-1">${time}</span>
            </div>
        `;
        container.appendChild(msgDiv);
    }

    function appendBotMessage(text, suggestedIdeas, order, time) {
        const container = document.getElementById('aiChatMessages');
        const msgDiv = document.createElement('div');
        msgDiv.className = 'flex items-start gap-2.5';

        let parsedText = formatMarkdown(text);

        // Render Design Cards if present
        let cardsHtml = '';
        if (suggestedIdeas && suggestedIdeas.length > 0) {
            cardsHtml = `
                <div class="space-y-3 pt-3">
                    <span class="text-[11px] font-bold text-gold-400 block flex items-center gap-1">
                        <i class="fa-solid fa-lightbulb"></i>
                        <span>${"{{ app()->getLocale() === 'ar' ? 'تصاميم مقترحة من بنك أفكار أرتيزان:' : 'Suggested Designs from Knowledge Bank:' }}"}</span>
                    </span>
                    <div class="space-y-2.5">
                        ${suggestedIdeas.map(idea => `
                            <div class="p-3 rounded-2xl bg-dark-950/80 border border-gold-500/30 space-y-2 group">
                                <div class="flex gap-3 items-start">
                                    <img src="${idea.image}" alt="${idea.title}" class="w-16 h-16 rounded-xl object-cover border border-white/10 flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-white text-xs truncate">${idea.title}</h4>
                                        <p class="text-[11px] text-slate-300 line-clamp-2 mt-0.5">${idea.description || ''}</p>
                                        <div class="flex items-center gap-2 mt-1 text-[10px] text-gold-400">
                                            <span><i class="fa-solid fa-tree"></i> ${idea.wood_type || 'خشب طبيعي'}</span>
                                            ${idea.price ? `<span>• ${idea.price}</span>` : ''}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 pt-1 border-t border-white/5">
                                    ${idea.pinterest_url ? `
                                        <a href="${idea.pinterest_url}" target="_blank" class="px-2.5 py-1 rounded-lg bg-red-600/20 text-red-400 hover:bg-red-600/30 text-[10px] font-bold flex items-center gap-1 transition">
                                            <i class="fa-brands fa-pinterest"></i>
                                            <span>Pinterest</span>
                                        </a>
                                    ` : ''}
                                    <button type="button" onclick="openInlineOrderForm(${idea.id}, '${idea.title.replace(/'/g, "\\'")}', '${(idea.dimensions || '').replace(/'/g, "\\'")}')" class="flex-1 py-1.5 rounded-lg bg-gold-gradient text-slate-950 font-bold text-[10px] text-center shadow transition hover:brightness-110 flex items-center justify-center gap-1">
                                        <i class="fa-solid fa-file-signature text-[9px]"></i>
                                        <span>${"{{ app()->getLocale() === 'ar' ? 'طلب تفصيل هذا الموديل' : 'Order This Model' }}"}</span>
                                    </button>
                                </div>
                                <!-- Inline Order Form Container (Hidden by default) -->
                                <div id="inlineOrderForm_${idea.id}" class="hidden pt-2 border-t border-white/10 space-y-2">
                                    <input type="text" id="orderCustName_${idea.id}" placeholder="${"{{ app()->getLocale() === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}"}" class="w-full bg-dark-900 border border-white/10 rounded-lg px-2.5 py-1.5 text-[11px] text-white focus:outline-none focus:border-gold-500">
                                    <input type="tel" id="orderCustPhone_${idea.id}" placeholder="${"{{ app()->getLocale() === 'ar' ? 'رقم الجوال / الواتساب *' : 'Phone / WhatsApp *' }}"}" dir="ltr" class="w-full bg-dark-900 border border-white/10 rounded-lg px-2.5 py-1.5 text-[11px] text-white focus:outline-none focus:border-gold-500 text-right">
                                    <input type="text" id="orderCustDims_${idea.id}" value="${idea.dimensions || ''}" placeholder="${"{{ app()->getLocale() === 'ar' ? 'المقاسات المطلوبة (اختياري)' : 'Dimensions (Optional)' }}"}" class="w-full bg-dark-900 border border-white/10 rounded-lg px-2.5 py-1.5 text-[11px] text-white focus:outline-none focus:border-gold-500">
                                    <button type="button" onclick="submitInlineOrder(${idea.id})" class="w-full py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-[11px] shadow transition">
                                        ${"{{ app()->getLocale() === 'ar' ? 'تأكيد وإرسال طلب التفصيل' : 'Confirm & Submit Order' }}"}
                                    </button>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        // Render Order Tracking Badge if present
        let orderBadgeHtml = '';
        if (order) {
            orderBadgeHtml = `
                <div class="mt-3 p-3 rounded-2xl bg-emerald-950/60 border border-emerald-500/40 text-emerald-300 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold flex items-center gap-1.5 text-xs">
                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                            <span>${"{{ app()->getLocale() === 'ar' ? 'تم إنشاء الطلب بنجاح' : 'Order Created Successfully' }}"}</span>
                        </span>
                        <span class="font-mono font-bold text-white bg-emerald-800/60 px-2 py-0.5 rounded text-[11px]">${order.order_number}</span>
                    </div>
                    <p class="text-[11px] text-emerald-200/80">${"{{ app()->getLocale() === 'ar' ? 'تم تحويل بياناتك إلى ورشتنا، وسيقوم مهندسونا بدراسة الطلب والتواصل معك.' : 'Your order has been submitted to our engineering workshop.' }}"}</p>
                    <a href="${order.tracking_url}" class="inline-block pt-1 text-xs font-bold text-gold-400 hover:text-gold-300 underline">
                        ${"{{ app()->getLocale() === 'ar' ? 'عرض تفاصيل وتتبع الطلب ←' : 'View & Track Order Details →' }}"}
                    </a>
                </div>
            `;
        }

        msgDiv.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-gradient-to-br from-wood-600 to-wood-800 text-white flex items-center justify-center text-xs flex-shrink-0 shadow mt-1">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="max-w-[85%] space-y-1">
                <div class="p-3.5 rounded-2xl rounded-tl-sm bg-dark-950 border border-white/10 text-slate-200 leading-relaxed shadow-md">
                    <div class="ai-bot-content space-y-2">${parsedText}</div>
                    ${cardsHtml}
                    ${orderBadgeHtml}
                </div>
                <span class="text-[10px] text-slate-400 block px-1">${time}</span>
            </div>
        `;
        container.appendChild(msgDiv);
    }

    // Inline Quick Order Submission Handlers
    function openInlineOrderForm(ideaId, title, dims) {
        const form = document.getElementById(`inlineOrderForm_${ideaId}`);
        if (form) {
            form.classList.toggle('hidden');
        }
    }

    async function submitInlineOrder(ideaId) {
        const nameInput = document.getElementById(`orderCustName_${ideaId}`);
        const phoneInput = document.getElementById(`orderCustPhone_${ideaId}`);
        const dimsInput = document.getElementById(`orderCustDims_${ideaId}`);

        const name = nameInput.value.trim();
        const phone = phoneInput.value.trim();
        const dims = dimsInput.value.trim();

        if (!name || !phone) {
            alert("{{ app()->getLocale() === 'ar' ? 'يرجى إدخال الاسم ورقم الجوال لتسجيل الطلب' : 'Please enter your name and phone number' }}");
            return;
        }

        try {
            const res = await fetch(AI_CHAT_ROUTES.orderIdea, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_token: aiSessionToken,
                    idea_id: ideaId,
                    customer_name: name,
                    customer_phone: phone,
                    custom_dimensions: dims,
                })
            });

            const data = await res.json();
            if (data.success) {
                // Hide form
                document.getElementById(`inlineOrderForm_${ideaId}`).classList.add('hidden');
                appendBotMessage(data.message, [], { order_number: data.order_number, tracking_url: data.tracking_url }, new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }));
                scrollAiToBottom();
            } else {
                alert(data.message || 'حدث خطأ أثناء تسجيل الطلب.');
            }
        } catch (e) {
            alert('تعذر إرسال الطلب، يرجى المحاولة لاحقاً.');
        }
    }

    // Typing Indicator
    function showTypingIndicator() {
        const container = document.getElementById('aiChatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.id = 'aiTypingIndicator';
        typingDiv.className = 'flex items-start gap-2.5 animate-pulse';
        typingDiv.innerHTML = `
            <div class="w-7 h-7 rounded-xl bg-wood-700 text-white flex items-center justify-center text-xs flex-shrink-0 mt-1">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="p-3 rounded-2xl rounded-tl-sm bg-dark-950 border border-white/10 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-bounce [animation-delay:0.2s]"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-gold-400 animate-bounce [animation-delay:0.4s]"></span>
            </div>
        `;
        container.appendChild(typingDiv);
        scrollAiToBottom();
    }

    function removeTypingIndicator() {
        const ind = document.getElementById('aiTypingIndicator');
        if (ind) ind.remove();
    }

    // Clear Chat
    async function clearAiChat() {
        if (!confirm("{{ app()->getLocale() === 'ar' ? 'هل أنت متأكد من رغبتك في مسح المحادثة وبدء جلسة جديدة؟' : 'Are you sure you want to clear chat and start fresh?' }}")) return;

        try {
            await fetch(AI_CHAT_ROUTES.clear, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ session_token: aiSessionToken })
            });

            aiSessionToken = '';
            localStorage.removeItem('artisan_ai_session_token');
            initAiSession();
        } catch (e) {
            console.error('Error clearing chat:', e);
        }
    }

    // Helpers
    function scrollAiToBottom() {
        const container = document.getElementById('aiChatMessages');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = escapeHtml(text);
        // Bold: **text**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-bold text-white">$1</strong>');
        // Code: `code`
        escaped = escaped.replace(/`(.*?)`/g, '<code class="px-1.5 py-0.5 rounded bg-dark-900 text-gold-400 font-mono text-[11px] border border-white/10">$1</code>');
        // Links: [text](url)
        escaped = escaped.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" class="text-gold-400 hover:text-gold-300 font-bold underline" target="_blank">$1</a>');
        // Line breaks
        escaped = escaped.replace(/\n/g, '<br>');
        return escaped;
    }
</script>
@endpush
@endif
