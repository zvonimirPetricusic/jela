<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Categories extends Model
{
    use HasTranslations;
    use HasFactory;

    public $translatable = ['category_title'];
}
