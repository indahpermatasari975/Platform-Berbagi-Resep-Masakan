<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MealPlannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoritesPageController;
use App\Http\Controllers\MealPlannerPageController;
use App\Http\Controllers\SubstitutionsPageController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('recipes', RecipeController::class);

Route::post('/recipes/{recipe}/rate', [RecipeController::class, 'rate'])
    ->name('recipes.rate');

// Favorite toggle
Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'store'])
    ->name('recipes.favorite');
Route::delete('/recipes/{recipe}/favorite', [FavoriteController::class, 'destroy'])
    ->name('recipes.favorite.destroy');

// Meal planner add
Route::post('/meal-planner', [MealPlannerController::class, 'store'])
    ->name('meal-planner.store');

// Pages
Route::get('/favorites', [FavoritesPageController::class, 'index'])->name('favorites.index');
Route::get('/meal-planner', [MealPlannerPageController::class, 'index'])->name('meal-planner.index');
Route::get('/substitutions', [SubstitutionsPageController::class, 'index'])->name('substitutions.index');

