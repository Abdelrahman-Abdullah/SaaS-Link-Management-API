<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Seed the links table.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found — run UserSeeder first.');
            return;
        }

        // ----- Give every user between 1 and 15 links -----
        $users->each(function (User $user) {
            $count = rand(1, 15);

            Link::factory($count)->create([
                'user_id' => $user->id,
            ]);
        });

        // ----- Create some popular links for the demo user -----
        $demoUser = User::where('email', 'demo@example.com')->first();

        if ($demoUser) {
            // Well-known URLs that look realistic
            $popularUrls = [
                'https://laravel.com/docs/11.x',
                'https://github.com/laravel/laravel',
                'https://stackoverflow.com/questions/tagged/laravel',
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'https://medium.com/@demo/my-great-article',
            ];

            foreach ($popularUrls as $url) {
                Link::factory()->create([
                    'user_id'      => $demoUser->id,
                    'original_url' => $url,
                    'is_active'    => true,
                    'title'        => fake()->sentence(rand(3, 6)),
                ]);
            }

            // Some expired / inactive links
            Link::factory(3)->inactive()->create([
                'user_id' => $demoUser->id,
            ]);
        }
    }
}
