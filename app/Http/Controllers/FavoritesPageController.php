<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavoritesPageController extends Controller
{
    public function index()
    {
        $favoriteRecipes = Recipe::query()
            ->where('status', 'approved')
            ->whereHas('favorites', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with('ingredients')
            ->orderByDesc('updated_at')
            ->paginate(8);

        return view('favorites.index', [
            'recipes' => $favoriteRecipes,
        ]);
    }
}
