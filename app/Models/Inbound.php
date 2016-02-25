<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = "inbounds";

    public $timestamps = false;

    public function message()
    {
        return $this->morphOne('App\Models\Message', 'messageable');
    }

    public function getContentAttribute($value)
    {
        return json_decode($value, true);
    }
}