<?php

namespace Database\Factories;

use App\Models\Alternative;
use App\Models\Criterion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Combine>
 */
class CombineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'alternative_id' => Alternative::count() ? Alternative::all()->random()->id : Alternative::factory()->create()->id,
            'criterion_id' => Criterion::count() ? Criterion::all()->random()->id : Criterion::factory()->create()->id,
            'value' => $this->faker->numberBetween(50, 1000)
        ];
    }
}
