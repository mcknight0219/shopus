<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;

class MessageMeta extends Model
{
    protected $table = 'messageMetas';

    // Return a json instead of string
    public function getMetaAttribute($value) 
    {
        return json_decode($value);
    }
}
