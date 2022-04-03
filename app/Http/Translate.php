<?php

namespace App\Http;

use Illuminate\Foundation\Http\Translate as Translator;
use App\Models\Language;
use App\Models\Meals;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class Translate
{
    public function translate($response, $lang){
        // function body
        $language = Language::where('title',"!=", $lang)->get();
        $response = $response->toArray();

        foreach($response as $key => $value){
            foreach($language as $lang){      

                if(isset($response[$key]["ingrediants"])){
                    $ingrediants = count($response[$key]["ingrediants"]);
                    for($i = 0; $i<$ingrediants; $i++){
                        unset($response[$key]["ingrediants"][$i]["ingrediant_title"][0][strtolower($lang["title"])]);
                    }
                }

                if(isset($response[$key]["tags"])){
                    $tags = count($response[$key]["tags"]);
                    for($i = 0; $i<$tags; $i++){
                        unset($response[$key]["tags"][$i]["tag_title"][0][strtolower($lang["title"])]);
                    }
                }

                if(isset($response[$key]["categories"])){
                    unset($response[$key]["categories"]["category_title"][0][strtolower($lang["title"])]);
                }

                unset($response[$key]["title"][0][strtolower($lang["title"])]);
                unset($response[$key]["description"][0][strtolower($lang["title"])]);

            }
        }
        return $response;
    }
}
