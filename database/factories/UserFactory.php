<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'is_merchant' => false,
        ];
    }

    public function merchant()
    {
        return $this->state(fn () => [
            'name' => $this->faker->company(),
            'email' => $this->faker->companyEmail(),
            'role_id' => 2,
            'is_merchant' => true,
        ]);
    }
}
