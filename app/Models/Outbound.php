<?php
namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\SimpleXMLElementEx;


class Outbound extends Model
{
    protected $table = 'outbounds';

	public $timestamps = false;

    public function message()
    {
        return $this->morphOne('App\Models\Message', 'messageable');
    }

    /**
     * If the message is considered unique. Outbound message is always unique.
     *
     * @return bool
     */
    public function unique()
    {
        return true;
    }

    public function toXml()
    {
        $xml = new SimpleXMLElementEx('<xml/>');

		$xml->addChildCData('ToUserName', 	$this->message->toUserName);
		$xml->addChildCData('FromUserName',	$this->message->fromUserName);
		$xml->addChild('CreateTime', $this->message->createTime);
		$xml->addChildCData('MsgType', $this->message->msgType);

        // add specific tags according to msg type.
        // Note content fields are always capitalized
        $type = $this->message->msgType;
        switch (true) {
            case strstr($type, 'news'):
				$articles = $this->articles();
				$xml->addChild('ArticleCount', count($articles));
				$sub = $xml->addChild('Articles');
				foreach($articles as $article) {
					$item = $sub->addChild('item');
					$item->addChild('Title', $article['Title']);
					$item->addChild('Description', $article['Description']);
					$item->addChild('PicUrl', $article['PicUrl']);
					$item->addChild('Url', $article['Url']);
				}
            	break;
            case strstr($type, 'text'):
				$xml->addChildCData('Content', $this->content['Content']);
            	break;
			case strstr($type, 'image'):
				$image = $xml->addChild('Image');
            	$image->addChild('MediaId', $this->content['MediaId']);
            	break;
			// We are not going to support those types of media in near future	
            case strstr($type, 'voice'):
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
     * Get the article for news message
     *
     * @return mixed 	false if this is not a news | array of articles
     */
    public function articles()
    {
    	if( $this->message->msgType !== 'news' ) {
    		LOG::warning('Accessing article for non-news message');
    		return false;
    	}
    	
    	$content = $this->content;
    	return $content['Articles'];
    }
    
    public function numArticles()
    {
    	$as = $this->articles();
    	if( $as === false )	return $as;
    	else 		return count($as);
    }

	public function deleteArticle($title)
	{
		if( !is_string($title) || strlen($title) === 0 ) {
			return false;
		}
		
		$content = $this->content;
		$found = false;
		for ($i = 0; $i < count($content['Articles']); $i++) {
			if( $content['Articles'][$i]['Title'] === $title ) {
                unset($content['Articles'][$i]);
				$this->content = json_encode($content);
				$found = true;
			}
		}
		if( $found ) {
			try{ $this->save(); return true; } catch(Exception $e) {
				return false;
			}
		}
		return true;
	}

    /**
     * Add article to content
     * 
     * @param String $title       
     * @param String $description 
     * @param String $picUrl      link to JPG or PNG format. 360x200 for first article and 200x200 for rest
     * @param String $url         link when news clicked
	 * @return Boolean
     */
    public function addArticle($title, $description, $picUrl, $url)
    {
        $content = $this->content;
        if( $content === null ) {
            $content = [];
        }

        if( !array_key_exists('Articles', $content) ) {
        	$content['Articles'] = array();
        }
        $content['Articles'][] = [
        	'Title' 		=> $title,
        	'Description' 	=> $description,
        	'PicUrl' 		=> $picUrl,
        	'Url'			=> $url
        ];

        $this->content = json_encode($content);
		try { $this->save(); return true; } catch(Exception $e) {
        	return false;
        } 
    }
    
}
