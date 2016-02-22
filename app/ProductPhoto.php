<?php

namespace App;

use Product;
use Illuminate\Database\Eloquent\Model;

class ProductPhoto extends Model
{
    public $timestamps = false;

    public function product()
    {
        return $this->hasOne('App\Product');    
    }
}
