<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            // Clothing Sizes
            ['name' => 'Extra Small', 'value' => 'xs', 'category' => 'clothing'],
            ['name' => 'Small', 'value' => 's', 'category' => 'clothing'],
            ['name' => 'Medium', 'value' => 'm', 'category' => 'clothing'],
            ['name' => 'Large', 'value' => 'l', 'category' => 'clothing'],
            ['name' => 'Extra Large', 'value' => 'xl', 'category' => 'clothing'],
            ['name' => '2X Large', 'value' => '2xl', 'category' => 'clothing'],
            ['name' => '3X Large', 'value' => '3xl', 'category' => 'clothing'],

            // Footwear Sizes
            ['name' => 'Size 6', 'value' => '6', 'category' => 'footwear'],
            ['name' => 'Size 7', 'value' => '7', 'category' => 'footwear'],
            ['name' => 'Size 8', 'value' => '8', 'category' => 'footwear'],
            ['name' => 'Size 9', 'value' => '9', 'category' => 'footwear'],
            ['name' => 'Size 10', 'value' => '10', 'category' => 'footwear'],
            ['name' => 'Size 11', 'value' => '11', 'category' => 'footwear'],
            ['name' => 'Size 12', 'value' => '12', 'category' => 'footwear'],
            ['name' => 'Size 13', 'value' => '13', 'category' => 'footwear'],

            // Accessories
            ['name' => 'One Size', 'value' => 'onesize', 'category' => 'accessories'],
            ['name' => 'Free Size', 'value' => 'freesize', 'category' => 'accessories'],
        ];

        foreach ($sizes as $size) {
            Size::firstOrCreate(
                ['value' => $size['value'], 'category' => $size['category']],
                $size
            );
        }
    }
}
