<?php

namespace Database\Seeders;

use App\Models\JobDesc;
use Illuminate\Database\Seeder;

class JobDescSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($job_descs as $jd) {
            JobDesc::insert([
                'id'         => $jd['id'],
                'position'   => $jd['position'],
                'job'        => $jd['job'],
                'branch'     => $jd['branch'],
                'created_at' => $jd['created_at'],
                'updated_at' => $jd['updated_at']
            ]);
        }
    }
}
