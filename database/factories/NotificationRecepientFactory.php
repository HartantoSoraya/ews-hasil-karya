<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class NotificationRecepientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'job_title' => $this->faker->jobTitle(),
            'is_active' => true,
        ];
    }
}
