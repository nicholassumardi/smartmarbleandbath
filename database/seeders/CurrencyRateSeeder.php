<?php

namespace Database\Seeders;

use App\Models\CurrencyRate;
use Illuminate\Database\Seeder;

class CurrencyRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($currency_rates as $cr) {
            CurrencyRate::insert([
                'id'          => $cr['id'],
                'currency_id' => $cr['currency_id'],
                'company_id'  => $cr['company_id'],
                'conversion'  => $cr['conversion'],
                'created_at'  => $cr['created_at'],
                'updated_at'  => $cr['updated_at']
            ]);
        }
    }
}
