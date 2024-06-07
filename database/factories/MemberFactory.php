<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "phone" => $this->faker->phoneNumber,
            "rukn_id" => $this->faker->word,
            "full_name" => $this->faker->name,
            "unit_name" => $this->faker->word,
            "district" => $this->faker->word,
            "halqa" => $this->faker->word,
            "gender" => $this->faker->word,
            "confirm_arrival" => null,
        ];
    }
}