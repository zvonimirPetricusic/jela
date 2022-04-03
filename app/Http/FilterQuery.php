<?php

namespace App\Http;

use Illuminate\Foundation\Http\FilterQuery as Filter;
use App\Models\Language;
use App\Models\Meals;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class FilterQuery
{
    public function filter($url){
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $prepQuery = Meals::select([
            'meals.id','meals.title','meals.description','meals.status','meals.category_id'
        ]);
        $per_page = null;
        

        if(isset($params['with'])){
            $includes = explode(",", $params["with"]);
            foreach ($includes as $include){
                $prepQuery->with($include);
            }
        }

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'categories' :
                    if($value == "NULL"){
                        $prepQuery = $prepQuery->whereNull('category_id');
                    }else if($value == "!NULL"){
                        $prepQuery = $prepQuery->whereNotNull('category_id');
                    }else{
                        $prepQuery = $prepQuery
                                                ->join('categories','categories.id','=','meals.category_id')
                                                ->where('category_id', $value);
                    }
                    break ;
                case 'tag' :
                    $prepQuery =  $prepQuery
                                            ->join('meals_tags','meals.id','=','meals_tags.meals_id')
                                            ->join('tags','tags.id','=','meals_tags.tags_id')
                                            ->groupBy('meals_tags.meals_id');
                    $tags = explode(",", $value);
                    foreach ($tags as $tag){
                        $prepQuery = $prepQuery->havingRaw('SUM(tags.id = ' . $tag . ')');
                    }
                    break ;
                case 'diff_time':
                    $date = date('Y-m-d H:i:s', $value);
                    $prepQuery->withTrashed()
                              ->whereDate("created_at", ">=",  $date );
                    break;
                case 'per_page':
                    $per_page = $value;
                    $prepQuery->take($value);
                    break;
                case 'page':
                    $offset = ($value * $per_page) - $per_page;
                    $prepQuery->skip($offset);
                    break;
             }
         }

         $result = $prepQuery->get();
         return $result;
    }
}
