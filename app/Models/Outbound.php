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
    public function toXml()
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
            case strstr($type, 'music'):
                Log::warning('Music/Video outbound message is not implemented !');
                break;
            default:
                Log::error('Not supported message type !');       
        }

        $dom = dom_import_simplexml($xml);
        return $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
    }

    // Return in array format
    public function getContentAttribute($value)
    {
        return json_decode($value, true);
    }

    public function addArticle()
    {
        $content = $this->content;
        $size = ;
        
    }
}
