<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function store(Recipe $recipe)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($recipe->status !== 'approved') {
            abort(404);
        }

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'recipe_id' => $recipe->id,
        ]);

        return back()->with('success', 'Resep berhasil disimpan ke favorit.');
    }

    public function destroy(Recipe $recipe)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($recipe->status !== 'approved') {
            abort(404);
        }

        Favorite::where('user_id', Auth::id())
            ->where('recipe_id', $recipe->id)
            ->delete();

        return back()->with('success', 'Resep dihapus dari favorit.');
    }
}
