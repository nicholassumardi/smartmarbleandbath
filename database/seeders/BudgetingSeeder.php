<?php

namespace Database\Seeders;

use App\Models\Budgeting;
use Illuminate\Database\Seeder;

class BudgetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($budgetings as $b) {
            Budgeting::insert([
                'id'         => $b['id'],
                'coa_id'     => $b['coa_id'],
                'month'      => $b['month'],
                'nominal'    => $b['nominal'],
                'created_at' => $b['created_at'],
                'updated_at' => $b['updated_at'],
                'deleted_at' => $b['deleted_at']
            ]);
        }
    }
}
