<?php

namespace App\Models;

use Log;
use DB;
use Illuminate\Database\Eloquent\Model;

/**
 * The normal message user sends to us through weixin server  
 */
class Message extends Model
{
    protected $table = 'messages';

    public function messageable()
    {
        return $this->morphTo();
    }

    
}