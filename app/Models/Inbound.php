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

    /**
     * If the message is considered unique. For inbound message,
     * we could conveniently use msgId property.
     *
     * @return bool
     */
    public function unique()
    {
        return 1 === static::where('msgId', $this->messageable->msgId)->get()->count();
    }
    public function getContentAttribute($value)
    {
        return json_decode($value, true);
    }
}
