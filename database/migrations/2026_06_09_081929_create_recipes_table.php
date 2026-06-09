<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {

            $table->id();
            $table->string('title',255);
            $table->text('description')->nullable();
            $table->string('category',50)->nullable();
            $table->integer('servings')->default(2);
            $table->integer('prep_time')->default(15);
            $table->integer('cook_time')->default(30);

            $table->enum('difficulty',[
                'Mudah',
                'Sedang',
                'Sulit'
            ])->default('Sedang');

            $table->string('author_name',100);

            $table->string('image')->nullable();

            $table->decimal('rating',3,2)
                  ->default(0);

            $table->integer('total_ratings')
                  ->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
