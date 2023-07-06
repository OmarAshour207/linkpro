<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Translatable;
    use HasFactory;

    public $timestamps = false;

    public $translationModel = 'App\Models\ServiceTranslation';
    public $translatedAttributes = ['name', 'description'];

    protected $with = ['translations'];
}
