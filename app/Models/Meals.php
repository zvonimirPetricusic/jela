<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Tags;
use App\Models\Categories;
use App\Models\Ingrediants;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meals extends Model
{
    use HasTranslations;
    use HasFactory;
    use SoftDeletes;

    public $translatable = ['title', 'description'];

    public function tags()
    {
        return $this->belongsToMany(Tags::class);
    }

    public function ingrediants()
    {
        return $this->belongsToMany(Ingrediants::class,'ingrediant_meals','ingrediant_id','meal_id');
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

}
