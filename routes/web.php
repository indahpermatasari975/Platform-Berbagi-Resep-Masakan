<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminRecipeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MealPlannerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoritesPageController;
use App\Http\Controllers\MealPlannerPageController;
use App\Http\Controllers\SubstitutionsPageController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class,'index'])
    ->name('dashboard');

Route::get('/recipes',[RecipeController::class,'index'])
    ->name('recipes.index');

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::get('/login',[AuthController::class,'showLogin'])
    ->name('login');

Route::post('/login',[AuthController::class,'login'])
    ->name('login.process');

Route::get('/register',[AuthController::class,'showRegister'])
    ->name('register');

Route::post('/register',[AuthController::class,'register'])
    ->name('register.post');

Route::post('/logout',[AuthController::class,'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| User Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/recipes/create',
        [RecipeController::class,'create'])
        ->name('recipes.create');

    Route::post('/recipes',
        [RecipeController::class,'store'])
        ->name('recipes.store');

    Route::get('/recipes/{recipe}/edit',
        [RecipeController::class,'edit'])
        ->name('recipes.edit');

    Route::put('/recipes/{recipe}',
        [RecipeController::class,'update'])
        ->name('recipes.update');

    Route::delete('/recipes/{recipe}',
        [RecipeController::class,'destroy'])
        ->name('recipes.destroy');

    Route::post('/recipes/{recipe}/favorite',
        [FavoriteController::class,'store'])
        ->name('recipes.favorite');

    Route::delete('/recipes/{recipe}/favorite',
        [FavoriteController::class,'destroy'])
        ->name('recipes.favorite.destroy');

    Route::post('/recipes/{recipe}/rate',
        [RecipeController::class,'rate'])
        ->name('recipes.rate');

    Route::post('/meal-planner',
        [MealPlannerController::class,'store'])
        ->name('meal-planner.store');

    Route::get('/favorites',
        [FavoritesPageController::class,'index'])
        ->name('favorites.index');

    Route::get('/meal-planner',
        [MealPlannerPageController::class,'index'])
        ->name('meal-planner.index');

    Route::get('/substitutions',
        [SubstitutionsPageController::class,'index'])
        ->name('substitutions.index');

});

Route::get('/recipes/{recipe}',[RecipeController::class,'show'])
    ->name('recipes.show');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::middleware('admin')->group(function () {

    Route::get('/admin',
        [AdminRecipeController::class,'index'])
        ->name('admin.dashboard');

    Route::get('/admin/recipes',
        [AdminRecipeController::class,'index'])
        ->name('admin.recipes.index');

    Route::patch('/admin/recipes/{recipe}/approve',
        [AdminRecipeController::class,'approve'])
        ->name('admin.recipes.approve');

});
