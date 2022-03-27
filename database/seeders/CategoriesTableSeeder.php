<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categories As Categories;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categories::factory()->count(3)->create();
    }
}
