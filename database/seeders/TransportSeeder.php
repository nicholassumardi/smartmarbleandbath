<?php

namespace Database\Seeders;

use App\Models\Transport;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($transports as $t) {
            Transport::insert([
                'id'         => $t['id'],
                'fleet'      => $t['fleet'],
                'created_at' => $t['created_at'],
                'updated_at' => $t['updated_at'],
                'deleted_at' => $t['deleted_at']
            ]);
        }
    }
}
