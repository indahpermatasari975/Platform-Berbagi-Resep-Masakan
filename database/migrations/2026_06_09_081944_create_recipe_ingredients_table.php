<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_ingredients', function (Blueprint $table) {

            $table->id();
            $table->foreignId('recipe_id')
                  ->constrained('recipes')
                  ->cascadeOnDelete();
            $table->string('ingredient_name',200);
            $table->decimal('quantity',8,2)
                  ->nullable();
            $table->string('unit',30)
                  ->nullable();
            $table->string('preparation_note',100)
                  ->nullable();
            $table->boolean('is_optional')
                  ->default(false);
            $table->integer('sort_order')
                  ->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_ingredients');
    }
};
