<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::query()->insert([
            [
                'product_name' => 'Notebook',
                'quantity_in_stock' => 12,
                'price_per_item' => 3.50,
                'submitted_at' => now()->subMinutes(30),
                'total_value_number' => 42.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_name' => 'USB Cable',
                'quantity_in_stock' => 7,
                'price_per_item' => 5.25,
                'submitted_at' => now()->subMinutes(5),
                'total_value_number' => 36.75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
