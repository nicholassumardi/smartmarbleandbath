<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($banners as $b) {
            Banner::insert([
                'id'         => $b['id'],
                'image'      => $b['image'],
                'status'     => $b['status'],
                'created_at' => $b['created_at'],
                'updated_at' => $b['updated_at'],
                'deleted_at' => $b['deleted_at']
            ]);
        }
    }
}
