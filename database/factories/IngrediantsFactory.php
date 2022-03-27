<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ingrediants;
use Faker\Generator as Faker;

class IngrediantsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $rand =  $this->faker->randomNumber();

        $fr_ingrediants = "IngrediantFR-" . $rand;
        $en_ingrediants  = "IngrediantEN-" . $rand;
        
        $translations = array(['en' => $en_ingrediants, 'fr'=>$fr_ingrediants]);

        return [
            'ingrediant_title' => $translations,
            'slug' => 'Ingrediant-' . $rand
        ];
    }
}



