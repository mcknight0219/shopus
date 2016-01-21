<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $table = 'outbounds';

    public function message()
    {
        return $this->morphOne('Message', 'messageable');
    }
}