<?php

namespace Database\Seeders;

use App\Models\Surface;
use Illuminate\Database\Seeder;

class SurfaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($surfaces as $s) {
            Surface::insert([
                'id'         => $s['id'],
                'code'       => $s['code'],
                'name'       => $s['name'],
                'status'     => $s['status'],
                'created_at' => $s['created_at'],
                'updated_at' => $s['updated_at'],
                'deleted_at' => $s['deleted_at']
            ]);
        }
    }
}
