<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FavoritesPageController extends Controller
{
    public function index()
    {
        $userId = $this->currentUserId();

        $favoriteRecipes = Recipe::query()
            ->whereHas('favorites', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with('ingredients')
            ->orderByDesc('updated_at')
            ->paginate(8);

        return view('favorites.index', [
            'recipes' => $favoriteRecipes,
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
