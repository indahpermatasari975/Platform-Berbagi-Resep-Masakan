<?php

namespace App\Http\Controllers;

use App\Models\MealPlanner;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MealPlannerPageController extends Controller
{
    public function index()
    {
        $userId = $this->currentUserId();

        $items = MealPlanner::query()
            ->with('recipe')
            ->where('user_id', $userId)
            ->orderBy('meal_date')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('meal_planner.index', [
            'items' => $items,
        ]);
    }

    private function currentUserId(): int
    {
        if (Auth::id()) {
            return Auth::id();
        }

        return User::firstOrCreate(
            ['email' => 'demo@resepkita.test'],
            [
                'name' => 'Pengguna Demo',
                'password' => Hash::make('password'),
            ]
        )->id;
    }
}
