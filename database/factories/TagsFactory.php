<?php

namespace Database\Factories;
use App\Models\Tags;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class TagsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    public function definition()
    {
        $rand =  $this->faker->randomNumber();

        $fr_tags = "TagFR-" . $rand;
        $en_tags = "TagEN-" . $rand;
        
        $translations = array(['en' => $en_tags, 'fr'=>$fr_tags]);

        return [
            'tag_title' => $translations,
            'slug' => 'Tag-' . $rand
        ];
    }
}
