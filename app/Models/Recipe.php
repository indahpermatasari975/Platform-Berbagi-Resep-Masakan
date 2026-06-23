<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Recipe extends Model
{
    use HasFactory;

    private const GROUND_SPICE_SORT_OFFSET = 1000;

    protected $table = 'recipes';

    protected $fillable = [
        'title',
        'description',
        'category',
        'servings',
        'prep_time',
        'cook_time',
        'difficulty',
        'author_name',
        'image',
        'video_url',
        'rating',
        'total_ratings',
        'status',
        'user_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Bahan-bahan resep
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients()
    {
        return $this->hasMany(
            RecipeIngredient::class,
            'recipe_id',
            'id'
        );
    }

    public function steps()
    {
        return $this->hasMany(
            RecipeStep::class,
            'recipe_id',
            'id'
        )->orderBy('step_number');
    }

    // Favorit resep
    public function favorites()
    {
        return $this->hasMany(
            Favorite::class,
            'recipe_id',
            'id'
        );
    }

    // Meal planner
    public function mealPlanners()
    {
        return $this->hasMany(
            MealPlanner::class,
            'recipe_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return '';
        }

        if (preg_match('/^https?:\/\//', $this->image)) {
            return $this->image;
        }

        if (!Storage::disk('public')->exists($this->image)) {
            return '';
        }

        return asset('storage/' . $this->image);
    }

    public function getMainIngredientsAttribute()
    {
        return $this->ingredientsText(false);
    }

    public function getGroundSpicesAttribute()
    {
        return $this->ingredientsText(true);
    }

    public function getCookingStepsTextAttribute()
    {
        $steps = $this->relationLoaded('steps')
            ? $this->steps
            : $this->steps()->get();

        return $steps
            ->sortBy('step_number')
            ->map(fn ($step) => $step->instruction)
            ->implode(PHP_EOL);
    }

    /*
    |--------------------------------------------------------------------------
    | VIDEO EMBED YOUTUBE
    |--------------------------------------------------------------------------
    */

    public function getVideoEmbedUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // youtube.com/watch?v=
        if (str_contains($this->video_url, 'watch?v=')) {

            return str_replace(
                'watch?v=',
                'embed/',
                $this->video_url
            );
        }

        // youtu.be/
        if (str_contains($this->video_url, 'youtu.be/')) {

            $videoId = basename($this->video_url);

            return 'https://www.youtube.com/embed/' . $videoId;
        }

        return $this->video_url;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL FAVORITE
    |--------------------------------------------------------------------------
    */

    public function getFavoriteCountAttribute()
    {
        return $this->favorites()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | CEK SUDAH DIFAVORITKAN
    |--------------------------------------------------------------------------
    */

    public function isFavoritedBy($userId)
    {
        return $this->favorites()
            ->where('user_id', $userId)
            ->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | CEK SUDAH DIFAVORITKAN (DENGAN SESSION)
    |--------------------------------------------------------------------------
    */

    public function isInFavorites()
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->isFavoritedBy(Auth::id());
    }

    private function ingredientsText(bool $groundSpices): string
    {
        $ingredients = $this->relationLoaded('ingredients')
            ? $this->ingredients
            : $this->ingredients()->get();

        return $ingredients
            ->filter(function ($ingredient) use ($groundSpices) {
                return $groundSpices
                    ? $ingredient->sort_order >= self::GROUND_SPICE_SORT_OFFSET
                    : $ingredient->sort_order < self::GROUND_SPICE_SORT_OFFSET;
            })
            ->sortBy('sort_order')
            ->map(function ($ingredient) {
                $line = trim(implode(' ', array_filter([
                    $this->formatIngredientQuantity($ingredient->quantity),
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

    private function formatIngredientQuantity($quantity): ?string
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
