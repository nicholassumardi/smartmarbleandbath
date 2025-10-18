<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketingStructure;

class MarketingStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($marketing_structures as $ms) {
            MarketingStructure::insert([
                'id'                    => $ms['id'],
                'company_id'            => $ms['company_id'],
                'rental_cost'           => $ms['rental_cost'],
                'travel_sales_cost'     => $ms['travel_sales_cost'],
                'marketing_cost'        => $ms['marketing_cost'],
                'on_site_cost'          => $ms['on_site_cost'],
                'storage_cost'          => $ms['storage_cost'],
                'fixed_cost'            => $ms['fixed_cost'],
                'interest_in_payment'   => $ms['interest_in_payment'],
                'rental_cost'           => $ms['rental_cost'],
                'nett_profit'           => $ms['nett_profit'],
                'saving'                => $ms['saving'],
                'sales_commission'      => $ms['sales_commission'],
                'middlemant_commission' => $ms['middlemant_commission'],
                'project_commission'    => $ms['project_commission'],
                'created_at'            => $ms['created_at'],
                'updated_at'            => $ms['updated_at'],
                'deleted_at'            => $ms['deleted_at']
            ]);
        }
    }
}
