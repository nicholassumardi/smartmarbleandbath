<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($currencies as $c) {
            Currency::insert([
                'id'         => $c['id'],
                'code'       => $c['code'],
                'name'       => $c['name'],
                'symbol'     => $c['symbol'],
                'status'     => $c['status'],
                'created_at' => $c['created_at'],
                'updated_at' => $c['updated_at'],
                'deleted_at' => $c['deleted_at']
            ]);
        }
    }
}
