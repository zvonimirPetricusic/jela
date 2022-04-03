<?php

namespace App\Http;

use Illuminate\Foundation\Http\ParamValidate as Validator;
use App\Models\Language;
use App\Models\Meals;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class ParamValidate
{
    public function validate($lang){
            $language = Language::where('title', $lang)->first();
            if($lang == null){
                return [ "message" => "required" ];
            }else if(!$language){
                return [ "message" => "not_found" ];
            }else{
                return [ "message" => "success" ];
            }
    }
}
