<?php

namespace Database\Seeders;
use App\Models\Language;

use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  
        $languages = 
            [
                '0' => 'EN',
                '1' => 'FR'
            ]
        ;
 
        $arr_size = count($languages);
        for($i=0; $i < $arr_size; $i++){
            Language::factory()->create([
                "title" => $languages[$i]
             ]);
        }

    }
}
