@extends('admin.layouts.master')

@section('title', 'تفاصيل محادثة المساعد الذكي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-wood-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-comments"></i>
                <span>تفاصيل جلسة المحادثة</span>
            </div>
            <h1 class="text-xl font-black text-slate-900">
                محادثة: {{ $aiLog->user_name ?: 'زائر مجهول' }}
            </h1>
            <p class="text-xs text-slate-400 mt-0.5">
                تاريخ الجلسة: {{ $aiLog->created_at->format('Y-m-d H:i') }} | IP: {{ $aiLog->visitor_ip }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($aiLog->order)
                <a href="{{ route('admin.orders.show', $aiLog->order_id) }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>طلب التفصيل: {{ $aiLog->order->order_number }}</span>
                </a>
            @endif
            <a href="{{ route('admin.ai-logs.index') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-right"></i>
                <span>العودة للسجلات</span>
            </a>
        </div>
    </div>

    <!-- Chat Transcript Container -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8 space-y-6">
        <div class="space-y-4">
            @forelse($aiLog->messages as $msg)
                @if($msg->sender === 'user')
                    <!-- User Message -->
                    <div class="flex items-start gap-3 justify-end">
                        <div class="max-w-[80%] space-y-1">
                            <div class="p-4 rounded-2xl rounded-bl-sm bg-wood-600 text-white text-xs leading-relaxed shadow-sm">
                                @if($msg->image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $msg->image_path) }}" alt="attached" class="rounded-xl max-h-48 object-cover border border-white/20">
                                    </div>
                                @endif
                                <p class="whitespace-pre-line">{{ $msg->message }}</p>
                            </div>
                            <div class="text-[10px] text-slate-400 text-left px-1 flex items-center justify-end gap-1.5">
                                <span>{{ $aiLog->user_name ?: 'الزائر' }}</span>
                                <span>•</span>
                                <span class="font-mono">{{ $msg->created_at->format('H:i A') }}</span>
                            </div>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold flex-shrink-0 shadow">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                @else
                    <!-- Assistant Message -->
                    <div class="flex items-start gap-3 justify-start">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold-500 to-wood-600 text-slate-950 flex items-center justify-center text-xs font-bold flex-shrink-0 shadow">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </div>
                        <div class="max-w-[80%] space-y-1">
                            <div class="p-4 rounded-2xl rounded-tl-sm bg-slate-100 text-slate-800 text-xs leading-relaxed border border-slate-200">
                                <p class="whitespace-pre-line">{{ $msg->message }}</p>

                                @if(!empty($msg->metadata['suggested_ideas']))
                                    <div class="mt-3 pt-3 border-t border-slate-200 space-y-2">
                                        <span class="text-[11px] font-bold text-wood-700 block">التصاميم المقترحة في هذه الرسالة:</span>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($msg->metadata['suggested_ideas'] as $sIdea)
                                                <div class="p-2 rounded-xl bg-white border border-slate-200 text-[11px] space-y-1">
                                                    <div class="font-bold text-slate-900 truncate">{{ $sIdea['title'] ?? '' }}</div>
                                                    <div class="text-slate-500 text-[10px]">{{ $sIdea['wood_type'] ?? '' }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-[10px] text-slate-400 px-1 flex items-center gap-1.5">
                                <span>المساعد الذكي (Gemini)</span>
                                <span>•</span>
                                <span class="font-mono">{{ $msg->created_at->format('H:i A') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center py-12 text-slate-400 text-xs">
                    لا توجد رسائل مسجلة في هذه الجلسة.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
