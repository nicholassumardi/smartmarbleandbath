<?php

namespace Database\Seeders;

use App\Models\Delivery;
use Illuminate\Database\Seeder;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($deliveries as $d) {
            Delivery::insert([
                'id'              => $d['id'],
                'vendor_id'       => $d['vendor_id'],
                'transport_id'    => $d['transport_id'],
                'destination_id'  => $d['destination_id'],
                'capacity'        => $d['capacity'],
                'price_per_kg'    => $d['price_per_kg'],
                'price_per_meter' => $d['price_per_meter'],
                'created_at'      => $d['created_at'],
                'updated_at'      => $d['updated_at'],
                'deleted_at'      => $d['deleted_at']
            ]);
        }
    }
}
