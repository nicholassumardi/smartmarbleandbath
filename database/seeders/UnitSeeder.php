<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($units as $u) {
            Unit::insert([
                'id'         => $u['id'],
                'code'       => $u['code'],
                'name'       => $u['name'],
                'status'     => $u['status'],
                'created_at' => $u['created_at'],
                'updated_at' => $u['updated_at'],
                'deleted_at' => $u['deleted_at']
            ]);
        }
    }
}
