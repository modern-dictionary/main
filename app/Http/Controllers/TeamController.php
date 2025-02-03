<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['owner', 'users'])
            ->withCount('users')
            ->get()
            ->map(function ($team) {
                // شمارش کلمات کاربران تیم
                $userWordsCount = $team->users->sum(function ($user) {
                    return $user->words->count();
                });

                // شمارش کلمات مالک تیم
                $ownerWordsCount = $team->owner ? $team->owner->words->count() : 0;

                // مجموع کلمات کاربران و مالک تیم
                $team->words_count = $userWordsCount + $ownerWordsCount;

                return $team;
            });

        $currentUser = auth()->user();

        return Inertia::render('Teams/Index', [
            'teams' => $teams,
            'currentUser' => $currentUser,
        ]);
    }



    public function sendJoinRequest(Team $team): \Illuminate\Http\JsonResponse
    {
        $team->users()->syncWithoutDetaching([auth()->id()]);
      return response()->json(['message' => 'درخواست عضویت با موفقیت ارسال شد.']);
  }

    public function leave(Team $team)
    {
        $user = auth()->user();

        if ($team->owner_id === $user->id) {
            return response()->json(['message' => 'Owner cannot leave the team.'], 403);
        }

        $team->users()->detach($user->id);

        return response()->json(['message' => 'You have left the team successfully.']);
    }
}
