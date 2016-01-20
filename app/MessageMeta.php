<?php

namespace App;

use App\MessageType;
use Log;
use Illuminate\Database\Eloquent\Model;

class MessageMeta extends Model
{
    protected $table = 'messageMetas';

    public static function create($msgId, Array $props) {
        if( is_null($msgId) || count($props) == 0 ) {
            Log::warning('Message type is invalid or json is empty');
            return;
        }

        $meta = new MessageMeta;
        $meta->id = $msgId;
        $meta->meta = json_encode($props);
        $meta->save();
    }

    // Return a json instead of string
    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }
}
