<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";

    public function message()
    {
        return $this->morphOne('Message', 'messageable');
    }
}