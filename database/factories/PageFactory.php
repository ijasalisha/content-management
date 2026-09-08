<?php

namespace Database\Factories;

use App\Models\Page;
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
            'body' => '<p>' . $this->faker->paragraphs(3, true) . '</p>',
            'cover_image' => $this->faker->imageUrl(800, 600, 'nature', true),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'publish_at' => null,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
