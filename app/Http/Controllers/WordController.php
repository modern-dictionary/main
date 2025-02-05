<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $words = Word::all();
        return Inertia::render('Dashboard', [
            'words' => $words,
        ]);
    }

    public function userWords()
    {
      $words = Auth::user()->words()->with('categories')->get();
      $categories = Category::all();

      return Inertia::render('Words/Index', [
          'words' => $words,
          'categories' => $categories,
        ]);
    }


    /**
     * validates the word.
     *
     * @param Request $request
     * @return array
     */
    private function validateWord(Request $request): array
    {
        return $request->validate([
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:1000',
            'pronunciation' => 'required|string|max:1000',
            'voice' => 'nullable|mimes:mp3,wav,ogx',
            'image' => 'nullable|mimes:jpg,jpeg,png',
            'description' => 'nullable|string|max:2000',
        ]);
    }

    //TODO: add error handling
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // ثبت اطلاعات اولیه درخواست در لاگ
        Log::info('Store Word Request Data:', $request->all());

        try {
            // اعتبارسنجی اولیه
            $validated = $request->validate([
                'word'          => 'required|string|max:255',
                'meaning'       => 'required|string|max:1000',
                'pronunciation' => 'nullable|string|max:255',
                'description'   => 'nullable|string|max:2000',
                'voice'         => 'nullable|mimes:mp3,wav,ogx,ogg',
                'image'         => 'nullable|mimes:jpg,jpeg,png',
                'categories'    => 'nullable|string',
            ]);

            // بررسی و ثبت اطلاعات فایل صوتی
            if ($request->hasFile('voice')) {
                $voiceFile = $request->file('voice');
                Log::info('Voice file received', ['file' => $voiceFile->getClientOriginalName()]);
                $voicePath = $voiceFile->store('voices', 's3');
                Log::info('Voice file stored on S3', ['path' => $voicePath]);
            } else {
                Log::info('No voice file provided');
                $voicePath = null;
            }

            // بررسی و ثبت اطلاعات فایل تصویری
            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                Log::info('Image file received', ['file' => $imageFile->getClientOriginalName()]);
                $imagePath = $imageFile->store('images', 's3');
                Log::info('Image file stored on S3', ['path' => $imagePath]);
            } else {
                Log::info('No image file provided');
                $imagePath = null;
            }

            // ایجاد رکورد در دیتابیس
            $word = Word::create([
                'word'          => $validated['word'],
                'meaning'       => $validated['meaning'],
                'pronunciation' => $validated['pronunciation'] ?? null,
                'description'   => $validated['description'] ?? null,
                'voice'         => $voicePath,
                'image'         => $imagePath,
                'user_id'       => auth()->id(),
            ]);

            Log::info('Word record created successfully', ['word_id' => $word->id]);
            return response()->json(['message' => 'Word created successfully', 'word' => $word], 201);
        } catch (\Exception $e) {
            // ثبت خطا در لاگ
            Log::error('Error in WordController@store', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
      $request->validate([
        'word' => 'required|string|max:255',
        'meaning' => 'required|string|max:1000',
        'pronunciation' => 'nullable|string|max:1000',
        'voice' => 'nullable|mimes:mp3,wav,ogx,ogg',
        'image' => 'nullable|mimes:jpg,jpeg,png',
        'description' => 'nullable|string|max:2000',
        'selectedCategories' => 'nullable|string',
      ]);

        $word = Word::findOrFail($id);

        // Prepare data for updating
        $data = $request->only(['word', 'meaning', 'pronunciation', 'description']);

        if ($request->hasFile('voice')) {
            if ($word->voice) {
                Storage::disk('s3')->delete($word->voice);
            }
            $data['voice'] = $request->file('voice')->store('voices', 's3');
        }

        if ($request->hasFile('image')) {
            if ($word->image) {
                Storage::disk('s3')->delete($word->image);
            }
            $data['image'] = $request->file('image')->store('images', 's3');
        }

        $word->update($data);

        if ($request->selectedCategories) {
          $word->categories()->sync(json_decode($request->selectedCategories));
        }

        return response()->json(['message' => 'کلمه با موفقیت به‌روزرسانی شد', 'word' => $word], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $word = Word::findOrFail($id);

        if ($word->voice) {
            Storage::disk('s3')->delete($word->voice);
        }

        if ($word->image) {
            Storage::disk('s3')->delete($word->image);
        }

        $word->delete();

        return response()->json(['message' => 'کلمه با موفقیت حذف شد'], 200);
    }
}
