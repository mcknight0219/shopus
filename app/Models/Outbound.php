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

    /**
     * Addd article to content
     * 
     * @param String $title       
     * @param String $description 
     * @param String $picUrl      link to JPG or PNG format. 360x200 for first article and 200x200 for rest
     * @param String $url         link when news clicked
     */
    public function addArticle($title, $description, $picUrl, $url)
    {
        $content = $this->content;
        $size = array_key_exists('ArticleCount', $content) ? $content['ArticleCount'] : 0;
        if( !$size ) {
            $content['ArticleCount'] = 0;
        }

        
    }
}
