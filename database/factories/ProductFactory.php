<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->realText(),
            'owner_id' => User::first(),
            'category_id' => $this->faker->randomElement([11, 12, 13]),
            'thumbnail_url' => $this->faker->url(),
            'demo_url' => $this->faker->url(),
            'source_url' => $this->faker->url(),
            'price' => rand(1,9) * 100000
        ];
    }
}
