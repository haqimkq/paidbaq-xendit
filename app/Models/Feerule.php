<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feerule extends Model
{
    protected $table = "feerules";
    protected $guarded = [];


    public function routes()
    {
        return $this->hasMany('App\FeeruleDetail');
    }
}
