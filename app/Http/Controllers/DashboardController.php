<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\User;
use App\Models\Team;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::withCount('words')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($user) {
                $user->formatted_created_at = $user->created_at->format('d/m/Y');
                return $user;
            });

        $words = Word::orderBy('created_at', 'desc')->take(10)->get();

        $timeFrames = [
            '1_week' => now()->subWeek(),
            '1_month' => now()->subMonth(),
            '6_months' => now()->subMonths(6),
            '1_year' => now()->subYear(),
        ];

        $data = [];
        foreach ($timeFrames as $key => $timeFrame) {
            $data[$key] = [
                'users' => User::where('created_at', '>=', $timeFrame)->count(),
                'words' => Word::where('created_at', '>=', $timeFrame)->count(),
                'teams' => Team::where('created_at', '>=', $timeFrame)->count(),
            ];
        }

        return Inertia::render('Dashboard', [
            'words' => $words,
            'users' => $users,
            'chartData' => $data,
        ]);
    }
}
