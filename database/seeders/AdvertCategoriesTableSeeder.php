<?php

namespace Database\Seeders;

use App\Entity\Adverts\Category;
use Illuminate\Database\Seeder;

class AdvertCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()->count(10)->create()
            ->each(function (Category $category) {
                $counts = [0, random_int(3, 7)];
                $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create()
                    ->each(function (Category $category) {
                        $counts = [0, random_int(3, 7)];
                        $category->children()->saveMany(Category::factory()->count($counts[array_rand($counts)])->create());
                    }));
            });
    }
}
