<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inbound extends Model
{
    protected $table = "inbounds";

    public function message()
    {
        return $this->morphOne('Message', 'messageable');
    }
}