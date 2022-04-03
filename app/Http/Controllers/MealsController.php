<?php

namespace App\Http\Controllers;

use App\Models\Meals;
use App\Models\Tags;
use App\Models\MealsTags;
use App\Models\IngrediantMeals;
use App\Models\Ingrediants;
use App\Models\Language;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Translatable\HasTranslations;
use App\Http\FilterQuery as Filter;
use App\Http\Translate as Translator;
use App\Http\ParamValidate as Validator;

class MealsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function meals(){ 
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $lang = isset($_GET["lang"]) ? $_GET["lang"] : null;
        $page = isset($_GET["page"]) ? $_GET["page"] : null;
        $per_page = isset($_GET["per_page"]) ? $_GET["per_page"] : null;

        $category = isset($_GET["category"]) ? $_GET["category"] : false;
        $tags = isset($_GET["tags"]) ? $_GET["tags"] : null;
        $with = isset($_GET["with"]) ? $_GET["with"] : null;

        $meals_count = Meals::where('status' , 'created')->get();

        $translated_response["meta"]["currentPage"] = $page;
        $translated_response["meta"]["itemsPerPage"] = $per_page;
        $translated_response["meta"]["totalItems"] = count($meals_count);
        $translated_response["meta"]["totalPages"] = ceil(count($meals_count) / $per_page);
       
        $validate = Validator::validate($lang);

        if($validate["message"] == "required") {
            return "Language is required";
        }else if($validate["message"] == "not_found"){
            return "Lanaguage doesn't exist in database";
        }

        $response = Filter::filter($url);

        $translated_response["data"] = Translator::translate($response, $lang);

        $prev = $page - 1;
        $next = $page + 1;

        $translated_response["links"]["prev"] = "http://localhost:8000/api/meals?per_page=" . $per_page 
                                                . "&tags=" . $tags . "&lang=" . $lang 
                                                . "&with=" . $with . "&page=" . $prev;

        $translated_response["links"]["next"] = "http://localhost:8000/api/meals?per_page=" . $per_page 
                                                . "&tags=" . $tags . "&lang=" . $lang 
                                                . "&with=" . $with . "&page=" . $next;
        $translated_response["links"]["self"] = "http://localhost:8000/api/meals?per_page=" . $per_page 
                                                . "&tags=" . $tags . "&lang=" . $lang 
                                                . "&with=" . $with . "&page=" . $page;

        return response()->json($translated_response, 200, 
                                ['Content-type'=> 'application/json; charset=utf-8'], 
                                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
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
