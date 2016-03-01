<?php
namespace App\Wechat;

use Log;

// We don't need to store assets or meta in our database because
// weixin will store and deliver them as long as we have MediaId 
class AssetService
{
    const ERROR_RESPONSE_ARRAY = array();
    const ERROR_RESPONSE_SCALAR = '';

    /**
     * @var The wechat http service
     */
    protected $httpSerice;

    function __construct()
    {
        $this->httpSerice = App::make('App\Wechat\HttpServiceInterface');
    }

    /**
     * Get the total number of materials uploaded
     * @param $type  String could be [voice, video, image, news]
     * @return Integer
     */
    public function count($type)
    {
        try {
            $resp = $this->httpSerice->request('GET', 'material/get_materialcount', []);
            return $resp[$type];
        } catch (\Exception $e) {
            $this->logError($e);
            return self::ERROR_RESPONSE_SCALAR;
        }
    }

    public function batch($type)
    {
        try {
            $offset = 0; $total = 0; $batch = [];
            while (true) {
                $resp = $this->httpSerice->request('POST', 'material/batchget_material', [
                    'body' => [
                        'type'      => $type,
                        'offset'    => $offset,
                        'count'     => 20
                    ]
                ]);
                $count = $resp['item_count'];
                $total += $count;
                $batch = array_merge($batch, $resp['item']);
                if ($total === $resp['total_count']) {
                    break;
                }
                $offset = $total;
            }

            return $batch;
        } catch (\Exception $e) {
            $this->logError($e);
            return self::ERROR_RESPONSE_ARRAY;
        }
    }

    /**
     * Create temporary material on remote server. It will be deleted after 3 days
     *
     * @param $type [image, voice, video, thumb]
     * @param $filepath
     * @return Array
     */
    public function upload($type, $filepath)
    {
        try {
            $resp = $this->httpSerice->request('POST', 'media/upload', [
                'query' => [
                    'type' => $type
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'content' => fopen($filepath, 'r')
                    ]
                ]
            ]);

            return $resp['media_id'];
        } catch (\Exception $e) {
            $this->logError($e);
            return self::ERROR_RESPONSE_SCALAR;
        }
    }

    /**
     * Create a single news material
     *
     * @param $attr
     * @return array
     */
    public function article($attr)
    {
        try {
            $content = $this->preprocessArticleContent($attr['content']);
            unset($attr['content']);
            $attr['content'] = $content;

            $resp = $this->httpSerice->request('POST', 'material/add_news', [
                'body' => $attr
            ]);

            return $resp['media_id'];
        } catch (\Exception $e) {
            $this->logError($e);
            return self::ERROR_RESPONSE_SCALAR;
        }
    }

    public function image($filepath)
    {
        try {

        } catch (\Exception $e) {
            $this->logError($e);
            return self::
        }
    }

    /**
     * Create permanent material for media file. 
     * 
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


    /**
     * Remove a material given media id
     *         
     * @param  String $mediaId
     * @return mixed 
     */
    public function delete($mediaId)
    {
        try {
            $resp = $this->client->request('POST', 'material/del_material', [
                'query' => ['access_token' => $this->token->get()],
                'body'  => ['media_id' => $mediaId]
            ]);
            $respBody = json_decode($resp);
            $this->guardResponse($respBody);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return FALSE;
        }
    }

    /**
     * Replace external image url with uploaded url so it won't be ignored
     */
    protected function preprocessArticleContent($html)
    {
        $html = str_get_html($html);
        static $host = 'mmbiz.qpic.cn';
        foreach( $html->find('img') as $element ) {
            $link = $element->src;
            if( parse_url($link)['host'] !== $host ) {
                $resp = $this->client->request('POST', 'uploadimg', [
                    'query' => ['access_token' => $this->token->get()],
                    'multipart' => [
                        [
                            'name' => 'media',
                            'content' => fopen($link, 'r')
                        ]
                    ]
                ]);
                $respBody = json_decode($resp);
                // no need to panic, server will remove that link for us
                if( array_key_exists('url', $respBody) ) $element->src = $respBody['url'];
                else continue;
            }
        }

        return $html->save();
    }

    protected function logError(\Exception $e)
    {

    }
}