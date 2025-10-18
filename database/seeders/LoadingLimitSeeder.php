<?php

namespace Database\Seeders;

use App\Models\LoadingLimit;
use Illuminate\Database\Seeder;

class LoadingLimitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($loading_limits as $ll) {
            LoadingLimit::insert([
                'id'         => $ll['id'],
                'code'       => $ll['code'],
                'name'       => $ll['name'],
                'status'     => $ll['status'],
                'created_at' => $ll['created_at'],
                'updated_at' => $ll['updated_at'],
                'deleted_at' => $ll['deleted_at']
            ]);
        }
    }
}
