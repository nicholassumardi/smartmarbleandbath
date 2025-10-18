<?php

namespace Database\Seeders;

use App\Models\Freight;
use Illuminate\Database\Seeder;

class FreightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($freights as $f) {
            Freight::insert([
                'id'         => $f['id'],
                'country_id' => $f['country_id'],
                'country_id' => $f['country_id'],
                'city_id'    => $f['city_id'],
                'container'  => $f['container'],
                'shipping'   => $f['shipping'],
                'cost'       => $f['cost'],
                'created_at' => $f['created_at'],
                'updated_at' => $f['updated_at'],
                'deleted_at' => $f['deleted_at']
            ]);
        }
    }
}
