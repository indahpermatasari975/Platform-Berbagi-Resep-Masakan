<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display listing recipes
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $recipes = Recipe::query()

            ->when($q, function ($query) use ($q) {

                $query->where(function ($sub) use ($q) {

                    $sub->where('title', 'LIKE', "%{$q}%")
                        ->orWhere('category', 'LIKE', "%{$q}%")
                        ->orWhere('description', 'LIKE', "%{$q}%");

                });

            })

            ->with([
                'ingredients',
                'favorites'
            ])

            ->withCount('favorites')

            ->latest()

            ->paginate(8);

        return view(
            'recipes.index',
            compact('recipes')
        );
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store recipe
     */
    public function store(Request $request)
    {
        $data = $request->validate([

            'title' => 'required|string|max:255',

            'description' => 'required',

            'category' => 'required|string|max:100',

            'servings' => 'required|integer|min:1',

            'prep_time' => 'required|integer|min:1',

            'cook_time' => 'required|integer|min:1',

            'difficulty' => 'required',

            'author_name' => 'required|string|max:100',

            'image' => 'nullable|url',

            'video_url' => 'nullable|url',

            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'recipe_ingredients' => 'nullable|array',

            'recipe_ingredients.*.ingredient_name'
                => 'required_with:recipe_ingredients|string|max:200',

            'recipe_ingredients.*.quantity'
                => 'nullable|numeric',

            'recipe_ingredients.*.unit'
                => 'nullable|string|max:30',

            'recipe_ingredients.*.preparation_note'
                => 'nullable|string|max:100',

            'recipe_ingredients.*.is_optional'
                => 'nullable|in:0,1',

            'recipe_ingredients.*.sort_order'
                => 'nullable|integer',

        ]);

        // Upload image
        if ($request->hasFile('image_file')) {

            $path = $request
                ->file('image_file')
                ->store('recipes', 'public');

            $data['image'] = $path;
        }

        if (empty($data['image'])) {
            $data['image'] = null;
        }

        $recipe = Recipe::create($data);

        // Save ingredients
        $ingredients = $request->input(
            'recipe_ingredients',
            []
        );

        if (is_array($ingredients)) {

            $filtered = array_values(
                array_filter(
                    $ingredients,
                    function ($row) {

                        return isset(
                            $row['ingredient_name']
                        ) &&
                            trim(
                                (string) $row['ingredient_name']
                            ) !== '';

                    }
                )
            );

            foreach ($filtered as &$row) {

                $row['sort_order']
                    = $row['sort_order'] ?? 0;

                $row['is_optional']
                    = $row['is_optional'] ?? 0;
            }

            if (!empty($filtered)) {

                $recipe->ingredients()
                    ->createMany($filtered);
            }
        }

        return redirect()
            ->route(
                'recipes.show',
                $recipe
            )
            ->with(
                'success',
                'Resep berhasil ditambahkan'
            );
    }

    /**
     * Show recipe detail
     */
    public function show(Recipe $recipe)
    {
        $recipe->load([
            'ingredients',
            'favorites'
        ]);

        return view(
            'recipes.show',
            compact('recipe')
        );
    }

    /**
     * Show edit form
     */
    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients');

        return view(
            'recipes.edit',
            compact('recipe')
        );
    }

    /**
     * Update recipe
     */
    public function update(
        Request $request,
        Recipe $recipe
    ) {

        $data = $request->validate([

            'title' => 'required|string|max:255',

            'description' => 'required',

            'category' => 'required|string|max:100',

            'servings' => 'required|integer|min:1',

            'prep_time' => 'required|integer|min:1',

            'cook_time' => 'required|integer|min:1',

            'difficulty' => 'required',

            'author_name' => 'required|string|max:100',

            'image' => 'nullable|url',

            'video_url' => 'nullable|url',

            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'recipe_ingredients' => 'nullable|array',

            'recipe_ingredients.*.ingredient_name'
                => 'required_with:recipe_ingredients|string|max:200',

            'recipe_ingredients.*.quantity'
                => 'nullable|numeric',

            'recipe_ingredients.*.unit'
                => 'nullable|string|max:30',

            'recipe_ingredients.*.preparation_note'
                => 'nullable|string|max:100',

            'recipe_ingredients.*.is_optional'
                => 'nullable|in:0,1',

            'recipe_ingredients.*.sort_order'
                => 'nullable|integer',
        ]);

        // Upload image baru
        if ($request->hasFile('image_file')) {

            $path = $request
                ->file('image_file')
                ->store('recipes', 'public');

            $data['image'] = $path;
        } elseif (empty($data['image'])) {

            unset($data['image']);
        }

        $recipe->update($data);

        // Refresh ingredients
        $recipe->ingredients()->delete();

        $ingredients = $request->input(
            'recipe_ingredients',
            []
        );

        $filtered = array_values(
            array_filter(
                $ingredients,
                fn($row)
                    => isset($row['ingredient_name'])
                    && trim(
                        $row['ingredient_name']
                    ) !== ''
            )
        );

        foreach ($filtered as &$row) {

            $row['sort_order']
                = $row['sort_order'] ?? 0;

            $row['is_optional']
                = $row['is_optional'] ?? 0;
        }

        if (!empty($filtered)) {

            $recipe->ingredients()
                ->createMany($filtered);
        }

        return redirect()
            ->route(
                'recipes.show',
                $recipe
            )
            ->with(
                'success',
                'Resep berhasil diperbarui'
            );
    }

    /**
     * Rate recipe
     */
    public function rate(
        Request $request,
        Recipe $recipe
    ) {

        $validated = $request->validate([

            'rating_value'
                => 'required|integer|min:1|max:5'

        ]);

        $value = (int) $validated['rating_value'];

        $oldTotal = (int) $recipe->total_ratings;

        $oldAverage = (float) $recipe->rating;

        $newTotal = $oldTotal + 1;

        $newAverage =
            (($oldAverage * $oldTotal) + $value)
            / $newTotal;

        $recipe->update([

            'rating' => round(
                $newAverage,
                2
            ),

            'total_ratings' => $newTotal

        ]);

        return back()->with(
            'success',
            'Rating berhasil diberikan'
        );
    }

    /**
     * Delete recipe
     */
    public function destroy(
        Recipe $recipe
    ) {

        $recipe->ingredients()->delete();

        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with(
                'success',
                'Resep berhasil dihapus'
            );
    }
}
