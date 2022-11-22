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
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_name' => fake()->unique()->name(),
            'first_name'=> fake()->firstName(),
            'last_name'=> fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' =>fake()->phoneNumber(),
            'birthday' => fake()->dateTimeBetween('-70 years', '-10 years', null),
            'country' => fake()->country(),
            'status' => fake()->randomElement(['married', 'single', 'engaged']),
            'region'=> fake()->word(),
            'gender' => fake()->randomElement(['male', 'female']),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
