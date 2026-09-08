<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'title' => $this->faker->sentence(4),
            'body' => $this->faker->paragraphs(3, true),
            'cover_image' => null,
            'status' => $this->faker->randomElement(['draft', 'published']),
            'publish_at' => null,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
