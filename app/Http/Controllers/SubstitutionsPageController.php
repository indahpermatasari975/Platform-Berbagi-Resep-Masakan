<?php

namespace App\Http\Controllers;

use App\Models\IngredientSubstitution;
use Illuminate\Http\Request;

class SubstitutionsPageController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $query = IngredientSubstitution::query();

        if ($q) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('ingredient_name', 'like', "%{$q}%")
                    ->orWhere('substitute_name', 'like', "%{$q}%");
            });
        }

        $substitutions = $query
            ->orderBy('ingredient_name')
            ->paginate(10);

        return view('substitutions.index', compact(
            'substitutions',
            'q'
        ));
    }

    public function create()
    {
        return view('substitutions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ingredient_name' => 'required|max:255',
            'substitute_name' => 'required|max:255',
            'notes' => 'nullable'
        ]);

        IngredientSubstitution::create($validated);

        return redirect()
            ->route('substitutions.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(IngredientSubstitution $substitution)
    {
        return view('substitutions.edit', compact(
            'substitution'
        ));
    }

    public function update(
        Request $request,
        IngredientSubstitution $substitution
    ) {
        $validated = $request->validate([
            'ingredient_name' => 'required|max:255',
            'substitute_name' => 'required|max:255',
            'notes' => 'nullable'
        ]);

        $substitution->update($validated);

        return redirect()
            ->route('substitutions.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(IngredientSubstitution $substitution)
    {
        $substitution->delete();

        return redirect()
            ->route('substitutions.index')
            ->with('success', 'Data berhasil dihapus.');
    }
}
