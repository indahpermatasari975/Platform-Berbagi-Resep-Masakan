<?php

namespace App\Http\Controllers;

use App\Models\IngredientSubstitution;
use Illuminate\Http\Request;

class SubstitutionsPageController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = IngredientSubstitution::query();

        if (!empty($q)) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('ingredient_name', 'LIKE', "%{$q}%")
                    ->orWhere('substitute_name', 'LIKE', "%{$q}%");
            });
        }

        $substitutions = $query
            ->orderBy('ingredient_name')
            ->paginate(10);

        return view('substitutions.index', [
            'substitutions' => $substitutions,
            'q' => $q,
        ]);
    }
}

