<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($categories as $c) {
            Category::insert([
                'id'         => $c['id'],
                'name'       => $c['name'],
                'slug'       => $c['slug'],
                'parent_id'  => $c['parent_id'],
                'type'       => $c['type'],
                'status'     => $c['status'],
                'created_at' => $c['created_at'],
                'updated_at' => $c['updated_at'],
                'deleted_at' => $c['deleted_at']
            ]);
        }
    }
}
