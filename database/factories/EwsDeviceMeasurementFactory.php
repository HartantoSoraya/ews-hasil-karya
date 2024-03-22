<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EwsDeviceMeasurementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
