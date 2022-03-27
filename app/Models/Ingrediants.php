<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Ingrediants extends Model
{
    use HasTranslations;
    use HasFactory;

    public $translatable = ['ingrediant_title'];
}
