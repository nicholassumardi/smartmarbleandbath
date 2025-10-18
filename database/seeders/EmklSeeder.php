<?php

namespace Database\Seeders;

use App\Models\Emkl;
use Illuminate\Database\Seeder;

class EmklSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($emkls as $e) {
            Emkl::insert([
                'id'         => $e['id'],
                'company_id' => $e['company_id'],
                'import_id'  => $e['import_id'],
                'country_id' => $e['country_id'],
                'city_id'    => $e['city_id'],
                'container'  => $e['container'],
                'cost'       => $e['cost'],
                'created_at' => $e['created_at'],
                'updated_at' => $e['updated_at'],
                'deleted_at' => $e['deleted_at']
            ]);
        }
    }
}
