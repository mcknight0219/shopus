<?php

namespace App;

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

    /**
     * Check if an msgId already exists in our database
     * @param  Integer $msgId
     * @return Bool
     */
    protected static function exists($msgId) 
    {
        $res = DB::select('SELECT id FROM messages WHERE msgId = ?', $msgId);
        return !is_null($res);
    }

    protected static function epochToTimestamp($epoch)
    {
        $dt = new DateTime("@$epoch");
        return $dt->format('Y-m-d H:i:s');
    }

    protected static function filterXMLFields($value, $index, $exclude = [])
    {
        $exclude = array_map('strtoupper', $exclude);
        $restFields = [];
        foreach(array_keys($index) as $tag) {
            if (array_key_exists($tag, $exclude)) continue;
            else {
                $restFields[$tag] = $value[$index[$tag][0]]['value'];
            }
        }
        return $restFields;
    }
}
