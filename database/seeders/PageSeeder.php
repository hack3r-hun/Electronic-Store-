<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        Page::create([
            'slug' => 'about',
            'title' => 'About Us',
            'content' => '<p>Welcome to ElectroMart — your neighborhood electronics and hardware store. For over a decade, we have been supplying quality bulbs, capacitors, wiring, pipes, bolts, nuts, and all essential electrical items to local customers, electricians, and contractors.</p><p>We believe in fair prices, genuine products, and friendly service. Whether you need a single LED bulb or bulk wiring for a project, our team is here to help you find exactly what you need.</p><p>Visit our store or shop online — we deliver across the city with Cash on Delivery and card payment options.</p>',
            'meta' => [
                'hero_title' => 'Serving Our Community Since 2010',
                'hero_subtitle' => 'Quality products, honest prices, trusted by locals.',
            ],
        ]);

        Page::create([
            'slug' => 'home',
            'title' => 'Home',
            'content' => null,
            'meta' => [
                'hero_title' => 'Everything Electrical, One Trusted Store',
                'hero_subtitle' => 'Bulbs, wiring, capacitors, pipes, bolts & more — delivered to your door.',
                'hero_cta' => 'Shop Now',
            ],
        ]);
    }
}
