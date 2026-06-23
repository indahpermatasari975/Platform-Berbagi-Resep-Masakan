<?php

namespace App\Http\Controllers;

use App\Models\Recipe;

class AdminRecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::query()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.recipes.index', compact('recipes'));
    }

    public function approve(Recipe $recipe)
    {
        $recipe->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Resep berhasil disetujui.');
    }
}
