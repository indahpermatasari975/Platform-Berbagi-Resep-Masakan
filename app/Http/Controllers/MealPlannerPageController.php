<?php

namespace App\Http\Controllers;

use App\Models\MealPlanner;
use Illuminate\Support\Facades\Auth;

class MealPlannerPageController extends Controller
{
    public function index()
    {
        $items = MealPlanner::query()
            ->with('recipe')
            ->where('user_id', Auth::id())
            ->orderBy('meal_date')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('meal_planner.index', [
            'items' => $items,
        ]);
    }
}
