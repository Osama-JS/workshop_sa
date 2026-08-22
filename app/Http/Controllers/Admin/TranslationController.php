<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    public function translate(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'from' => 'nullable|string|in:ar,en',
            'to' => 'nullable|string|in:ar,en',
        ]);

        $text = $request->input('text');
        $from = $request->input('from', 'ar');
        $to = $request->input('to', 'en');

        $translatedText = $this->translationService->translate($text, $from, $to);

        return response()->json([
            'success' => true,
            'original' => $text,
            'translated' => $translatedText,
        ]);
    }
}
