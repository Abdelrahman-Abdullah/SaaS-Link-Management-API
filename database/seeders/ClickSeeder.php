<?php

namespace Database\Seeders;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Database\Seeder;

class ClickSeeder extends Seeder
{
    /**
     * Seed the clicks table.
     *
     * Generates a realistic distribution of clicks across all links.
     */
    public function run(): void
    {
        $links = Link::all();

        if ($links->isEmpty()) {
            $this->command->warn('No links found — run LinkSeeder first.');
            return;
        }

        $this->command->info("Generating clicks for {$links->count()} links…");

        $links->each(function (Link $link) {
            // Generate a random number of click records per link (1–50)
            $numRecords = rand(1, 50);

            if ($numRecords === 0) {
                return;
            }

            // Mix of desktop and mobile traffic
            $desktopCount = (int) round($numRecords * 0.55);
            $mobileCount  = $numRecords - $desktopCount;

            Click::factory($desktopCount)->desktop()->create([
                'link_id'    => $link->id,
                'created_at' => fn () => fake()->dateTimeBetween($link->created_at, 'now'),
                'updated_at' => fn () => fake()->dateTimeBetween($link->created_at, 'now'),
            ]);

            Click::factory($mobileCount)->mobile()->create([
                'link_id'    => $link->id,
                'created_at' => fn () => fake()->dateTimeBetween($link->created_at, 'now'),
                'updated_at' => fn () => fake()->dateTimeBetween($link->created_at, 'now'),
            ]);
        });

        $this->command->info('Clicks seeding complete. Total: ' . Click::count());
    }
}
