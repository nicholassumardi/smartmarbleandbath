<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PricingPolicy;
use App\Models\ProductShading;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($products as $p) {
            Product::insert([
                'id'                  => $p['id'],
                'type_id'             => $p['type_id'],
                'company_id'          => $p['company_id'],
                'hs_code_id'          => $p['hs_code_id'],
                'brand_id'            => $p['brand_id'],
                'country_id'          => $p['country_id'],
                'supplier_id'         => $p['supplier_id'],
                'grade_id'            => $p['grade_id'],
                'carton_pallet'       => $p['carton_pallet'],
                'carton_pcs'          => $p['carton_pcs'],
                'container_standart'  => $p['container_standart'],
                'container_stock'     => $p['container_stock'],
                'container_max_stock' => $p['container_max_stock'],
                'description'         => $p['description'],
                'status'              => $p['status'],
                'created_at'          => $p['created_at'],
                'updated_at'          => $p['updated_at'],
                'deleted_at'          => $p['deleted_at']
            ]);
        }

        foreach($product_shadings as $ps) {
            ProductShading::insert([
                'id'             => $ps['id'],
                'product_id'     => $ps['product_id'],
                'warehouse_code' => $ps['warehouse_code'],
                'stock_code'     => $ps['stock_code'],
                'code'           => $ps['code'],
                'qty'            => $ps['qty'],
                'created_at'     => $ps['created_at'],
                'updated_at'     => $ps['updated_at']
            ]);
        }

        foreach($pricing_policies as $pp) {
            PricingPolicy::insert([
                'id'                       => $pp['id'],
                'product_id'               => $pp['product_id'],
                'cogs'                     => $pp['cogs'],
                'showroom_cost'            => $pp['showroom_cost'],
                'sales_travel_cost'        => $pp['sales_travel_cost'],
                'marketing_cost'           => $pp['marketing_cost'],
                'interest'                 => $pp['interest'],
                'sales_commission'         => $pp['sales_commission'],
                'fixed_cost'               => $pp['fixed_cost'],
                'nett_profit'              => $pp['nett_profit'],
                'saving'                   => $pp['saving'],
                'middlemant'               => $pp['middlemant'],
                'project'                  => $pp['project'],
                'on_site_cost'             => $pp['on_site_cost'],
                'storage_cost'             => $pp['storage_cost'],
                'bottom_price'             => $pp['bottom_price'],
                'project_price'            => $pp['project_price'],
                'price_list'               => $pp['price_list'],
                'store_price_list'         => $pp['store_price_list'],
                'discount_retail_sales'    => $pp['discount_retail_sales'],
                'discount_retail_manager'  => $pp['discount_retail_manager'],
                'discount_retail_director' => $pp['discount_retail_director'],
                'created_at'               => $pp['created_at'],
                'updated_at'               => $pp['updated_at'],
                'deleted_at'               => $pp['deleted_at']
            ]);
        }
    }
}
