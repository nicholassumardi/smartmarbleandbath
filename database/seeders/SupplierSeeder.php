<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use App\Models\SupplierCurrency;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($suppliers as $s) {
            Supplier::insert([
                'id'              => $s['id'],
                'country_id'      => $s['country_id'],
                'code'            => $s['code'],
                'name'            => $s['name'],
                'email'           => $s['email'],
                'phone'           => $s['phone'],
                'address'         => $s['address'],
                'pic'             => $s['pic'],
                'limit_credit'    => $s['limit_credit'],
                'term_of_payment' => $s['term_of_payment'],
                'status'          => $s['status'],
                'created_at'      => $s['created_at'],
                'updated_at'      => $s['updated_at'],
                'deleted_at'      => $s['deleted_at']
            ]);
        }

        foreach($supplier_currencies as $sc) {
            SupplierCurrency::insert([
                'id'          => $sc['id'],
                'supplier_id' => $sc['supplier_id'],
                'currency_id' => $sc['currency_id'],
                'created_at'  => $sc['created_at'],
                'updated_at'  => $sc['updated_at']
            ]);
        }
    }
}
