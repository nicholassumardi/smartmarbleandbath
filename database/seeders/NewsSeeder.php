<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsTags;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($news as $n) {
            News::insert([
                'id'          => $n['id'],
                'category_id' => $n['category_id'],
                'user_id'     => $n['user_id'],
                'image'       => $n['image'],
                'title'       => $n['title'],
                'slug'        => $n['slug'],
                'description' => $n['description'],
                'status'      => $n['status'],
                'created_at'  => $n['created_at'],
                'updated_at'  => $n['updated_at']
            ]);
        }

        foreach($news_tags as $nt) {
            NewsTags::insert([
                'id'         => $nt['id'],
                'news_id'    => $nt['news_id'],
                'tags'       => $nt['tags'],
                'created_at' => $nt['created_at'],
                'updated_at' => $nt['updated_at']
            ]);
        }
    }
}
