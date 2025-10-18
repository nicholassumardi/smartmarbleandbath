<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach ($warehouses as $w) {
            Warehouse::insert([
                'id'         => $w['id'],
                'code'       => $w['code'],
                'name'       => $w['name'],
                'status'     => $w['status'],
                'created_at' => $w['created_at'],
                'updated_at' => $w['updated_at']
            ]);
        }
    }
}
