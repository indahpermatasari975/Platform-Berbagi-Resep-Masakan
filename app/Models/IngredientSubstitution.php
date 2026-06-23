<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientSubstitution extends Model
{
    protected $fillable = [
        'ingredient_name',
        'substitute_name',
        'notes'
    ];
}
