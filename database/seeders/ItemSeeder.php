<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Laptop',
                'description' => 'Dell Latitude 5420',
                'unit_price' => 4500.00,
                'stock_quantity' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Monitor',
                'description' => '24-inch LED Monitor',
                'unit_price' => 1200.00,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Keyboard',
                'description' => 'Wireless Keyboard',
                'unit_price' => 150.00,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Mouse',
                'description' => 'Wireless Optical Mouse',
                'unit_price' => 100.00,
                'stock_quantity' => 40,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
