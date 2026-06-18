<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $table = 'recipe_ingredients';

    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'ingredient_name',
        'quantity',
        'unit',
        'preparation_note',
        'is_optional',
        'sort_order'
    ];

    public function recipe()
    {
        return $this->belongsTo(
            Recipe::class,
            'recipe_id',
            'id'
        );
    }
}
