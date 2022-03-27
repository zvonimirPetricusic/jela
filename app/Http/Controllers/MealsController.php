<?php

namespace App\Http\Controllers;

use App\Models\Meals;
use App\Models\Tags;
use App\Models\TagMeal;
use App\Models\IngrediantMeals;
use App\Models\Ingrediants;
use App\Models\Language;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;

class MealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $per_page = isset($_GET["per_page"]) ? $_GET["per_page"] : null;
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        $category = isset($_GET["category"]) ? $_GET["category"] : false;
        $tags = isset($_GET["tags"]) ? $_GET["tags"] : null;
        $with = isset($_GET["with"]) ? $_GET["with"] : null;
        $lang = isset($_GET["lang"]) ? $_GET["lang"] : null;
        $diff_time = isset($_GET["diff_time"]) ? $_GET["diff_time"] : null;
        $where_diff_time_raw = ' AND meals.status = "created"';
        $where_diff_time = '';
        $data = [];
        $meals_count = Meals::where('status' , 'created')->get();
    
        $with_ingrediants = 0;
        $with_tags = 0;
        $with_categories = 0;
        $pieces = explode(",", $with);

        foreach($pieces as $piece){
            if($piece == 'categories'){
                $with_categories = 1;
            }else if($piece == 'tags'){
                $with_tags = 1;
            }else if($piece == 'ingrediants'){
                $with_ingrediants = 1;
            }
        }
        $data["itemPerPage"] = 'ALL';
        $data["currentPage"] = 1;
        $data["totalItems"] = count($meals_count);    
        $data["totalPages"] = 1;

        if($per_page != null){
            $data["itemPerPage"] = $per_page;
            $data["totalPages"] = ceil(count($meals_count) / $per_page);
        }
        if($page != null){
            $data["currentPage"] = $page;
        }
        
        if($diff_time != null){
            $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
            $where_diff_time_raw = ' AND meals.created_at > "' . $timestamp . '"';
        }
        
        if(!isset($lang)){
            return "lang parameter is required!";
        }else{
            $language = Language::where('title', $lang)->first();
            if(!$language){
                return "Language doesn't exist in database!";
            }

            if($category != false || $tags != null){
                if($category != false && $tags != null){
                    if($per_page != null && $page == null){

                        $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals  
                        LEFT JOIN meals ON tag_meals.meal_id = meals.id 
                        WHERE meals.category_id = ' . $category . ' 
                        AND  tag_meals.tag_id ' . $where_diff_time_raw . '
                        IN ( ' . $tags . ' ) LIMIT ' . $per_page));
                    }else if($page != null && $per_page != null){
                        $offset = ($page * $per_page) - $per_page;
                        $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals  
                        LEFT JOIN meals ON tag_meals.meal_id = meals.id 
                        WHERE meals.category_id = ' . $category . ' 
                        AND  tag_meals.tag_id 
                        IN ( ' . $tags . ' ) ' . $where_diff_time_raw . ' LIMIT ' . $per_page . ' OFFSET ' . $offset));
                    }else{
                        $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals  
                        LEFT JOIN meals ON tag_meals.meal_id = meals.id 
                        WHERE meals.category_id = ' . $category . ' ' . $where_diff_time_raw . '
                        AND  tag_meals.tag_id 
                        IN ( ' . $tags . ' )'));  
                    }
                     
                    $counter_tag = 0;
                    $meals = [];
                    foreach($tag_meals as $tag_meal){
                        $meals = Meals::where('id', $tag_meal->meal_id)->where('category_id', $category)->first();
                        $translations_title_meal = $meals->getTranslations('title');  
                        $translations_description_meal = $meals->getTranslations('description');  
                        $description = $translations_description_meal[0][$lang];
                        $title = $translations_title_meal[0][$lang];
                        $tags = Tags::where('id',$tag_meal->tag_id)->first();
                        $translations_title_tag = $tags->getTranslations('tag_title'); 
                        $title_tag = $translations_title_tag[0][$lang];
                        //MEALS
                        $data[$counter_tag]["id"] = $meals["id"];
                        $data[$counter_tag]["title"] = $title;
                        $data[$counter_tag]["description"] = $description;
                        $data[$counter_tag]["status"] = $meals["status"];
                        // TAGS
                        if($with_tags != 0){
                            $data[$counter_tag]["tags"]["id"] = $tags["id"];
                            $data[$counter_tag]["tags"]["title"] = $translations_title_tag;
                            $data[$counter_tag]["tags"]["slug"] = $tags["slug"];
                        }
                        // CATEGORIES
                        $categories = Categories::where('id',$category)->first();
                        $translations_title_category = $categories->getTranslations('category_title'); 
                        $title_category = $translations_title_category[0][$lang]; 
                        if($with_categories != 0){
                            $data[$counter_tag]["categories"]["id"] = $categories["id"];
                            $data[$counter_tag]["categories"]["title"] = $translations_title_category;
                            $data[$counter_tag]["categories"]["slug"] = $categories["slug"];
                        }
                        //INGREDIANTS
                        $ingrediant_meals = IngrediantMeals::where('meal_id', $meals["id"])->get();
                        foreach($ingrediant_meals as $ingrediant_meal){
                            $ingrediant = Ingrediants::where('id', $ingrediant_meal["ingrediant_id"])->first();
                            $translations_title_ingrediant = $ingrediant->getTranslations('ingrediant_title'); 

                            $title_ingrediant = $translations_title_ingrediant[0][$lang];   
                            if($with_ingrediants != 0){
                                $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["id"] = $ingrediant["id"];
                                $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["title"] = $title_ingrediant;
                                $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["slug"] = $ingrediant["slug"];
                            }

                        }
                        $counter_tag++;
                    }
                    $title_category = null;

                    return $data;
                }else{
                    if($category != false){
                        if($per_page != null && $page == null){
                            if($diff_time != null){
                                $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                                $meals = Meals::where('category_id', $category)->whereDate("created_at", ">=",  $timestamp )->take($per_page)->get(); 
                            }else{
                                $meals = Meals::where('category_id', $category)->where('status','created')->take($per_page)->get(); 
                            }
                        }else if($page != null && $per_page != null){
                            $offset = ($page * $per_page) - $per_page;
                            if($diff_time != null){
                                $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                                $meals = Meals::where('category_id', $category)->whereDate("created_at", ">=",  $timestamp )->skip($offset)->take($per_page)->get();  
                            }else{
                                $meals = Meals::where('category_id', $category)->where('status','created')->skip($offset)->take($per_page)->get();  
                            }
                        }else{
                            if($diff_time != null){
                                $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                                $meals = Meals::where('category_id', $category)->whereDate("created_at", ">=",  $timestamp )->get();  
                            }else{
                                $meals = Meals::where('category_id', $category)->where('status','created')->get();  
                            }
                        }
                        
                        $categories = Categories::where('id',$category)->first();
                        $translations_title_category = $categories->getTranslations('category_title'); 
                        $title_category = $translations_title_category[0][$lang];   
                    }else if($tags != null){
                        if($per_page != null && $page == null){
                            $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals WHERE tag_id IN ( ' . $tags . ' ) LIMIT ' . $per_page));
                        }else if($page != null && $per_page != null){
                            $offset = ($page * $per_page) - $per_page;
                            $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals WHERE tag_id IN ( ' . $tags . ' ) LIMIT ' . $per_page . ' OFFSET ' . $offset));
                        }else{
                            $tag_meals = DB::select( DB::raw('SELECT * FROM tag_meals WHERE tag_id IN ( ' . $tags . ' )'));
                        }
                        $counter_tag = 0;
                        $meals = [];
                        foreach($tag_meals as $tag_meal){
                            if($diff_time != null){
                                $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                                $meals = Meals::where('id', $tag_meal->meal_id)->whereDate("created_at", ">=",  $timestamp )->first();
                            }else{
                                $meals = Meals::where('id', $tag_meal->meal_id)->where('status','created')->first();
                            }
                            
                            if($meals){
                                $translations_title_meal = $meals->getTranslations('title');  
                                $title = $translations_title_meal[0][$lang];
                                $translations_description_meal = $meals->getTranslations('description');  
                                $description = $translations_description_meal[0][$lang];
                                $tags = Tags::where('id',$tag_meal->tag_id)->first();
                                $translations_title_tag = $tags->getTranslations('tag_title'); 
                                $title_tag = $translations_title_tag[0][$lang]; 
                            
                                $data[$counter_tag]["id"] = $meals["id"];
                                $data[$counter_tag]["title"] = $title;
                                $data[$counter_tag]["description"] = $description;
                                $data[$counter_tag]["status"] = $meals["status"];
                                //TAGS
                                if($with_tags != 0){
                                    $data[$counter_tag]["tags"]["id"] = $meals["id"];
                                    $data[$counter_tag]["tags"]["title"] = $translations_title_tag;
                                    $data[$counter_tag]["tags"]["slug"] = $tags["slug"];
                                }
                                //INGREDIANTS
                                $ingrediant_meals = IngrediantMeals::where('meal_id', $meals["id"])->get();
                                foreach($ingrediant_meals as $ingrediant_meal){
                                    $ingrediant = Ingrediants::where('id', $ingrediant_meal["ingrediant_id"])->first();
                                    $translations_title_ingrediant = $ingrediant->getTranslations('ingrediant_title'); 
                                    $title_ingrediant = $translations_title_ingrediant[0][$lang];
                                    if($with_ingrediants != 0){
                                        $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["id"] = $ingrediant["id"];
                                        $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["title"] = $title_ingrediant;
                                        $data[$counter_tag]["ingrediant"][$ingrediant["id"]]["slug"] = $ingrediant["slug"];
                                    }   
                                }
                                $counter_tag++;
                            }

                        }
        
                        $title_category = null;
                        return $data;
                    }
                }
            }
            else{
                if($per_page != null && $page == null){
                    if($diff_time != null){
                        $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                        $meals = Meals::whereDate("created_at", ">=",  $timestamp )->take($per_page)->get(); 
                    }else{
                        $meals = Meals::take($per_page)->where('status','created')->get(); 
                    }
                }else if($page != null && $per_page != null){
                    $offset = ($page * $per_page) - $per_page;
                    
                    if($diff_time != null){
                        $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                        $meals = Meals::whereDate("created_at", ">=",  $timestamp )->skip($offset)->take($per_page)->get(); 
                    }else{
                        $meals = Meals::where('status','created')->skip($offset)->take($per_page)->get(); 
                    }
                }else{
                    if($diff_time != null){
                        $timestamp = gmdate("Y-m-d H:i:s", $diff_time);
                        $meals = Meals::whereDate("created_at", ">=",  $timestamp )->get();   
                    }else{
                        $meals = Meals::where('status','created')->get(); 
                    }
                }
                $title_category = null;
            }
            
            $counter = 0;
            foreach($meals as $meal){
                $translations_title_meal = $meal->getTranslations('title');  
                $title = $translations_title_meal[0][$lang];
                $translations_description_meal = $meal->getTranslations('description');  
                $description = $translations_description_meal[0][$lang];

                $data[$counter]["id"] = $meal["id"];
                $data[$counter]["title"] = $title;
                $data[$counter]["description"] = $description;
                $data[$counter]["status"] = $meal["status"];

                if($title_category != null){
                    if($with_categories != 0){
                        $data[$counter]["category"]["id"] = $categories["id"];
                        $data[$counter]["category"]["title"] = $title_category;
                        $data[$counter]["category"]["slug"] = $categories["slug"];
                    }
                }

                $ingrediant_meals = IngrediantMeals::where('meal_id', $meal["id"])->get();
                foreach($ingrediant_meals as $ingrediant_meal){
                    $ingrediant = Ingrediants::where('id', $ingrediant_meal["ingrediant_id"])->first();
                    $translations_title_ingrediant = $ingrediant->getTranslations('ingrediant_title'); 
                    $title_ingrediant = $translations_title_ingrediant[0][$lang];   
                    
                    if($with_ingrediants != 0){
                        $data[$counter]["ingrediant"][$ingrediant["id"]]["id"] = $ingrediant["id"];
                        $data[$counter]["ingrediant"][$ingrediant["id"]]["title"] = $title_ingrediant;
                        $data[$counter]["ingrediant"][$ingrediant["id"]]["slug"] = $ingrediant["slug"];
                    } 
                }
                $counter++;
            }
            return $data;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Meals  $meals
     * @return \Illuminate\Http\Response
     */
    public function show(Meals $meals)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meals  $meals
     * @return \Illuminate\Http\Response
     */
    public function edit(Meals $meals)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Meals  $meals
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meals $meals)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meals  $meals
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meals $meals)
    {
        //
    }
}
