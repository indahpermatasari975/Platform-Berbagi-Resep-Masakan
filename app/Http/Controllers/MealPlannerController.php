<?php

namespace App\Http\Controllers;

use App\Models\MealPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealPlannerController extends Controller
{
    public function store(Request $request)
    {
        MealPlanner::create([
            'user_id' => Auth::id(),
            'recipe_id' => $request->recipe_id,
            'meal_date' => $request->meal_date,
            'meal_type' => $request->meal_type
        ]);

        return back();
    }
}
