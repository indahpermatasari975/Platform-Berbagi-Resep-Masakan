<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $recipesQuery = Recipe::query()
            ->where('status', 'approved');

        // Search
        if (!empty($q)) {

            $recipesQuery->where(function ($query) use ($q) {

                $query->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('category', 'LIKE', "%{$q}%")
                    ->orWhere('description', 'LIKE', "%{$q}%");

            });

        }

        // Resep Populer
        $popularRecipes = (clone $recipesQuery)
            ->with(['ingredients'])
            ->withCount('favorites')
            ->orderByDesc('total_ratings')
            ->limit(8)
            ->get();

        // Resep Terbaru
        $latestRecipes = (clone $recipesQuery)
            ->with(['ingredients'])
            ->withCount('favorites')
            ->latest()
            ->limit(8)
            ->get();

        // Kategori Populer
        $categories = Recipe::query()
            ->where('status', 'approved')
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByRaw('COUNT(*) DESC')
            ->limit(8)
            ->pluck('category');

        // Video Terbaru
        $latestVideoRecipe = Recipe::where('status', 'approved')
            ->whereNotNull('video_url')
            ->where('video_url', '!=', '')
            ->latest()
            ->first();

        // Statistik Dashboard
        $totalRecipes = Recipe::where('status', 'approved')->count();

        $totalCategories = Recipe::query()
            ->where('status', 'approved')
            ->distinct()
            ->count('category');

        $totalFavorites = Recipe::where('status', 'approved')
            ->withCount('favorites')
            ->get()
            ->sum('favorites_count');

        return view('dashboard', [
            'q' => $q,

            'popularRecipes' => $popularRecipes,

            'latestRecipes' => $latestRecipes,

            'categories' => $categories,

            'latestVideoRecipe' => $latestVideoRecipe,

            'totalRecipes' => $totalRecipes,

            'totalCategories' => $totalCategories,

            'totalFavorites' => $totalFavorites,
        ]);
    }
}
