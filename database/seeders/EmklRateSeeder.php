<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmklRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($emkl_rates as $er) {
            EmklRate::insert([
                'id'          => $er['id'],
                'company_id'  => $er['company_id'],
                'currency_id' => $er['currency_id'],
                'conversion'  => $er['conversion'],
                'created_at'  => $er['created_at'],
                'updated_at'  => $er['updated_at']
            ]);
        }
    }
}
