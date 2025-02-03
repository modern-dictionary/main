<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Word;
use Illuminate\Http\Request;

class WordImportController extends Controller
{
    public function importWord($word)
    {
        // 1. ترجمه کلمه فارسی به انگلیسی با MyMemory API
        $translationResponse = Http::get('https://api.mymemory.translated.net/get', [
            'q'        => $word,
            'langpair' => 'fa|en'
        ]);

        if (!$translationResponse->successful()) {
            return response()->json(['error' => 'خطا در سرویس ترجمه'], 500);
        }

        $translationData = $translationResponse->json();
        $englishWord = $translationData['responseData']['translatedText'] ?? null;

        if (!$englishWord) {
            return response()->json(['error' => 'ترجمه پیدا نشد'], 404);
        }

        // 2. دریافت اطلاعات کلمه از Free Dictionary API
        $dictionaryResponse = Http::get('https://api.dictionaryapi.dev/api/v2/entries/en/' . urlencode($englishWord));

        if (!$dictionaryResponse->successful()) {
            return response()->json(['error' => 'اطلاعات دیکشنری پیدا نشد'], 404);
        }

        $dictionaryData = $dictionaryResponse->json();
        $firstEntry = $dictionaryData[0] ?? null;

        if (!$firstEntry) {
            return response()->json(['error' => 'ورودی دیکشنری یافت نشد'], 404);
        }

        // استخراج اطلاعات: تلفظ، فایل صوتی و تعریف انگلیسی
        $pronunciation     = $firstEntry['phonetics'][0]['text'] ?? null;
        $voice             = $firstEntry['phonetics'][0]['audio'] ?? null;
        $englishDefinition = $firstEntry['meanings'][0]['definitions'][0]['definition'] ?? null;

        // 3. ترجمه تعریف انگلیسی به فارسی (برای توضیحات)
        $persianDefinition = null;
        if ($englishDefinition) {
            $definitionTranslationResponse = Http::get('https://api.mymemory.translated.net/get', [
                'q'        => $englishDefinition,
                'langpair' => 'en|fa'
            ]);

            if ($definitionTranslationResponse->successful()) {
                $definitionTranslationData = $definitionTranslationResponse->json();
                $persianDefinition = $definitionTranslationData['responseData']['translatedText'] ?? $englishDefinition;
            } else {
                $persianDefinition = $englishDefinition; // در صورت عدم موفقیت، انگلیسی باقی می‌ماند
            }
        }

        // 5. آماده‌سازی داده برای ذخیره در دیتابیس (بدون category_id و is_popular)
        $wordData = [
            'word'          => $word,         // کلمه فارسی ورودی
            'translation'   => $englishWord,  // ترجمه انگلیسی
            'pronunciation' => $pronunciation,
            'description'   => $persianDefinition,
        ];

        return response()->json([
            'message' => 'کلمه با موفقیت وارد شد.',
            'data'    => $wordData
        ], 201);
    }
}
