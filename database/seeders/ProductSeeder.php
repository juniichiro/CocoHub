<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Coconut Coir Tote Bag',
            'category' => 'Fashion',
            'description' => 'Reusable tote bag made from woven coconut fibers, perfect for eco-conscious shopping.',
            'price' => 280,
            'stock' => 5,
            'image' => 'coconut_coir_tote_bag.png',
        ]);
        
        Product::create([
            'name' => 'Coconut Coir Brush',
            'category' => 'Household',
            'description' => 'Durable cleaning brush made from stiff coconut coir bristles. Ideal for scrubbing floors, dishes, and outdoor surfaces.',
            'price' => 120,
            'stock' => 50,
            'image' => 'coconut_coir_brush.png',
        ]);
        
        Product::create([
            'name' => 'Coir Geotextile Roll',
            'category' => 'Construction',
            'description' => 'Biodegradable coconut fiber net used for soil stabilization, erosion control, and road construction projects.',
            'price' => 520,
            'stock' => 5,
            'image' => 'coir_geotextile_roll.png',
        ]);

        Product::create([
            'name' => 'Coir Hanging Basket Pot',
            'category' => 'Gardening',
            'description' => 'Decorative hanging planter lined with natural coconut fiber to support plant growth and moisture retention.',
            'price' => 180,
            'stock' => 30,
            'image' => 'coir_hanging_basket_pot.png',
        ]);

        Product::create([
            'name' => 'Coconut Coir Rope',
            'category' => 'Construction',
            'description' => 'Strong twisted rope made from natural coconut fiber. Commonly used in gardening, crafts, and construction.',
            'price' => 260,
            'stock' => 0,
            'image' => 'coconute_coir_rope.png',
        ]);

        Product::create([
            'name' => 'Coconut Coir Door Mat',
            'category' => 'Home & Living',
            'description' => 'Eco-friendly doormat made from thick coconut fibers designed to trap dirt and moisture from shoes.',
            'price' => 350,
            'stock' => 20,
            'image' => 'coconut_coir_door_mat.png',
        ]);

        Product::create([
            'name' => 'Coconut Coir Organic Fertilizer',
            'category' => 'Gardening',
            'description' => 'Natural fertilizer made from processed coconut husk fibers that improves soil aeration and water retention.',
            'price' => 150,
            'stock' => 50,
            'image' => 'coconut_coir_organic_feritilizer.png',
        ]);

        Product::create([
            'name' => 'Coconut Fiber Slippers',
            'category' => 'Fashion',
            'description' => 'Comfortable eco-friendly slippers made using woven coconut fiber materials.',
            'price' => 320,
            'stock' => 5,
            'image' => 'coconut_fiber_slippers.png',
        ]);

        Product::create([
            'name' => 'Coir Plant Pot',
            'category' => 'Gardening',
            'description' => 'Biodegradable plant pot made from compressed coconut fiber, ideal for seedlings and small plants.',
            'price' => 95,
            'stock' => 100,
            'image' => 'coir_plant_pot.png',
        ]);

        Product::create([
            'name' => 'Coir Seeding Tray',
            'category' => 'Gardening',
            'description' => 'Sustainable seed-starting tray made from coconut fiber that supports plant growth and root development.',
            'price' => 220,
            'stock' => 50,
            'image' => 'coir_seeding_tray.png',
        ]);
    }
}
