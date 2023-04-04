<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articleCategories = config('news.categories');

        if (!empty($articleCategories)) {
            foreach ($articleCategories as $category) {
                ArticleCategory::firstOrCreate([
                    'name' => $category
                ]);
            }
        }
    }
}
