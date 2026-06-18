<?php

namespace Database\Factories;

use App\Models\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactoriesFactory extends Factory
{
    protected $model = Factories::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->address(),
            'city' => fake()->city(),
        ];
    }
}