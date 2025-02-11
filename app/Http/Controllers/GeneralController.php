<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Services\ElasticsearchService;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    protected $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index()
    {
        $words = Word::with(['user.teams', 'category'])->get();

        foreach ($words as $word) {
            $this->elasticsearch->indexWord($word);
        }

        return view('library.index', compact('words'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = $this->elasticsearch->searchWords($query);

        return response()->json($results);
    }
}
