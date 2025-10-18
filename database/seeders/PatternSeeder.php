<?php

namespace Database\Seeders;

use App\Models\Pattern;
use Illuminate\Database\Seeder;

class PatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($patterns as $p) {
            Pattern::insert([
                'id'         => $p['id'],
                'code'       => $p['code'],
                'name'       => $p['name'],
                'status'     => $p['status'],
                'created_at' => $p['created_at'],
                'updated_at' => $p['updated_at'],
                'deleted_at' => $p['deleted_at']
            ]);
        }
    }
}
