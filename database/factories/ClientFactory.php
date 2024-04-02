<?php

namespace Database\Factories;

use App\Enum\UserRoleEnum;
use App\Models\User;
use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class ClientFactory extends Factory
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
            'name' => $this->faker->name,
            'province' => $this->faker->state,
            'regency' => $this->faker->city,
            'district' => $this->faker->city,
            'subdistrict' => $this->faker->city,
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'is_active' => $this->faker->boolean,
        ];
    }

    public function withExpectedCode(): self
    {
        return $this->state(function (array $attributes) {
            $clientRepository = new ClientRepository();

            $code = '';
            $tryCount = 0;
            do {
                $code = $clientRepository->generateCode($tryCount);
                $tryCount++;
            } while (! $clientRepository->isUniqueCode($code));

            return [
                'code' => $code,
            ];
        });
    }

    public function withClientUser(): self
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()
                ->hasAttached(Role::where('name', '=', UserRoleEnum::CLIENT)->first())
                ->create();

            return [
                'user_id' => $user->id,
            ];
        });
    }

    public function withCredentials(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'email' => $this->faker->unique()->safeEmail,
                'password' => 'password',
            ];
        });
    }
}
