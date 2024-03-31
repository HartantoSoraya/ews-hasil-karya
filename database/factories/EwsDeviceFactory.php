<?php

namespace Database\Factories;

use App\Repositories\EwsDeviceRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EwsDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => Str::random(10),
            'name' => $this->faker->unique()->word,
            'type' => $this->faker->randomElement(['sensor', 'actuator']),
            'province' => $this->faker->state,
            'regency' => $this->faker->city,
            'district' => $this->faker->city,
            'subdistrict' => $this->faker->city,
            'address' => $this->faker->address,
            'description' => $this->faker->sentence,
        ];
    }

    public function withExpectedCode(): self
    {
        return $this->state(function (array $attributes) {
            $ewsDeviceRepository = new EwsDeviceRepository();

            $code = '';
            $tryCount = 0;
            do {
                $code = $ewsDeviceRepository->generateCode($tryCount);
                $tryCount++;
            } while (! $ewsDeviceRepository->isUniqueCode($code));

            return [
                'code' => $code,
            ];
        });
    }
}
