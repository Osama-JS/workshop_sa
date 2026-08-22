<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TranslationService
{
    /**
     * Translate text from source language to target language.
     *
     * @param string $text
     * @param string $from e.g. 'ar', 'en'
     * @param string $to e.g. 'en', 'ar'
     * @return string|null
     */
    public function translate(string $text, string $from = 'ar', string $to = 'en'): ?string
    {
        if (trim($text) === '') {
            return '';
        }

        try {
            // Google Translate public client endpoint (fast, free, no API key required)
            $response = Http::timeout(10)->get('https://translate.googleapis.com/translate_a/single', [
                'client' => 'gtx',
                'sl' => $from,
                'tl' => $to,
                'dt' => 't',
                'q' => $text,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[0]) && is_array($data[0])) {
                    $translated = '';
                    foreach ($data[0] as $sentence) {
                        if (isset($sentence[0])) {
                            $translated .= $sentence[0];
                        }
                    }
                    return trim($translated);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Translation error via Google GTX: ' . $e->getMessage());
        }

        // Secondary fallback endpoint
        try {
            $fallbackResponse = Http::timeout(10)->get('https://api.mymemory.translated.net/get', [
                'q' => $text,
                'langpair' => "{$from}|{$to}",
            ]);

            if ($fallbackResponse->successful()) {
                $data = $fallbackResponse->json();
                if (!empty($data['responseData']['translatedText'])) {
                    return trim($data['responseData']['translatedText']);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Translation fallback error: ' . $e->getMessage());
        }

        return $text; // Return original text if both fail
    }
}
