<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    private const GROUND_SPICE_SORT_OFFSET = 1000;

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
            ->with(['ingredients', 'favorites', 'steps'])
            ->withCount('favorites')
            ->latest()
            ->paginate(8);

        return view('recipes.index', compact('recipes'));
    }

    public function create()
    {
        return view('recipes.create');
    }

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
            'image' => 'nullable|string|max:2048',
            'video_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_ingredients_text' => 'nullable|string',
            'ground_spices_text' => 'nullable|string',
            'steps_text' => 'required|string',
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request
                ->file('image_file')
                ->store('recipes', 'public');
        }

        if (!empty($data['image'])) {
            $data['image'] = trim($data['image']);
        }

        $mainIngredientsText = $data['main_ingredients_text'] ?? '';
        $groundSpicesText = $data['ground_spices_text'] ?? '';
        $stepsText = $data['steps_text'] ?? '';

        unset(
            $data['image_file'],
            $data['main_ingredients_text'],
            $data['ground_spices_text'],
            $data['steps_text']
        );

        if (empty($data['image'])) {
            $data['image'] = null;
        }

        $recipe = Recipe::create($data);

        $this->syncIngredientsFromText(
            $recipe,
            $mainIngredientsText,
            $groundSpicesText
        );
        $this->syncStepsFromText($recipe, $stepsText);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Resep berhasil ditambahkan');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'favorites', 'steps']);

        return view('recipes.detail', compact('recipe'));
    }

    public function edit(Recipe $recipe)
    {
        $recipe->load(['ingredients', 'steps']);

        return view('recipes.edit', [
            'recipe' => $recipe,
            'mainIngredientsText' => $this->ingredientsToText($recipe, false),
            'groundSpicesText' => $this->ingredientsToText($recipe, true),
            'stepsText' => $this->stepsToText($recipe),
        ]);
    }

    public function update(Request $request, Recipe $recipe)
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
            'image' => 'nullable|string|max:2048',
            'video_url' => 'nullable|url',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_ingredients_text' => 'nullable|string',
            'ground_spices_text' => 'nullable|string',
            'steps_text' => 'required|string',
        ]);

        if ($request->hasFile('image_file')) {
            $data['image'] = $request
                ->file('image_file')
                ->store('recipes', 'public');
        } elseif (empty($data['image'])) {
            unset($data['image']);
        } else {
            $data['image'] = trim($data['image']);
        }

        $mainIngredientsText = $data['main_ingredients_text'] ?? '';
        $groundSpicesText = $data['ground_spices_text'] ?? '';
        $stepsText = $data['steps_text'] ?? '';

        unset(
            $data['image_file'],
            $data['main_ingredients_text'],
            $data['ground_spices_text'],
            $data['steps_text']
        );

        $recipe->update($data);

        $this->syncIngredientsFromText(
            $recipe,
            $mainIngredientsText,
            $groundSpicesText
        );
        $this->syncStepsFromText($recipe, $stepsText);

        return redirect()
            ->route('recipes.show', $recipe)
            ->with('success', 'Resep berhasil diperbarui');
    }

    public function rate(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'rating_value' => 'required|integer|min:1|max:5',
        ]);

        $value = (int) $validated['rating_value'];
        $oldTotal = (int) $recipe->total_ratings;
        $oldAverage = (float) $recipe->rating;
        $newTotal = $oldTotal + 1;
        $newAverage = (($oldAverage * $oldTotal) + $value) / $newTotal;

        $recipe->update([
            'rating' => round($newAverage, 2),
            'total_ratings' => $newTotal,
        ]);

        return back()->with('success', 'Rating berhasil diberikan');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->ingredients()->delete();
        $recipe->steps()->delete();
        $recipe->delete();

        return redirect()
            ->route('recipes.index')
            ->with('success', 'Resep berhasil dihapus');
    }

    private function syncIngredientsFromText(
        Recipe $recipe,
        ?string $mainIngredientsText,
        ?string $groundSpicesText
    ): void {
        $recipe->ingredients()->delete();

        $ingredients = array_merge(
            $this->parseIngredientText($mainIngredientsText, 1),
            $this->parseIngredientText($groundSpicesText, self::GROUND_SPICE_SORT_OFFSET)
        );

        if (!empty($ingredients)) {
            $recipe->ingredients()->createMany($ingredients);
        }
    }

    private function parseIngredientText(?string $text, int $sortStart): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $text);
        $items = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $line = preg_replace('/^\s*(?:[-*]|\d+[.)])\s*/', '', $line);

            $items[] = array_merge(
                $this->parseIngredientLine($line),
                [
                    'is_optional' => 0,
                    'sort_order' => $sortStart + count($items),
                ]
            );
        }

        return $items;
    }

    private function parseIngredientLine(string $line): array
    {
        if (str_contains($line, '|')) {
            $parts = array_map('trim', explode('|', $line));
            $quantity = $parts[1] ?? null;

            return [
                'ingredient_name' => $parts[0] ?? $line,
                'quantity' => is_numeric(str_replace(',', '.', (string) $quantity))
                    ? str_replace(',', '.', (string) $quantity)
                    : null,
                'unit' => $parts[2] ?? null,
                'preparation_note' => $parts[3] ?? null,
            ];
        }

        $quantity = null;
        $unit = null;
        $nameAndNote = $line;

        if (preg_match('/^([0-9]+(?:[,.][0-9]+)?)\s+([A-Za-z]+)\s+(.+)$/', $line, $matches)) {
            $quantity = str_replace(',', '.', $matches[1]);
            $unit = $matches[2];
            $nameAndNote = $matches[3];
        }

        $nameParts = array_map('trim', explode(',', $nameAndNote, 2));

        return [
            'ingredient_name' => $nameParts[0],
            'quantity' => $quantity,
            'unit' => $unit,
            'preparation_note' => $nameParts[1] ?? null,
        ];
    }

    private function syncStepsFromText(Recipe $recipe, ?string $stepsText): void
    {
        $recipe->steps()->delete();

        $steps = $this->parseStepsText($stepsText);

        if (!empty($steps)) {
            $recipe->steps()->createMany($steps);
        }
    }

    private function parseStepsText(?string $text): array
    {
        $lines = preg_split('/\r\n|\r|\n/', (string) $text);
        $steps = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $line = preg_replace('/^\s*(?:[-*]|\d+[.)])\s*/', '', $line);
            $line = preg_replace('/^\s*(?:langkah|step)\s*\d+\s*[:.)-]?\s*/i', '', $line);

            $steps[] = [
                'step_number' => count($steps) + 1,
                'instruction' => $line,
            ];
        }

        return $steps;
    }

    private function ingredientsToText(Recipe $recipe, bool $groundSpices): string
    {
        return $recipe->ingredients
            ->filter(function ($ingredient) use ($groundSpices) {
                return $groundSpices
                    ? $ingredient->sort_order >= self::GROUND_SPICE_SORT_OFFSET
                    : $ingredient->sort_order < self::GROUND_SPICE_SORT_OFFSET;
            })
            ->sortBy('sort_order')
            ->map(function ($ingredient) {
                $line = trim(implode(' ', array_filter([
                    $this->formatQuantity($ingredient->quantity),
                    $ingredient->unit,
                    $ingredient->ingredient_name,
                ])));

                if ($ingredient->preparation_note) {
                    $line .= ', ' . $ingredient->preparation_note;
                }

                return $line;
            })
            ->implode(PHP_EOL);
    }

    private function stepsToText(Recipe $recipe): string
    {
        return $recipe->steps
            ->sortBy('step_number')
            ->pluck('instruction')
            ->implode(PHP_EOL);
    }

    private function formatQuantity($quantity): ?string
    {
        if ($quantity === null || $quantity === '') {
            return null;
        }

        $number = (float) $quantity;

        return floor($number) == $number
            ? (string) (int) $number
            : rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    }
}
