<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($divisions as $d) {
            Division::insert([
                'id'         => $d['id'],
                'code'       => $d['code'],
                'name'       => $d['name'],
                'status'     => $d['status'],
                'created_at' => $d['created_at'],
                'updated_at' => $d['updated_at']
            ]);
        }
    }
}
