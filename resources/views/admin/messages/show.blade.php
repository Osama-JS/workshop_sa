@extends('admin.layouts.master')

@section('title', 'عرض رسالة التواصل: ' . $message->name)

@section('page_icon')
    <i class="fa-solid fa-envelope-open-text text-wood-600"></i>
@endsection

@section('page_title', 'عرض رسالة التواصل')
@section('page_subtitle', 'قراءة استفسار العميل وتوثيق الرد')

@section('content')
<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.messages.index') }}" class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-2">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة لقائمة الرسائل</span>
        </a>

        <div class="flex items-center gap-3">
            @if($message->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}?text={{ urlencode('مرحباً ' . $message->name . '، رداً على رسالتكم بخصوص ' . ($message->subject ?: 'استفساركم') . '...') }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>رد عبر واتساب</span>
                </a>
            @endif

            <a href="mailto:{{ $message->email }}?subject={{ urlencode('رد على استفساركم: ' . $message->subject) }}" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-reply text-sm"></i>
                <span>رد عبر البريد</span>
            </a>

            <form method="POST" action="{{ route('admin.messages.destroy', $message->id) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" class="px-4 py-2 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 text-xs font-bold transition confirm-delete flex items-center gap-2">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>حذف</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Message Details & Reply Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <!-- Left: Message Body -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                <!-- Sender Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-wood-100 text-wood-700 flex items-center justify-center text-xl font-bold font-mono">
                            {{ mb_substr($message->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">{{ $message->name }}</h3>
                            <p class="text-xs text-slate-500 font-mono">{{ $message->email }}</p>
                        </div>
                    </div>
                    <div class="text-start sm:text-end text-xs text-slate-400 font-mono">
                        <div>{{ $message->created_at->format('Y-m-d H:i') }}</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">({{ $message->created_at->diffForHumans() }})</div>
                    </div>
                </div>

                <!-- Subject & Body -->
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-slate-400 block mb-1">الموضوع:</span>
                        <h4 class="text-base font-bold text-slate-800">{{ $message->subject ?: 'استفسار عام' }}</h4>
                    </div>

                    <div>
                        <span class="text-xs text-slate-400 block mb-2">نص الرسالة:</span>
                        <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200 text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $message->message }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Admin Reply Tracking & Notes -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-6">
                <h3 class="text-sm font-bold text-slate-800 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-notes-medical text-wood-600"></i>
                    <span>توثيق الرد والملاحظات</span>
                </h3>

                <form method="POST" action="{{ route('admin.messages.reply', $message->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">ملخص الرد المرسل للعميل:</label>
                        <textarea name="reply_notes" rows="6" required class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:bg-white focus:outline-none focus:border-wood-500 transition" placeholder="اكتب ملخص ما تم الرد به على العميل وتاريخ التواصل...">{{ old('reply_notes', $message->reply_notes) }}</textarea>
                    </div>

                    @if($message->replied_at)
                        <div class="p-3 bg-emerald-50 rounded-xl text-[11px] text-emerald-800 font-semibold flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i>
                            <span>تم توثيق الرد بتاريخ: {{ $message->replied_at->format('Y-m-d H:i') }}</span>
                        </div>
                    @endif

                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-wood-600 hover:bg-wood-700 text-white font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>حفظ توثيق الرد</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
