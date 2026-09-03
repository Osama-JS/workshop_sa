@extends('admin.layouts.master')

@section('title', 'بنك الأسئلة والأجوبة للمساعد الذكي')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-gold-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>{{ __('admin.menu_ai_hub') }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">بنك الأسئلة والأجوبة المعتمدة (AI Q&A Bank)</h1>
            <p class="text-xs text-slate-500 mt-1">تغذية وتدريب المساعد الذكي على الإجابات الرسمية والسياسات الخاصة بالمنصة</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ai-faqs.create') }}" class="px-5 py-2.5 rounded-2xl bg-wood-600 hover:bg-wood-700 text-white font-bold text-xs shadow-lg shadow-wood-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>إضافة سؤال وجواب جديد</span>
            </a>
        </div>
    </div>

    <!-- AI Hub Tabs Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <a href="{{ route('admin.ai-ideas.index') }}" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center gap-2">
            <i class="fa-solid fa-lightbulb text-wood-600"></i>
            <span>أفكار وتصاميم الذكاء الاصطناعي ({{ \App\Models\AiDesignIdea::count() }})</span>
        </a>
        <a href="{{ route('admin.ai-faqs.index') }}" class="px-5 py-2.5 rounded-2xl bg-wood-600 text-white font-bold text-xs shadow-md flex items-center gap-2">
            <i class="fa-solid fa-circle-question text-gold-400"></i>
            <span>بنك الأسئلة والأجوبة المعتمدة ({{ \App\Models\AiFaq::count() }})</span>
        </a>
        <a href="{{ route('admin.ai-logs.index') }}" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center gap-2">
            <i class="fa-solid fa-comments text-wood-600"></i>
            <span>سجلات محادثات العملاء ({{ \App\Models\AiChatSession::count() }})</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.ai-faqs.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <i class="fa-solid fa-magnifying-glass absolute top-3 right-3 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث في الأسئلة أو الأجوبة أو الكلمات المفتاحية..." class="w-full pr-8 pl-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-wood-600">
            </div>

            <select name="category" onchange="this.form.submit()" class="text-xs border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-wood-600">
                <option value="">{{ __('admin.all') }} - التصنيفات</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                {{ __('admin.filter') }}
            </button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.ai-faqs.index') }}" class="text-xs text-rose-500 hover:underline">{{ __('admin.reset') }}</a>
            @endif
        </form>
    </div>

    <!-- FAQs Table -->
    <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-right text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase">
                    <tr>
                        <th class="p-4">#</th>
                        <th class="p-4">السؤال والكلمات المفتاحية</th>
                        <th class="p-4">الإجابة المعتمدة</th>
                        <th class="p-4">التصنيف</th>
                        <th class="p-4">الحالة</th>
                        <th class="p-4 text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="p-4 font-mono text-slate-400 font-bold">{{ $faq->id }}</td>
                            <td class="p-4 max-w-xs space-y-1">
                                <div class="font-bold text-slate-900 text-sm leading-snug">{{ $faq->question_ar }}</div>
                                @if($faq->question_en)
                                    <div class="text-[11px] text-slate-400 font-medium" dir="ltr">{{ $faq->question_en }}</div>
                                @endif
                                @if($faq->keywords)
                                    <div class="flex items-center gap-1 flex-wrap pt-1">
                                        @foreach(explode(',', $faq->keywords) as $kw)
                                            @if(trim($kw))
                                                <span class="px-2 py-0.5 rounded-md bg-wood-50 text-wood-700 text-[10px] font-mono">#{{ trim($kw) }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </td>
                            <td class="p-4 max-w-sm">
                                <div class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                                    {{ $faq->answer_ar }}
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-100 text-slate-700">
                                    {{ $categories[$faq->category] ?? $faq->category }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($faq->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        مفعل في الـ AI
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500">
                                        معطل
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.ai-faqs.edit', $faq) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-wood-600 hover:text-white transition text-slate-600" title="تعديل">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.ai-faqs.destroy', $faq) }}" data-confirm="هل أنت متأكد من حذف هذا السؤال من بنك المعرفة نهائياً؟">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-slate-100 hover:bg-rose-600 hover:text-white transition text-rose-500" title="حذف">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400">
                                <i class="fa-solid fa-circle-question text-4xl mb-3 block text-slate-300"></i>
                                <span>لا توجد أسئلة مضافة في بنك المعرفة حالياً.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($faqs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $faqs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
