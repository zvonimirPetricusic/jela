<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meals;
use App\Models\Tags;
use App\Models\IngrediantMeals;
use App\Models\Ingrediants;
use App\Models\MealsTags;

class MealsSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $meals = Meals::factory()->count(10)->create();
      
      foreach($meals as $m){
        $tags = Tags::inRandomOrder()->first();
        $tag_id = $tags["id"];
        MealsTags::factory()->create([
           "meals_id" => $m["id"],
           "tags_id" => $tag_id
        ]);
      }

      foreach($meals as $m){
        $ingrediants = Ingrediants::inRandomOrder()->first();
        $ingrediant_id = $ingrediants["id"];
        IngrediantMeals::factory()->create([
           "meal_id" => $m["id"],
           "ingrediant_id" => $ingrediant_id
        ]);
      }
    }
}
