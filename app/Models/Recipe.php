<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Recipe extends Model
{
    use HasFactory;

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
        'total_ratings'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Bahan-bahan resep
    public function ingredients()
    {
        return $this->hasMany(
            RecipeIngredient::class,
            'recipe_id',
            'id'
        );
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

        return asset('storage/' . $this->image);
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
        $userId = Auth::id();

        // If authenticated, check DB
        if ($userId) {
            return $this->isFavoritedBy($userId);
        }

        // If not authenticated, check session
        $sessionFavorites = session('favorites', []);
        return in_array($this->id, $sessionFavorites);
    }
}
