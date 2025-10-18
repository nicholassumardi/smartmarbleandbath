<?php

namespace Database\Seeders;

use App\Models\Journal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($journals as $j) {
            Journal::insert([
                'id'               => $j['id'],
                'journalable_type' => $j['journalable_type'],
                'journalable_id'   => $j['journalable_id'],
                'coa_id'           => $j['coa_id'],
                'nominal'          => $j['nominal'],
                'created_at'       => $j['created_at'],
                'updated_at'       => $j['updated_at'],
                'deleted_at'       => $j['deleted_at']
            ]);
        }
    }
}
