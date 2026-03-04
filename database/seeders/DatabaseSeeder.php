<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,   // 65 users (3 fixed + 47 active + 10 inactive + 5 never-logged-in)
            LinkSeeder::class,   // 1-15 links per user + extras for demo user
            ClickSeeder::class,  // realistic click records per link
        ]);
    }
}
