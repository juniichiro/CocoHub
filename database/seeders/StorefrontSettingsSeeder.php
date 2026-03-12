<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StorefrontSetting;

class StorefrontSettingsSeeder extends Seeder
{
    public function run(): void
    {
        StorefrontSetting::updateOrCreate(
            ['id' => 1], // Ensures we only ever have one record
            [
                'banner_title'      => 'Sustainable Coconut Coir Products for Everyday Use',
                'short_description' => 'Browse curated coconut coir products for gardening, home, and construction.',
                'main_image'        => 'hero.jpg', 
                'featured_1'        => 1,
                'featured_2'        => 5,
                'featured_3'        => 7,
                'featured_4'        => 9,
            ]
        );
    }
}