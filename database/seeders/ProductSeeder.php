<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Product 1',
                'description' => 'Description for Product 1',
                'quantity' => 10,
                'price' => 19.99,
            ],
            [
                'name' => 'Product 2',
                'description' => 'Description for Product 2',
                'quantity' => 10,
                'price' => 29.99,
            ],
            [
                'name' => 'Product 3',
                'description' => 'Description for Product 3',
                'quantity' => 10,
                'price' => 39.99,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
