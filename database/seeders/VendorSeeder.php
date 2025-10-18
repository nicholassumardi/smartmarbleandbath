<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($vendors as $v) {
            Vendor::insert([
                'id'         => $v['id'],
                'code'       => $v['code'],
                'name'       => $v['name'],
                'email'      => $v['email'],
                'phone'      => $v['phone'],
                'address'    => $v['address'],
                'pic'        => $v['pic'],
                'status'     => $v['status'],
                'created_at' => $v['created_at'],
                'updated_at' => $v['updated_at'],
                'deleted_at' => $v['deleted_at']
            ]);
        }
    }
}
