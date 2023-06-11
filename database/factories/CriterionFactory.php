<?php

namespace Database\Factories;

use App\Models\Alternative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Criterion>
 */
class CriterionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->streetName() ,
            'weight' => $this->faker->numberBetween(1, 100),
            'normalization' => $this->faker->unique()->randomElement([0.38, 0.26, 0.16, 0.10, 0.06, 0.04]),
            'type' => $this->faker->randomElement(['cost', 'benefit']),
        ];
    }
}
