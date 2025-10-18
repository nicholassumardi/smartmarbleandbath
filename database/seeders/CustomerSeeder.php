<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerPoint;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($customers as $c) {
            Customer::insert([
                'id'           => $c['id'],
                'photo'        => $c['photo'],
                'name'         => $c['name'],
                'email'        => $c['email'],
                'phone'        => $c['phone'],
                'password'     => $c['password'],
                'type'         => 1,
                'points'       => $c['points'],
                'verification' => $c['verification'],
                'created_at'   => $c['created_at'],
                'updated_at'   => $c['updated_at']
            ]);
        }

        foreach($customer_points as $cp) {
            CustomerPoint::insert([
                'id'          => $cp['id'],
                'customer_id' => $cp['customer_id'],
                'order_id'    => $cp['order_id'],
                'points'      => $cp['points'],
                'created_at'  => $cp['created_at'],
                'updated_at'  => $cp['updated_at']
            ]);
        }
    }
}
