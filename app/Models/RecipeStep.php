<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeStep extends Model
{
    protected $fillable = [
        'recipe_id',
        'step_number',
        'instruction',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getCleanInstructionAttribute(): string
    {
        return trim(preg_replace(
            '/^\s*(?:langkah|step)\s*\d+\s*[:.)-]?\s*/i',
            '',
            $this->instruction
        ));
    }
}
