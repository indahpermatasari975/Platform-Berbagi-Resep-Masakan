<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class PortionController extends Controller
{
    public function calculate(Request $request, $id)
    {
        $recipe = Recipe::with('ingredients')
            ->findOrFail($id);

        $factor =
            $request->servings /
            $recipe->servings;

        $result = [];

        foreach($recipe->ingredients as $ingredient)
        {
            $result[] = [
                'ingredient' => $ingredient->ingredient_name,
                'quantity' =>
                    round(
                        $ingredient->quantity * $factor,
                        2
                    )
            ];
        }

        return response()->json($result);
    }
}
