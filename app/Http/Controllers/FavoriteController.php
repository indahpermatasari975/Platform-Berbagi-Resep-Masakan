<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FavoriteController extends Controller
{
    public function store(Recipe $recipe)
    {
        $userId = Auth::id();

        // If authenticated, save to DB
        if ($userId) {
            Favorite::firstOrCreate([
                'user_id' => $userId,
                'recipe_id' => $recipe->id,
            ]);
        } else {
            // If not authenticated, save to session
            $sessionFavorites = Session::get('favorites', []);
            if (!in_array($recipe->id, $sessionFavorites)) {
                $sessionFavorites[] = $recipe->id;
                Session::put('favorites', $sessionFavorites);
            }
        }

        return back();
    }

    public function destroy(Recipe $recipe)
    {
        $userId = Auth::id();

        // If authenticated, delete from DB
        if ($userId) {
            Favorite::where('user_id', $userId)
                ->where('recipe_id', $recipe->id)
                ->delete();
        } else {
            // If not authenticated, delete from session
            $sessionFavorites = Session::get('favorites', []);
            $key = array_search($recipe->id, $sessionFavorites);
            if ($key !== false) {
                unset($sessionFavorites[$key]);
                Session::put('favorites', array_values($sessionFavorites));
            }
        }

        return back();
    }
}
