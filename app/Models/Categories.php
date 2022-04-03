<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Models\Meals;

class Categories extends Model
{
    use HasTranslations;
    use HasFactory;

    public $translatable = ['category_title'];

}
