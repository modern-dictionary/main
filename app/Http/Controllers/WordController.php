<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $words = Auth::user()->words;
        return Inertia::render('Words/Index', [
            'words' => $words,
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
            'pronunciation' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);
    }

    //TODO: add error handling
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $this->validateWord($request);

        Word::create([
            'word' => $validated['word'],
            'meaning' => $validated['meaning'],
            'pronunciation' => $validated['pronunciation'] ?? null,
            'description' => $validated['description'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('words.index');
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'word' => 'required|string|max:255',
            'meaning' => 'required|string|max:1000',
            'pronunciation' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $word = Word::findOrFail($id);
        $word->update($request->all());

        return redirect()->route('words.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $word = Word::findOrFail($id);
        $word->delete();

        return redirect()->route('words.index');
    }
}
