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

    /**
     * Create a message from xml data
     * see http://mp.weixin.qq.com/wiki/17/fc9a27730e07b9126144d9c96eaf51f9.html
     *     
     * @param  String $xmlRaw
     */
    public static function fromXML($xmlRaw)
    {
        if (!is_string($xmlRaw) || strlen($xmlRaw)) {
            Log::warning("xml data should be in string format and not empty");
            return;
        }

        $parser = xml_parser_create();
        if ($ret = xml_parse_into_struct($parser, $xmlRaw, $values, $index) === 0) {
            Log::warning("Error at parsing xml");
        }

        try {
            $msg = new Message;
            foreach(DB::getSchemaBuilder()->getColumnListing('messages') as $column) {
                if ($column === 'id') continue;
                $tag    = strtoupper($column);
                $value  = $values[$index[$tag][0]]['value'];
                if ($column === 'createTime') {
                    $msg[$column] = self::epochToTimestamp($value);
                } else {
                    $msg[$column] = $value;
                }
            }
            // skip if message is sent multiple times
            if (!self::exists($msg->msgId)) {
                $msg->save();
            } 
        } catch (Exception $e) {
            Log::error("Error saving model: $e");
        } finally {
            xml_parser_free($parser);
        }
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
}
