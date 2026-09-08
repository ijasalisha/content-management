<?php

namespace Database\Factories;

use App\Models\Privilege;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Privilege>
 */
class PrivilegeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->unique()->word() . '.' . $this->faker->unique()->word(),
            "description" => $this->faker->sentence(),
        ];
    }
}
