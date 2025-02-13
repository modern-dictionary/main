<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ElasticsearchService;
use App\Models\Word;
use App\Models\User;
use App\Models\Team;
use App\Models\Category;
use Inertia\Inertia;


class GeneralController extends Controller
{
    protected $elasticsearch;

    public function __construct(ElasticsearchService $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function index()
    {
        $words = Word::with([
            'user:id,name',
            'user.teams:id,name',
            'categories:id,name'
        ])->get();

        foreach ($words as $word) {
            $this->elasticsearch->indexWord($word);
        }

        return Inertia::render('library/index', compact('words'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = $this->elasticsearch->searchWords($query);

        return response()->json($results);
    }
}
