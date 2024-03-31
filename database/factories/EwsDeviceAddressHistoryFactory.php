<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EwsDeviceAddressHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'province' => $this->faker->state,
            'regency' => $this->faker->city,
            'district' => $this->faker->city,
            'subdistrict' => $this->faker->city,
            'address' => $this->faker->address,
        ];
    }
}
