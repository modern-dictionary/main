<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Inertia\Inertia;
use Illuminate\Http\Request;

class TeamController extends Controller
{
  public function index()
  {
      $teams = Team::with(['owner', 'users'])->withCount('users')->get();
      return Inertia::render('Teams/Index', [
        'teams' => $teams,
      ]);
  }

  public function sendJoinRequest(Request $request, Team $team): \Illuminate\Http\JsonResponse
  {
        $user = $request->user();
        $owner = $team->owner; // فرض می‌کنیم رابطه owner در مدل Team تعریف شده است

        // در اینجا می‌توانید درخواست عضویت را ثبت کنید یا به مدیر اطلاع دهید.
        // به عنوان مثال، ارسال نوتیفیکیشن:
        $owner->notify(new \App\Notifications\TeamJoinRequestNotification($user, $team));

        // یا ثبت درخواست عضویت در یک جدول مجزا (مثلاً team_requests) در صورت نیاز

        return response()->json([
            'message' => 'درخواست عضویت ارسال شد.',
        ]);
    }
}
