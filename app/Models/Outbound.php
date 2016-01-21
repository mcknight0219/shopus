<?php
namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;

class Outbound extends Model
{
    protected $table = 'outbounds';

    public function message()
    {
        return $this->morphOne('Message', 'messageable');
    }

    // Turn message to xml string so we can send them as response
    public function xml()
    { 
        $xml = new SimpleXML('<xml/>');
        array_map(function ($tag) use ($this) {
            $xml->addChild(ucfirst($tag), $this->message()[$tag]);
        }, ['toUserName', 'fromUserName', 'createTime', 'msgType']);

        // add specific tags according to msg type.
        // Note content fields are always capitalized
        $type = $this->message()->msgType;
        switch (true) {
            case strstr($type, 'news'):
                // most important message. we use them for arrival of new items
                // and promotion event

                break;
            case strstr($type, 'text'):
                $xml->addChild('Content', $this->content['Content']);
                break;
            case strstr($type, 'image'):
                $xml->addChild('MediaId', $this->content['MediaId']);
                break;
            case strstr($type, 'voice'):
                $xml->addChild('MediaId', $this->content['MediaId']);
                break;
            case strstr($type, 'video'):
                Log::warning('Video outbound message is not implemented !');
                break;
            case strstr($type, 'music'):
                Log::warning('Music outbound message is not implemented !');
                break;
            default:
                Log::error('Not supported message type !');       
        }

        $dom = dom_import_simplexml($xml);
        return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
    }

    public function getContentAttribute($value)
    {
        return json_decode($value);
    }
    
    public function articles()
    {
        
    }

}
