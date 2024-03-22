<?php

namespace Database\Factories;

use App\Repositories\EwsDeviceRepository;
use Illuminate\Database\Eloquent\Factories\Factory;

class EwsDeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ewsDeviceRepository = new EwsDeviceRepository();

        $code = '';
        $tryCount = 0;
        do {
            $code = $ewsDeviceRepository->generateCode($tryCount);
            $tryCount++;
        } while (! $ewsDeviceRepository->isUniqueCode($code));

        return [
            'code' => $code,
            'name' => $this->faker->unique()->word,
            'type' => $this->faker->randomElement(['sensor', 'actuator']),
        ];
    }
}
