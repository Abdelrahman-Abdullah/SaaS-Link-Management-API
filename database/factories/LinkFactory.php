<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    protected $model = Link::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'user_id'            => User::factory(),
            'original_url'       => fake()->url(),
            'short_code'         => Str::random(7),
            'custom_alias'       => fake()->boolean(30) ? fake()->unique()->slug(2) : null,
            'title'              => fake()->boolean(80) ? fake()->sentence(rand(2, 6)) : null,
            'is_active'          => fake()->boolean(85),
            'last_status_update' => fake()->boolean(40) ? fake()->dateTimeBetween($createdAt, 'now') : null,
            'created_at'         => $createdAt,
            'updated_at'         => $createdAt,
        ];
    }

    /**
     * Link is active.
     */
    public function active(): static
    {
        return $this->state(fn () => ['is_active' => true]);
    }

    /**
     * Link is inactive / disabled.
     */
    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

}
