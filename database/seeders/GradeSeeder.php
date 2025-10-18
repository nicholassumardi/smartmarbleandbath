<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($grades as $g) {
            Grade::insert([
                'id'         => $g['id'],
                'code'       => $g['code'],
                'name'       => $g['name'],
                'status'     => $g['status'],
                'created_at' => $g['created_at'],
                'updated_at' => $g['updated_at'],
                'deleted_at' => $g['deleted_at']
            ]);
        }
    }
}
