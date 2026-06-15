<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavoritesPageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if ($userId) {
            // If authenticated, show DB favorites
            $favoriteRecipes = Recipe::query()
                ->whereHas('favorites', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->with('ingredients')
                ->orderByDesc('updated_at')
                ->paginate(8);
        } else {
            // If not authenticated, show session favorites
            $sessionFavorites = session('favorites', []);

            $favoriteRecipes = Recipe::query()
                ->whereIn('id', $sessionFavorites)
                ->with('ingredients')
                ->orderByDesc('updated_at')
                ->paginate(8);
        }

        return view('favorites.index', [
            'recipes' => $favoriteRecipes,
        ]);
    }
}

