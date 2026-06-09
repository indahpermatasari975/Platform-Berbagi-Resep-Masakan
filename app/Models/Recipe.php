<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'rating',
        'total_ratings'
    ];

    public function ingredients()
    {
        return $this->hasMany(
            RecipeIngredient::class,
            'recipe_id',
            'id'
        );
    }
}
