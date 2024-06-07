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
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => null,
            'email_verified_at' => now(),
            'password' => null,
            'remember_token' => Str::random(10),
            "phone" => $this->faker->phoneNumber,
            "rukn_id" => $this->faker->word,
            "full_name" => $this->faker->name,
            "unit_name" => $this->faker->word,
            "district" => $this->faker->word,
            "halqa" => $this->faker->word,
            "gender" => $this->faker->word,
            "confirm_arrival" => null,
            "reason_for_not_coming" => "",
            "ameer_permission_taken" => null,
            "emergency_contact" => ""
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}