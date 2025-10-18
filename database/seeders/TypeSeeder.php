<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($types as $t) {
            Type::insert([
                'id'               => $t['id'],
                'category_id'      => $t['category_id'],
                'division_id'      => $t['division_id'],
                'surface_id'       => $t['surface_id'],
                'color_id'         => $t['color_id'],
                'pattern_id'       => $t['pattern_id'],
                'loading_limit_id' => $t['loading_limit_id'],
                'buy_unit_id'      => $t['buy_unit_id'],
                'stock_unit_id'    => $t['stock_unit_id'],
                'selling_unit_id'  => $t['selling_unit_id'],
                'image'            => $t['image'],
                'code'             => $t['code'],
                'material'         => $t['material'],
                'faces'            => $t['faces'],
                'length'           => $t['length'],
                'width'            => $t['width'],
                'height'           => $t['height'],
                'weight'           => $t['weight'],
                'thickness'        => $t['thickness'],
                'conversion'       => $t['conversion'],
                'stockable'        => $t['stockable'],
                'min_stock'        => $t['min_stock'],
                'max_stock'        => $t['max_stock'],
                'small_stock'      => $t['small_stock'],
                'status'           => $t['status'],
                'created_at'       => $t['created_at'],
                'updated_at'       => $t['updated_at'],
                'deleted_at'       => $t['deleted_at']
            ]);
        }
    }
}
