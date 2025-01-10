<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Word;
use App\Models\User;
use App\Models\Team;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        $users = User::with(['words', 'teams'])->get();

        $statistics = $users->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'team_count' => $user->teams->count(),
                'word_count' => $user->words->count(),
            ];
        });

        return view('reports.index', compact('statistics'));
        // return Inertia::render('Words/Index', [
        //     'words' => $words,
        // ]);
    }


    public function wordsPerTeam()
    {
      $teams = Team::with('users.words')->get();

      $teamStats = $teams->map(function ($team) {
          return [
              'team_name' => $team->name,
              'word_count' => $team->words->count(),
              'members' => $team->users->map(function ($user) use ($team) {
                return [
                    'user_name' => $user->name,
                    'word_count' => $user->words->count(),
                  ];
                }),
              ];
        });

        return view('reports.words_per_team', compact('teamStats'));
    }

    public function userWordsWithTeams()
    {
      $users = User::with(['teams', 'words'])->get();

      $userStats = $users->map(function ($user) {
          return [
              'user_name' => $user->name,
              'total_words' => $user->words->count(),
              'teams' => $user->teams->map(function ($team) {
                  return [
                      'team_name' => $team->name,
                    ];
                  }),
                ];
              });

            return view('reports.user_words_with_teams', compact('userStats'));
      }

      public function teamStatistics()
      {
          // دریافت تمام تیم‌ها به همراه کاربران و کلماتشان
          $teams = Team::with('users.words')->get();

          // ایجاد آمار تیم‌ها
          $teamStats = $teams->map(function ($team) {
              return [
                  'team_name' => $team->name,
                  'member_count' => $team->users->count(),
                  'word_count' => $team->users->sum(function ($user) {
                      return $user->words->count();
                  }),
              ];
          });

          // مرتب‌سازی بر اساس تعداد کلمات (نزولی)
          $sortedStats = $teamStats->sortByDesc('word_count')->values();

          // ارسال اطلاعات به ویو
          return view('reports.team_statistics', compact('sortedStats'));
      }

      public function topMembers()
      {
        // دریافت تمام کاربران به همراه کلماتشان
        $users = User::with('words')->get();

        // محاسبه اطلاعات کاربران
        $topUsers = $users->map(function ($user) {
          return [
              'name' => $user->name,
              'word_count' => $user->words->count(),
              'joined_at' => $user->created_at->format('F Y'), // تاریخ به صورت ماه و سال
            ];
          });

          // مرتب‌سازی کاربران بر اساس تعداد کلمات (نزولی)
          $sortedTopUsers = $topUsers->sortByDesc('word_count')->values();

          // ارسال اطلاعات به ویو
          return view('reports.top_members', compact('sortedTopUsers'));
        }
  public function landingData()
  {
    $users = User::with(['words', 'teams'])->get();
    $teams = Team::with('users.words')->get();

    $teamStats = $teams->map(function ($team) {
        return [
            'team_name' => $team->name,
            'member_count' => $team->users->count(),
            'word_count' => $team->users->sum(function ($user) {
                return $user->words->count();
            }),
        ];
    })->sortByDesc('word_count')->values();

    $topUsers = $users->map(function ($user) {
      return [
          'name' => $user->name,
          'team_count' => $user->teams->count(),
          'word_count' => $user->words->count(),
          'joined_at' => $user->created_at->format('F Y'),
      ];
    })->sortByDesc('word_count')->values();

    $totalUsers = $users->count(); // تعداد کل کاربران
    $totalTeams = $teams->count(); // تعداد کل تیم‌ها
    $totalWords = $users->sum(function ($user) {
      return $user->words->count(); // تعداد کل کلمات
    });


    return Inertia::render('Landing', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'teamStats' => $teamStats,
        'topUsers' => $topUsers,
        'totalUsers' => $totalUsers,
        'totalTeams' => $totalTeams,
        'totalWords' => $totalWords,
    ]);
  }

}
