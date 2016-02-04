<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'weixin', 'name', 'address', 'city', 'state'
    ];
}
