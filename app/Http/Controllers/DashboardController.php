<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Word;
use App\Models\User;
use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $chartData = [
            '1_week' => [
                'users' => User::where('created_at', '>=', now()->subWeek())->count(),
                'words' => Word::where('created_at', '>=', now()->subWeek())->count(),
                'teams' => Team::where('created_at', '>=', now()->subWeek())->count(),
            ],
            '1_month' => [
                'users' => User::where('created_at', '>=', now()->subMonth())->count(),
                'words' => Word::where('created_at', '>=', now()->subMonth())->count(),
                'teams' => Team::where('created_at', '>=', now()->subMonth())->count(),
            ],
            '6_months' => [
                'users' => User::where('created_at', '>=', now()->subMonths(6))->count(),
                'words' => Word::where('created_at', '>=', now()->subMonths(6))->count(),
                'teams' => Team::where('created_at', '>=', now()->subMonths(6))->count(),
            ],
            '1_year' => [
                'users' => User::where('created_at', '>=', now()->subYear())->count(),
                'words' => Word::where('created_at', '>=', now()->subYear())->count(),
                'teams' => Team::where('created_at', '>=', now()->subYear())->count(),
            ],
        ];

        return Inertia::render('Dashboard', [
            'chartData' => $chartData,
            'words' => Word::latest()->take(5)->get(),
            'users' => User::latest()->take(5)->get(),
        ]);
    }

    public function getChartData()
    {
        $startDate = now()->subDays(30)->startOfDay();
        $endDate = now()->endOfDay();

        // Get daily counts for users
        $users = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Get daily counts for words
        $words = DB::table('words')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Get daily counts for teams
        $teams = DB::table('teams')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Generate date range
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $dates[$dateStr] = [
                'users' => $users[$dateStr] ?? 0,
                'words' => $words[$dateStr] ?? 0,
                'teams' => $teams[$dateStr] ?? 0
            ];
            $currentDate->addDay();
        }

        return response()->json($dates);
    }
}
