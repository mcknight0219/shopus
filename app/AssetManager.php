<?php
namespace App;

use GuzzleHttp\Client;
use Log;
use App\Models\AccessToken;
use App\Models\Outbound;
use Sunra\PhpSimple;

class ErrorResponseException extends Exception;
class SizeAndFormatException extends Exception;

// We don't need to store assets or meta in our database because
// weixin will store and deliver them as long as we have MediaId 
class AssetManager
{
    /**
     * Http client
     * 
     * @var GuzzleHttp\Client
     */
    protected $client;

    protected $token;

    function __construct()
    {
        $this->client = new Client(['baseUri' => 'https://api.weixin.qq.com/cgi-bin']);
        $this->token = new AccessToken;
    }

    protected function guardResponse($response)
    {
        if( array_key_exists('errcode', $response) ) {
            Log::error("Error in response: ${response['errmsg']}");
            throw new ErrorResponseException;
        }
    }

    protected function guardSizeAndFormat($type, $filepath)
    {
        $fileInfo = $this->analyzeFile($filepath);
        if( array_key_exists('error', $fileinfo) ) {
            Log::warning($fileinfo['error'][0]);
            throw new SizeAndFormatException
        }

        $filesize =     $fileInfo['filesize'];
        $fileformat =   $fileInfo['fileformat'];
        static $limits = [
            'image' =>      ['size' => 1024 * 1024,      'format' => ['bmp', 'png', 'jpg', 'gif']],
            'voice' =>      ['size' => 2 * 1024 * 1024,  'format' => ['mp3', 'amr', 'wma', 'wav']],
            'video' =>      ['size' => 10 * 1024 * 1024, 'format' => ['mp4']],
            'thumbnail' =>  ['size' => 64 * 1024,        'format' => ['bmp', 'png', 'jpg', 'gif']]
        ];

        if( !in_array($fileformat, $limits[$type]['format']) || $fileformat > $limits[$type]['size'] ) {
            throw new SizeAndFormatException;
        }
        // audio can not be longer than 60s
        if( $type === 'voice' && $fileinfo['playtime_seconds'] > 60) {
            throw new SizeAndFormatException;
        }
    }

    /**
     * Analyze file using GetID3 library. Also support remote file
     * @param  string $path url or local file path
     * @return array
     */
    protected function analyzeFile($path)
    {
        $identifier = new \getID3();
        if( filter_var($path, FILTER_VALID_URL) ) {
            $fpRead = fopen($path, 'rb');
            if ( $fpRead === FALSE )  return ["error" => ["Cannot open file ${path}"]];
            $path = tempnam('/tmp', 'getID3');
            $fpWrite = fopen($path, 'wb');
            if ( $fpWrite === FALSE ) {
                fclose($fpRead);
                return ["error" => ["Cannot create tmp file ${path}"]];
            }

            while ($buffer = fread($fpRead, 8192)) {
                fwrite($fpWrite, $buffer);
            }
            fclose($fpRead);
            $fileinfo = $identifier->analyze($path);
            unlink($path);
        } else {
            $fileinfo = $identifier->analyze($path);
        }

        return $fileinfo;
    }

    public function assetsCount()
    {
        try {
            $resp = json_decode($this->client->request('GET', 'material/get_materialcount', [
                'query' => [
                    'access_token' => $this->token->get()
                ]
            ])->getBody(), true);
            guardResponse($resp);
            return $resp;
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return FALSE;
        }
    }

    public function assetsList($type)
    {
        if( !in_array(strtolower($type), ['image', 'video', 'voice', 'news']) ) {
            Log::error("Unknown asset type: ${type}");
            return FALSE;
        }

        try {
            $offset = 0; $total = 0;
            while (true) {
                $resp = $this->client->request('POST', 'material/batchget_material', [
                    'query' => ['access_token' => $token->get()],
                    'body'  => [
                        'type'  => $type,
                        'offset'=> $offset,
                        'count' => 20
                    ]
                ]);
                $respBody = json_decode($resp->getBody(), true);
                guardResponse($respBody);

                $count = $respBody['item_count'];
                $total += $count;
                if( $total === $respBody['total_count'] ) break;
                else $offset = $total;

                return array_merge($lists, $respBody['item']);
            }
        } catch(Exception $e) {
            Log::error($e->getMessage());
            return FALSE;
        }
    }

