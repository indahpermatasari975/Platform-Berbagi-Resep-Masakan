<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FavoriteController extends Controller
{
    public function store(Recipe $recipe)
    {
        Favorite::firstOrCreate([
            'user_id' => $this->currentUserId(),
            'recipe_id' => $recipe->id,
        ]);

        return back()->with('success', 'Resep berhasil disimpan ke favorit.');
    }

    public function destroy(Recipe $recipe)
    {
        Favorite::where('user_id', $this->currentUserId())
            ->where('recipe_id', $recipe->id)
            ->delete();

        return back()->with('success', 'Resep dihapus dari favorit.');
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
