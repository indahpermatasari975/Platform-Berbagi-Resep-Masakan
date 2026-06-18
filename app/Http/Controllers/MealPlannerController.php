<?php

namespace App\Http\Controllers;

use App\Models\MealPlanner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MealPlannerController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'meal_date' => 'required|date',
            'meal_type' => 'required|in:Sarapan,Makan Siang,Makan Malam',
        ]);

        MealPlanner::create([
            'user_id' => $this->currentUserId(),
            'recipe_id' => $data['recipe_id'],
            'meal_date' => $data['meal_date'],
            'meal_type' => $data['meal_type'],
        ]);

        return back()->with('success', 'Menu berhasil ditambahkan ke meal planner.');
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
