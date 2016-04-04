<?php

namespace App\Wechat\Store;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $table = 'store_categories';
}
