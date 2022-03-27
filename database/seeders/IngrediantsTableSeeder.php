<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingrediants As Ingrediants;


class IngrediantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingrediants::factory()->count(3)->create();
    }
}
