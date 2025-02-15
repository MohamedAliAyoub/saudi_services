<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            ['name' => 'الرياض', 'address' => 'عنوان الرياض', 'phone' => '1234567890', 'client_id' => 3],
            ['name' => 'جدة', 'address' => 'عنوان جدة', 'phone' => '0987654321', 'client_id' => 3],
            ['name' => 'الدمام', 'address' => 'عنوان الدمام', 'phone' => '1122334455', 'client_id' => 3],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}
