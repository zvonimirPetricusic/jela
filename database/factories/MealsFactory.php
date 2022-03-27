<?php

namespace Database\Factories;
use App\Models\Meals;
use App\Models\Categories;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class MealsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand =  $this->faker->randomNumber();
        $category = Categories::inRandomOrder()->first();
        $category_id = $category["id"];


        $faker = \Faker\Factory::create();
        $faker->addProvider(new \FakerRestaurant\Provider\fr_FR\Restaurant($faker));
        $faker->addProvider(new \FakerRestaurant\Provider\en_US\Restaurant($faker));
        
        $en_meal = $faker->unique()->foodName('en_US');
        $fr_meal = $faker->unique()->foodName('fr_FR');

        $translations = array([
            'en' => $en_meal, 
            'fr' => $fr_meal
        ]);
        
        $translations_description = array([
            'en' => "Description on english for meal " . $en_meal, 
            'fr' => "Description on french for meal " . $fr_meal
        ]);
 
        return [
            'title' => $translations,
            'description' => $translations_description,
            'category_id' => $category_id
        ];
    }

}
