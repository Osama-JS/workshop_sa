<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard')) - {{ \App\Models\Setting::get('site_name_' . app()->getLocale(), 'أرتيزان للأعمال الخشبية') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Local FontAwesome 6 Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/all.min.css') }}">
    
    <!-- Local Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/quill/quill.snow.css') }}">

    <!-- Tailwind CSS (CDN / Play) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        wood: {
                            50: '#fbf8f5',
                            100: '#f5efe8',
                            200: '#ebdfd2',
                            300: '#dec7b0',
                            400: '#cba888',
                            500: '#b88b64',
                            600: '#9d7049',
                            700: '#815638',
                            800: '#69452e',
                            900: '#553926',
                            950: '#2d1c12',
                        },
                        gold: {
                            400: '#E5C158',
                            500: '#D4AF37',
                            600: '#B89324',
                        },
                        dark: {
                            800: '#1e1b18',
                            900: '#141210',
                            950: '#0c0a09',
                        }
                    },
                    fontFamily: {
                        sans: "['Cairo', 'sans-serif']",
                    }
                }
            }
        }
    </script>

    <!-- Local Core JS loaded in head to ensure availability -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('vendor/quill/quill.js') }}"></script>

    <style>
        body {
            font-family: 'Cairo', sans-serif !important;
            background-color: #f8fafc;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cba888;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9d7049;
        }
        /* Select2 Custom Luxury Styling */
        .select2-container {
            width: 100% !important;
        }
        .select2-container--default .select2-selection--single {
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            height: 42px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 10px !important;
            background-color: #f8fafc !important;
            transition: all 0.2s ease !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #9d7049 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(157, 112, 73, 0.15) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e293b !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            line-height: 42px !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px !important;
            {{ app()->getLocale() === 'ar' ? 'left: 10px !important; right: auto !important;' : 'right: 10px !important; left: auto !important;' }}
        }
        .select2-container--default .select2-selection--multiple {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            min-height: 42px !important;
            padding: 4px 8px !important;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #9d7049 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(157, 112, 73, 0.15) !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #9d7049 !important;
            border: none !important;
            color: #ffffff !important;
            border-radius: 0.5rem !important;
            padding: 2px 8px !important;
            font-size: 0.75rem !important;
            font-weight: bold !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ffffff !important;
            margin-inline-end: 4px !important;
        }
        .select2-dropdown {
            background-color: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
            z-index: 99999 !important;
        }
        .select2-search--dropdown {
            padding: 8px !important;
            background-color: #f8fafc !important;
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            padding: 6px 10px !important;
            font-size: 0.75rem !important;
            outline: none !important;
        }
        .select2-container--default .select2-results__option {
            padding: 8px 12px !important;
            font-size: 0.8rem !important;
            color: #334155 !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected],
        .select2-container--default .select2-results__option--selected {
            background-color: #9d7049 !important;
            color: #ffffff !important;
        }

        /* Flatpickr Luxury Custom Styling */
        .flatpickr-calendar {
            border-radius: 1rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15) !important;
            font-family: inherit !important;
            background: #ffffff !important;
            overflow: hidden !important;
        }
        .flatpickr-months {
            background: linear-gradient(135deg, #9d7049 0%, #815638 100%) !important;
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
            color: #ffffff !important;
            padding: 8px 0 !important;
        }
        .flatpickr-months .flatpickr-month,
        .flatpickr-current-month,
        .flatpickr-current-month input.cur-year,
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #ffffff !important;
            font-weight: bold !important;
            fill: #ffffff !important;
        }
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            fill: #ffffff !important;
            color: #ffffff !important;
        }
        .flatpickr-weekdays {
            background: #fdfaf7 !important;
            border-bottom: 1px solid #f0e6dd !important;
        }
        span.flatpickr-weekday {
            color: #815638 !important;
            font-weight: bold !important;
            font-size: 0.75rem !important;
        }
        .flatpickr-day {
            border-radius: 0.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            color: #334155 !important;
            transition: all 0.15s ease !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #9d7049 !important;
            border-color: #9d7049 !important;
            color: #ffffff !important;
            font-weight: bold !important;
            box-shadow: 0 4px 10px rgba(157, 112, 73, 0.3) !important;
        }
        .flatpickr-day:hover {
            background: #f5efe8 !important;
            color: #815638 !important;
        }
        .flatpickr-day.today {
            border-color: #D4AF37 !important;
            color: #9d7049 !important;
            font-weight: bold !important;
        }
        .flatpickr-day.today:hover {
            background: #D4AF37 !important;
            color: #ffffff !important;
        }

        /* Quill Editor styling */
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
            border-color: #e2e8f0;
            background-color: #f8fafc;
        }
        .ql-container.ql-snow {
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            border-color: #e2e8f0;
            font-family: inherit;
            min-height: 180px;
            background-color: #ffffff;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden backdrop-blur-sm transition-opacity"></div>

    <!-- Sidebar Component -->
    @include('admin.layouts.sidebar')

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Navbar Component -->
        @include('admin.layouts.navbar')

        <!-- Scrollable Page Content -->
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Page Header / Breadcrumb -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            @yield('page_icon')
                            @yield('page_title', __('admin.dashboard'))
                        </h1>
                        @hasSection('page_subtitle')
                            <p class="text-sm text-slate-500 mt-1">@yield('page_subtitle')</p>
                        @endif
                    </div>
                    <div>
                        @yield('page_actions')
                    </div>
                </div>

                <!-- Page Body Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer Component -->
        @include('admin.layouts.footer')
    </div>

    <script>
        // Toastr Configurations (if toastr loaded)
        if (typeof toastr !== 'undefined') {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "{{ app()->getLocale() === 'ar' ? 'toast-top-left' : 'toast-top-right' }}",
                "timeOut": "4000",
            };

            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if(session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        }

        // Sidebar Toggle for Mobile & Desktop (Vanilla JS)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (!sidebar) return;

            if (sidebar.classList.contains('-translate-x-full') || (sidebar.classList.contains('translate-x-full'))) {
                sidebar.classList.remove('-translate-x-full', 'translate-x-full');
                if (backdrop) backdrop.classList.remove('hidden');
            } else {
                if ("{{ app()->getLocale() }}" === 'ar') {
                    sidebar.classList.add('translate-x-full');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
                if (backdrop) backdrop.classList.add('hidden');
            }
        }



        // Global SweetAlert Confirm Delete Handler
        if (typeof $ !== 'undefined') {
            $(document).on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: "{{ __('admin.confirm_delete') }}",
                        text: "{{ __('admin.confirm_delete_msg') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: "{{ __('admin.yes_delete') }}",
                        cancelButtonText: "{{ __('admin.cancel') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm("{{ __('admin.confirm_delete') }}")) {
                        form.submit();
                    }
                }
            });
        }

        // Global One-Click Automatic Translation Engine (Google Translate)
        function autoTranslate(sourceId, targetId, fromLang = 'ar', toLang = 'en', btn = null) {
            const sourceEl = document.getElementById(sourceId);
            const targetEl = document.getElementById(targetId);

            if (!sourceEl || !targetEl) return;

            let text = '';
            // If Quill instance exists
            if (sourceEl.dataset && sourceEl.dataset.quillId) {
                const quillInst = window[sourceEl.dataset.quillId];
                if (quillInst) text = quillInst.getText().trim();
            } else {
                text = sourceEl.value ? sourceEl.value.trim() : '';
            }

            if (!text) {
                if (typeof toastr !== 'undefined') {
                    toastr.warning("{{ app()->getLocale() === 'ar' ? 'يرجى إدخال النص أولاً لترجمته' : 'Please enter source text first' }}");
                } else {
                    alert("{{ app()->getLocale() === 'ar' ? 'يرجى إدخال النص أولاً لترجمته' : 'Please enter source text first' }}");
                }
                return;
            }

            let origBtnHtml = '';
            if (btn) {
                origBtnHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> {{ __("admin.translating") }}';
            }

            fetch("{{ route('admin.translate') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    text: text,
                    from: fromLang,
                    to: toLang
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.translated) {
                    if (targetEl.dataset && targetEl.dataset.quillId) {
                        const targetQuill = window[targetEl.dataset.quillId];
                        if (targetQuill) {
                            targetQuill.setText(data.translated);
                            targetEl.value = targetQuill.root.innerHTML;
                        }
                    } else {
                        targetEl.value = data.translated;
                        if (typeof $ !== 'undefined') $(targetEl).trigger('input');
                    }
                    if (typeof toastr !== 'undefined') {
                        toastr.success("{{ app()->getLocale() === 'ar' ? 'تمت الترجمة التلقائية بنجاح!' : 'Translated successfully!' }}");
                    }
                }
            })
            .catch(err => {
                if (typeof toastr !== 'undefined') {
                    toastr.error("{{ app()->getLocale() === 'ar' ? 'تعذر إتمام الترجمة، يرجى المحاولة لاحقاً' : 'Translation failed, please try again' }}");
                }
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = origBtnHtml;
                }
            });
        }

        // Web Audio API Synth Chime for instant alerts without external audio files
        function playNotificationSound() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                
                // Tone 1
                const osc1 = ctx.createOscillator();
                const gain1 = ctx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
                gain1.gain.setValueAtTime(0.2, ctx.currentTime);
                gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                osc1.connect(gain1);
                gain1.connect(ctx.destination);
                osc1.start();
                osc1.stop(ctx.currentTime + 0.3);

                // Tone 2 (Higher pitch chime)
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(880, ctx.currentTime + 0.15); // A5
                gain2.gain.setValueAtTime(0.25, ctx.currentTime + 0.15);
                gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.start(ctx.currentTime + 0.15);
                osc2.stop(ctx.currentTime + 0.5);
            } catch (e) {
                console.log('Audio notification error:', e);
            }
        }

        // Real-Time Web Push & AJAX Live Listener
        let lastNotifCheck = Math.floor(Date.now() / 1000);

        function checkLiveNotifications() {
            fetch("{{ route('admin.notifications.check') }}?since=" + lastNotifCheck, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.timestamp) {
                    lastNotifCheck = data.timestamp;
                }

                // Update Badges
                const ordersBadge = document.getElementById('sidebarPendingOrdersBadge');
                if (ordersBadge) {
                    ordersBadge.innerText = data.pending_orders_count;
                    if (data.pending_orders_count > 0) ordersBadge.classList.remove('hidden');
                    else ordersBadge.classList.add('hidden');
                }

                const messagesBadge = document.getElementById('sidebarUnreadMessagesBadge');
                if (messagesBadge) {
                    messagesBadge.innerText = data.unread_messages_count;
                    if (data.unread_messages_count > 0) messagesBadge.classList.remove('hidden');
                    else messagesBadge.classList.add('hidden');
                }

                // Handle New Incoming Orders
                if (data.new_orders && data.new_orders.length > 0) {
                    playNotificationSound();
                    data.new_orders.forEach(order => {
                        const title = "{{ app()->getLocale() === 'ar' ? 'طلب تفصيل جديد!' : 'New Custom Order!' }}";
                        const body = `${order.order_number} - ${order.customer_name}`;

                        if (typeof toastr !== 'undefined') {
                            toastr.success(body, title, { timeOut: 8000 });
                        }

                        // Browser Desktop Notification
                        if ("Notification" in window && Notification.permission === "granted") {
                            new Notification(title, {
                                body: body,
                                icon: "{{ asset('vendor/fontawesome/webfonts/fa-solid-900.woff2') }}"
                            });
                        }
                    });
                }

                // Handle New Incoming Contact Messages
                if (data.new_messages && data.new_messages.length > 0) {
                    playNotificationSound();
                    data.new_messages.forEach(msg => {
                        const title = "{{ app()->getLocale() === 'ar' ? 'رسالة تواصل جديدة!' : 'New Contact Message!' }}";
                        const body = `${msg.name}: ${msg.subject || 'استفسار'}`;

                        if (typeof toastr !== 'undefined') {
                            toastr.info(body, title, { timeOut: 8000 });
                        }

                        if ("Notification" in window && Notification.permission === "granted") {
                            new Notification(title, {
                                body: body
                            });
                        }
                    });
                }
            })
            .catch(err => {
                // Silently ignore network hiccup during background polling
            });
        }

        // Universal Select2 & Flatpickr Initializer
        function initAllSelect2AndDatepickers() {
            // 1. Select2 for ALL <select> tags across the entire admin panel
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                $('select:not(.no-select2)').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        let config = {
                            dir: "{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}",
                            width: '100%',
                            language: {
                                noResults: function() {
                                    return "{{ app()->getLocale() === 'ar' ? 'لا توجد نتائج مطابقة' : 'No results found' }}";
                                }
                            }
                        };

                        const placeholderText = $(this).data('placeholder') || $(this).attr('data-placeholder');
                        if (placeholderText && $(this).find('option[value=""]').length > 0) {
                            config.placeholder = placeholderText;
                            config.allowClear = true;
                        }

                        $(this).select2(config);
                    }
                });
            }

            // 2. Flatpickr for ALL date inputs across the entire admin panel
            if (typeof flatpickr !== 'undefined') {
                flatpickr.l10ns.ar = {
                    weekdays: {
                        shorthand: ["أحد", "اثنين", "ثلاثاء", "أربعاء", "خميس", "جمعة", "سبت"],
                        longhand: ["الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"]
                    },
                    months: {
                        shorthand: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                        longhand: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]
                    },
                    firstDayOfWeek: 6,
                    rangeSeparator: " إلى ",
                    weekAbbreviation: "أسبوع",
                    scrollTitle: "قم بالتمرير للزيادة",
                    toggleTitle: "اضغط للتبديل",
                    amPM: ["ص", "م"]
                };

                document.querySelectorAll('input[type="date"], input.datepicker, input.flatpickr, input[data-datepicker]').forEach(function(input) {
                    if (!input._flatpickr) {
                        if (input.type === 'date') {
                            input.type = 'text';
                        }
                        flatpickr(input, {
                            dateFormat: "Y-m-d",
                            altInput: true,
                            altFormat: "Y-m-d",
                            allowInput: true,
                            disableMobile: true,
                            locale: "{{ app()->getLocale() === 'ar' ? 'ar' : 'en' }}"
                        });
                    }
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2 & Datepickers immediately
            initAllSelect2AndDatepickers();

            // Request Browser Desktop Notification Permission on first interaction
            if ("Notification" in window && Notification.permission === "default") {
                Notification.requestPermission();
            }

            // Start Live Polling every 15 seconds
            setInterval(checkLiveNotifications, 15000);
        });
    </script>
    @stack('scripts')
</body>
</html>
