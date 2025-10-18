<?php

namespace Database\Seeders;

use App\Models\Import;
use Illuminate\Database\Seeder;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($imports as $i) {
            Import::insert([
                'id'         => $i['id'],
                'code'       => $i['code'],
                'name'       => $i['name'],
                'created_at' => $i['created_at'],
                'updated_at' => $i['updated_at'],
                'deleted_at' => $i['deleted_at']
            ]);
        }
    }
}
