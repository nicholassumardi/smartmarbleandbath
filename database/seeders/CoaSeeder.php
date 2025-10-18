<?php

namespace Database\Seeders;

use App\Models\Coa;
use Illuminate\Database\Seeder;

class CoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($coas as $c) {
            Coa::insert([
                'id'          => $c['id'],
                'code'        => $c['code'],
                'name'        => $c['name'],
                'parent_id'   => $c['parent_id'],
                'status'      => $c['status'],
                'created_at'  => $c['created_at'],
                'updated_at'  => $c['updated_at'],
                'deleted_at'  => $c['deleted_at']
            ]);
        }
    }
}
