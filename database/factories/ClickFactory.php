<?php

namespace Database\Factories;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Click>
 */
class ClickFactory extends Factory
{
    protected $model = Click::class;

    protected array $browsers = [
        'Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Brave', 'Vivaldi', 'Samsung Internet',
    ];

    protected array $platforms = [
        'Windows', 'macOS', 'Linux', 'iOS', 'Android', 'Chrome OS', 'Ubuntu',
    ];

    protected array $deviceTypes = [
        'desktop', 'mobile', 'tablet',
    ];

    protected array $referrers = [
        'https://www.google.com',
        'https://www.facebook.com',
        'https://twitter.com',
        'https://www.linkedin.com',
        'https://www.reddit.com',
        'https://www.instagram.com',
        'https://t.co',
        'https://www.youtube.com',
        'https://news.ycombinator.com',
        'https://www.tiktok.com',
        'https://mail.google.com',
        'https://outlook.live.com',
        null, // direct traffic
    ];

    protected array $countries = [
        'United States', 'United Kingdom', 'Germany', 'France', 'Canada',
        'Australia', 'Brazil', 'India', 'Japan', 'Netherlands',
        'Spain', 'Italy', 'Mexico', 'South Korea', 'Sweden',
        'Poland', 'Turkey', 'Indonesia', 'Nigeria', 'Egypt',
    ];

    protected array $geoData = [
        'United States'  => [['California', 'Los Angeles'], ['New York', 'New York'], ['Texas', 'Houston'], ['Florida', 'Miami'], ['Illinois', 'Chicago'], ['Washington', 'Seattle']],
        'United Kingdom' => [['England', 'London'], ['Scotland', 'Edinburgh'], ['Wales', 'Cardiff']],
        'Germany'        => [['Bavaria', 'Munich'], ['Berlin', 'Berlin'], ['Hesse', 'Frankfurt']],
        'France'         => [['Île-de-France', 'Paris'], ['Provence', 'Marseille'], ['Auvergne-Rhône-Alpes', 'Lyon']],
        'Canada'         => [['Ontario', 'Toronto'], ['Quebec', 'Montreal'], ['British Columbia', 'Vancouver']],
        'Australia'      => [['New South Wales', 'Sydney'], ['Victoria', 'Melbourne'], ['Queensland', 'Brisbane']],
        'Brazil'         => [['São Paulo', 'São Paulo'], ['Rio de Janeiro', 'Rio de Janeiro'], ['Minas Gerais', 'Belo Horizonte']],
        'India'          => [['Maharashtra', 'Mumbai'], ['Karnataka', 'Bangalore'], ['Delhi', 'New Delhi']],
        'Japan'          => [['Tokyo', 'Tokyo'], ['Osaka', 'Osaka'], ['Kanagawa', 'Yokohama']],
        'Netherlands'    => [['North Holland', 'Amsterdam'], ['South Holland', 'Rotterdam']],
        'Spain'          => [['Community of Madrid', 'Madrid'], ['Catalonia', 'Barcelona']],
        'Italy'          => [['Lazio', 'Rome'], ['Lombardy', 'Milan']],
        'Mexico'         => [['Mexico City', 'Mexico City'], ['Jalisco', 'Guadalajara']],
        'South Korea'    => [['Seoul', 'Seoul'], ['Busan', 'Busan']],
        'Sweden'         => [['Stockholm', 'Stockholm'], ['Västra Götaland', 'Gothenburg']],
        'Poland'         => [['Masovia', 'Warsaw'], ['Lesser Poland', 'Krakow']],
        'Turkey'         => [['Istanbul', 'Istanbul'], ['Ankara', 'Ankara']],
        'Indonesia'      => [['Jakarta', 'Jakarta'], ['East Java', 'Surabaya']],
        'Nigeria'        => [['Lagos', 'Lagos'], ['FCT', 'Abuja']],
        'Egypt'          => [['Cairo', 'Cairo'], ['Alexandria', 'Alexandria']],
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $country    = fake()->randomElement($this->countries);
        $regionCity = fake()->randomElement($this->geoData[$country] ?? [['Unknown', 'Unknown']]);
        $clickedAt  = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'link_id'    => Link::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'referrer'   => fake()->randomElement($this->referrers),
            'country'    => $country,
            'region'     => $regionCity[0],
            'city'       => $regionCity[1],
            'device_type'=> fake()->randomElement($this->deviceTypes),
            'browser'    => fake()->randomElement($this->browsers),
            'platform'   => fake()->randomElement($this->platforms),
            'created_at' => $clickedAt,
            'updated_at' => $clickedAt,
        ];
    }

    /**
     * Click from mobile device.
     */
    public function mobile(): static
    {
        return $this->state(fn () => [
            'device_type' => 'mobile',
            'platform'    => fake()->randomElement(['iOS', 'Android']),
        ]);
    }

    /**
     * Click from desktop.
     */
    public function desktop(): static
    {
        return $this->state(fn () => [
            'device_type' => 'desktop',
            'platform'    => fake()->randomElement(['Windows', 'macOS', 'Linux']),
        ]);
    }
}
