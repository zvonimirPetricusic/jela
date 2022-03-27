<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(IngrediantsTableSeeder::class);
        $this->call(TagsTableSeeder::class);
        $this->call(MealsSeederTable::class);
        $this->call(LanguageTableSeeder::class);
    
    }
}
