<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdminStock;

class SuperAdminStockSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'Test Item 1',
                'description' => 'Test superadmin stock item 1',
                'image' => '2024-08-16-66be7113484e6.png',
                'category_id' => 3,
                'category_ids' => '[{"id":"3","position":1}]',
                'price' => 120.00,
                'discount' => 0.00,
                'discount_type' => 'percent',
                'veg' => 0,
                'status' => 1,
                'module_id' => 2,
                'stock' => 300,
                'unit_id' => null, // Assuming a valid unit_id exists
            ],
            // Add more items here with different names and other necessary fields
        ];

        foreach ($items as $item) {
            SuperAdminStock::create($item);
        }
    }
}
