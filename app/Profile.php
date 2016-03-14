<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'weixin', 'city', 'country', 'firstName', 'lastName'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
