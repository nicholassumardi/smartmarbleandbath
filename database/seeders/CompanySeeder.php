<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($companies as $c) {
            Company::insert([
                'id'         => $c['id'],
                'code'       => $c['code'],
                'name'       => $c['name'],
                'status'     => $c['status'],
                'created_at' => $c['created_at'],
                'updated_at' => $c['updated_at'],
                'deleted_at' => $c['deleted_at']
            ]);
        }
    }
}
