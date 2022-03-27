<?php

namespace Database\Factories;
use App\Models\Categories;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand =  $this->faker->randomNumber();

        $fr_categories = "CategoryFR-" . $rand;
        $en_categories = "CategoryEN-" . $rand;
        
        $translations = array(['en' => $en_categories, 'fr'=>$fr_categories]);

        return [
            'category_title' => $translations,
            'slug' => 'Category-' . $rand
        ];
    }
}
