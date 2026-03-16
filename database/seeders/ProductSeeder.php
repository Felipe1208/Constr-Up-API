<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = now();
        $rows = [];
        $brands = [
            'Bosch',
            'Makita',
            'DeWalt',
            'Stanley',
            'Tramontina',
        ];

        for ($i = 0; $i < 200; $i++) {
            $rows[] = [
                'product' => $faker->unique()->words(2, true),
                'brand' => $brands[$i % count($brands)],
                'description' => $faker->sentence(12),
                'price' => number_format(10 + $i + ($faker->numberBetween(0, 99) / 100), 2, '.', ''),
                'stock' => 100 + $i,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        Product::query()->insert($rows);
    }
}
