@extends('admin.layouts.master')

@section('title', __('admin.menu_ai_ideas'))

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold text-gold-600 uppercase tracking-wider mb-1">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>{{ __('admin.menu_ai_hub') }}</span>
            </div>
            <h1 class="text-2xl font-black text-slate-900">{{ __('admin.ai_ideas_list') }}</h1>
            <p class="text-xs text-slate-500 mt-1">{{ __('admin.menu_ai_ideas') }} - {{ __('admin.ai_settings') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.ai-ideas.create') }}" class="px-5 py-2.5 rounded-2xl bg-wood-600 hover:bg-wood-700 text-white font-bold text-xs shadow-lg shadow-wood-600/20 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                <span>{{ __('admin.create_ai_idea') }}</span>
            </a>
        </div>
    </div>

    <!-- AI Hub Tabs Navigation -->
    <div class="flex items-center gap-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <a href="{{ route('admin.ai-ideas.index') }}" class="px-5 py-2.5 rounded-2xl bg-wood-600 text-white font-bold text-xs shadow-md flex items-center gap-2">
            <i class="fa-solid fa-lightbulb"></i>
            <span>أفكار وتصاميم الذكاء الاصطناعي ({{ \App\Models\AiDesignIdea::count() }})</span>
        </a>
        <a href="{{ route('admin.ai-faqs.index') }}" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center gap-2">
            <i class="fa-solid fa-circle-question text-gold-500"></i>
            <span>بنك الأسئلة والأجوبة المعتمدة ({{ \App\Models\AiFaq::count() }})</span>
        </a>
        <a href="{{ route('admin.ai-logs.index') }}" class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs border border-slate-200 transition flex items-center gap-2">
            <i class="fa-solid fa-comments text-wood-600"></i>
            <span>سجلات محادثات العملاء ({{ \App\Models\AiChatSession::count() }})</span>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form method="GET" action="{{ route('admin.ai-ideas.index') }}" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fa-solid fa-magnifying-glass absolute top-3 right-3 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('admin.search') }}" class="w-full pr-8 pl-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:border-wood-600">
            </div>

            <select name="category" onchange="this.form.submit()" class="text-xs border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:border-wood-600">
                <option value="">{{ __('admin.all') }} - {{ __('admin.category') }}</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                {{ __('admin.filter') }}
            </button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('admin.ai-ideas.index') }}" class="text-xs text-rose-500 hover:underline">{{ __('admin.reset') }}</a>
            @endif
        </form>
    </div>

    <!-- Ideas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($ideas as $idea)
            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between group">
                <div>
                    <!-- Image -->
                    <div class="h-48 relative overflow-hidden bg-slate-100">
                        <img src="{{ $idea->image_url }}" alt="{{ $idea->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-3 right-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-white text-[10px] font-bold">
                                {{ $categories[$idea->category] ?? $idea->category }}
                            </span>
                            @if($idea->is_active)
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/90 text-white text-[9px] font-bold">نشط</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-slate-500/90 text-white text-[9px] font-bold">معطل</span>
                            @endif
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-5 space-y-3">
                        <h3 class="font-bold text-slate-900 text-sm line-clamp-1 group-hover:text-wood-600 transition">
                            {{ $idea->title_ar }}
                        </h3>
                        @if($idea->title_en)
                            <p class="text-[11px] text-slate-400 font-mono line-clamp-1" dir="ltr">{{ $idea->title_en }}</p>
                        @endif

                        <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                            {{ $idea->description_ar ?: 'لا يوجد وصف مدخل لهذا التصميم.' }}
                        </p>

                        <!-- Wood & Price Badges -->
                        <div class="pt-2 border-t border-slate-100 flex flex-wrap gap-2 text-[11px] text-slate-600">
                            @if($idea->wood_type)
                                <span class="px-2 py-1 rounded-lg bg-wood-50 text-wood-700 font-medium">
                                    <i class="fa-solid fa-tree text-[10px]"></i> {{ $idea->wood_type }}
                                </span>
                            @endif
                            @if($idea->estimated_price_range)
                                <span class="px-2 py-1 rounded-lg bg-gold-50 text-gold-700 font-bold">
                                    <i class="fa-solid fa-tag text-[10px]"></i> {{ $idea->estimated_price_range }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        @if($idea->pinterest_url)
                            <a href="{{ $idea->pinterest_url }}" target="_blank" class="text-[11px] font-bold text-red-600 hover:text-red-700 flex items-center gap-1">
                                <i class="fa-brands fa-pinterest"></i>
                                <span>Pinterest</span>
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.ai-ideas.edit', $idea->id) }}" class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-wood-600 hover:border-wood-600 transition text-xs" title="تعديل">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.ai-ideas.destroy', $idea->id) }}" onsubmit="return confirm('هل أنت متأكد من حذف فكرة التصميم هذه؟');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-600 transition text-xs" title="حذف">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-slate-100 p-8 space-y-3">
                <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl mx-auto">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm">لا توجد أفكار تصاميم حالياً</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto">أضف نماذج من بنترست والأعمال الخشبية ليقترحها المساعد الذكي على الزوار أثناء المحادثة.</p>
                <a href="{{ route('admin.ai-ideas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-wood-600 text-white text-xs font-bold shadow transition hover:bg-wood-700">
                    <i class="fa-solid fa-plus"></i>
                    <span>إضافة تصميم الآن</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $ideas->links() }}
    </div>

</div>
@endsection
