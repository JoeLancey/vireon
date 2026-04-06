<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder {
    public function run(): void {
        $brands = [
            ['name' => 'Nike',         'description' => 'Just Do It — Performance athletic wear.',       'accent_color' => '#FF6B00'],
            ['name' => 'Adidas',       'description' => 'Impossible is Nothing — Iconic 3-stripe brand.','accent_color' => '#000000'],
            ['name' => 'New Balance',  'description' => 'Fearlessly Independent Since 1906.',            'accent_color' => '#CF3338'],
            ['name' => 'Puma',         'description' => 'Forever Faster — Sports & lifestyle brand.',    'accent_color' => '#FFD700'],
            ['name' => 'Under Armour', 'description' => 'Protect This House — Performance gear.',        'accent_color' => '#E8002D'],
            ['name' => 'Converse',     'description' => 'All Star — Iconic canvas sneakers.',            'accent_color' => '#DC143C'],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name'         => $brand['name'],
                'slug'         => Str::slug($brand['name']),
                'description'  => $brand['description'],
                'accent_color' => $brand['accent_color'],
            ]);
        }
    }
}