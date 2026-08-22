@extends('frontend.layouts.app')

@section('title', $page->title . ' - ' . \App\Models\Setting::get('site_name_' . app()->getLocale()))
@section('meta_description', $page->meta_description ?: $page->title)

@section('content')
<!-- Page Header -->
<div class="py-20 bg-dark-950 border-b border-white/10 text-center relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 space-y-4 relative z-10">
        <h1 class="text-3xl sm:text-5xl font-black text-white">
            {{ $page->title }}
        </h1>
        <div class="w-16 h-1 bg-gold-500 mx-auto rounded-full mt-2"></div>
    </div>
</div>

<!-- Main Page Content -->
<div class="py-20 bg-dark-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-card rounded-3xl p-8 sm:p-12 space-y-6 text-slate-300 leading-relaxed text-sm sm:text-base prose prose-invert max-w-none">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