    /**
     * Upload temporary materials. Supports both remote and local files.
     * Temporary materials will be saved for 3 days in the server
     *      
     * @param  string $type     supported types of material
     * @param  string $filepath file location for material
     * @return mixed            FALSE on failure, array of response on success
     */
    public function upload($type, $filepath)
    {
        if( !is_string($type) || !in_array($type, ['image', 'voice', 'video', 'thumb']) ) {
            Log::error("Unknown asset type: ${type}");
            return FALSE;
        }

        try {
            $this->guardSizeAndFormat($type, $filepath);
            $resp = $this->client->request('POST', 'media/upload', [
                'query' => [
                    'type' => $type,
                    'access_token' => $this->token->get()
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'content' => fopen($filepath, 'r')
                    ]
                ]
            ]);
            $respBody = json_decode($resp->getBody());
            $this->guardResponse($respBody);
            return $respBody;
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Download the temporary material
     *
     * @param  string $mediaId
     * @param  string $type    supported material type
     * @param  string $where   intended location to save download file
     * @return mixed           FALSE on failure, saved file path on success
     */
    public function download($mediaId, $type, $where = '')
    {
        if( strlen($where) ) {
            $where = '/tmp';
        }
        
        try {
            $resp = $this->client->request('GET', 'media/get', [
                'query' => [
                    'media_id' => $mediaId,
                    'access_token' => $this->token->get()
                ],
                'stream' => true
            ]);
            preg_match('', $resp->getHeader('Content-disposition'), $match)
            $path = $where + '/' + $match[1];
            $fp = fopen($path, 'wb');
            while( !$resp->getBody()->eof() ) {
                $fwrite($fp, $resp->getBody()->read(8192));
            }
            fclose($fp);
            return $path;
        } catch(ErrorResponseException $e) {
            return FALSE;
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            return FALSE;
        }
    }

    // All external image url in article content is discarded. It needs to
    // be uploaded to weixin server and use their provided url instead.
    // 
    // The function automatically converts any non-tencent image link
    protected function preprocessArticleContent($html)
    {
        $html = str_get_html($html);
        foreach( $html->find('img') as $element ) {
            $link = $element->src;
            
        }
    }

    /**
     * Add permanent material to server
     * 
     * @param Array $attributes
     * @return  mixed  FALSE on failure, array of response(contains mediaId) on success
     */
    public function createArticle(Array $attributes)
    {
        static $attrs = ['title', 'cover', 'author', 'digest', 'content', 'url'];
        if( !array_reduce($attrs, function($carry, $a) { return $carry && array_key_exists($a, $attributes); }, true) {
            Log::warning('Must provide all fields to create an article');
            return FALSE;
        }

        try {
            $resp = $this->client->request('POST', 'material/add_news', [
                'query' => ['access_token' => $this->token->get()],
                'body' => [
                    'title' => $attributes['title'],
                    'thumbnail_media_id' => $attributes['cover'],
                    'author' => $attributes['author'],
                    'digest' => $attributes['digest'],
                    'show_cover_pic' => 1,
                    'content' => $attributes['content'],
                    'content_source_url' => $attributes['url']
                ]
            ]);
            $respBody = json_decode($resp->getBody());
            $this->guardResponse($respBody);
            return $respBody;
        } catch (ErrorResponseException $e) {
            return FALSE;
        } catch (Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            return FALSE;
        }
    }

    /**
     * Create permanent material for media file. 
     * @param  string $pathOrUrl either url or local path
     * @return mixed            FALSE on failure, array of response on success
     */
    protected function createMedia($type, $pathOrUrl)
    {
        if( $type !== 'image' ) { LOG::error('Not implemented for other media type'); return FALSE; }
        try {
            $this->guardSizeAndFormat($type, $pathOrUrl);
            $resp = $this->client->request('POST', 'material/add_material', [
                'query' => ['access_token' => $this->token->get()],
                'multipart' => [
                    [
                        'name' => 'media',
                        'content' => fopen($pathOrUrl, 'r');
                    ]
                ]
            ]);
            $respBody = json_decode($resp->getBody());
            $this->guardResponse($respBody);
            return $respBody;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return FALSE;
        }
    }

    public function createImage($pathOrUrl)
    {
        return $this->createMedia('image', $pathOrUrl);
    }
}