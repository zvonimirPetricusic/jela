<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MealsTags extends Model
{
    use HasTranslations;
    use HasFactory;

    public $translatable = ['title'];
}
