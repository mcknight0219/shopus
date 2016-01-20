<?php

namespace App;

use App\MessageType;
use Log;
use Illuminate\Database\Eloquent\Model;

class MessageMeta extends Model
{
    protected $table = 'messageMetas';

    /**
     * Create message meta for a specific type
     * @param \App\MessageType $type
     * @param $json
     */
    public static function createOfType(MessageType $type, Array $json) {
        if ($type === MessageType::UNKNOWN || count($json) == 0) {
            Log::warning('Message type is invalid or json is empty');
            return;
        }

        $meta = new MessageMeta;
        if ($type === MessageType::Text) {
            $meta->type = 'text';

        }
    }

    // Return a json instead of string
    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }
}
