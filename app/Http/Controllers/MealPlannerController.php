<?php

namespace App\Http\Controllers;

use App\Models\MealPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlannerController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $data = $request->validate([
            'recipe_id' => 'required|exists:recipes,id,status,approved',
            'meal_date' => 'required|date',
            'meal_type' => 'required|in:Sarapan,Makan Siang,Makan Malam',
        ]);

        MealPlanner::create([
            'user_id' => Auth::id(),
            'recipe_id' => $data['recipe_id'],
            'meal_date' => $data['meal_date'],
            'meal_type' => $data['meal_type'],
        ]);

        return back()->with('success', 'Menu berhasil ditambahkan ke meal planner.');
    }
}
