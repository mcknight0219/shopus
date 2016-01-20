<?php

namespace App;

use Log;
use DB;
use MessageMeta;
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
        if( !is_string($xmlRaw) || strlen($xmlRaw) ) {
            Log::warning("xml data should be in string format and not empty");
            return;
        }

        $parser = xml_parser_create();
        if( xml_parse_into_struct($parser, $xmlRaw, $values, $index) === 0 ) {
            Log::warning("Error at parsing xml");
            return;
        }

        try {
            $msg = new Message;
            $columns = DB::getSchemaBuilder()->getColumnListing('messages');
            foreach( $columns as $column ) {
                if( $column === 'id' ) continue;
                // tag are always uppercase
                $tag    = strtoupper($column);
                $value  = $values[$index[$tag][0]]['value'];
                if( $column === 'createTime' ) {
                    $msg[$column] = self::epochToTimestamp($value);
                } else {
                    $msg[$column] = $value;
                }
            }
            // skip if message is sent multiple times
            if( !self::exists($msg->msgId) ) {
                $msg->save();
                // create meta data for this message
                $meta = $this->filterXMLFields($values, $index, $columns);
                MessageMeta::create($msg->msgId, $meta);
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

    protected static function filterXMLFields($value, $index, $exclude = [])
    {
        $exclude = array_map('strtoupper', $exclude);
        $restFields = [];
        foreach(array_keys($index) as $tag) {
            if (array_key_exists($tag, $exclude)) continue;
            else {
                $restFields[$key] = $value[$index[$tag][0]]['value'];
            }
        }
        return $restFields;
    }
}
