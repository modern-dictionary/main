<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Inertia\Inertia;
use Illuminate\Http\Request;

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
      $words = auth()->user()->words;
      return Inertia::render('Words/Index', [
          'words' => $words,
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'word' => 'required|string', // Example validation rule
        ]);

        Word::create([
        'word' => $request->input('word'),
        'meaning' => $request->input('meaning'),
        'pronansiation' => $request->input('pronansiation'),
        'description' => $request->input('description'),
        'user_id' => auth()->id(), // اضافه کردن ID کاربر فعلی
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'word' => 'required|string', // Example validation rule
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
