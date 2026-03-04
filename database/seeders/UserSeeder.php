<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the users table.
     */
    public function run(): void
    {
        // ----- Fixed demo accounts (known credentials for testing) -----
        User::factory()->create([
            'name'          => 'Admin User',
            'email'         => 'admin@gmail.com',
            'password'      => Hash::make('password'),
            'active'        => true,
            'last_login_at' => now(),
        ]);

        User::factory()->create([
            'name'          => 'Demo User',
            'email'         => 'demo@gmail.com',
            'password'      => Hash::make('password'),
            'active'        => true,
            'last_login_at' => now()->subDays(2),
        ]);

        User::factory()->create([
            'name'          => 'Inactive User',
            'email'         => 'inactive@gmail.com',
            'password'      => Hash::make('password'),
            'active'        => false,
            'last_login_at' => now()->subMonths(3),
        ]);

        // ----- Random active users -----
        User::factory(47)->create([
            'active'        => true,
            'last_login_at' => fn () => fake()->dateTimeBetween('-30 days', 'now'),
        ]);

        // ----- Random inactive / churned users -----
        User::factory(10)->create([
            'active'        => false,
            'last_login_at' => fn () => fake()->dateTimeBetween('-6 months', '-2 months'),
        ]);

        // ----- Users that never logged in -----
        User::factory(5)->create([
            'active'        => true,
            'last_login_at' => null,
        ]);
    }
}
