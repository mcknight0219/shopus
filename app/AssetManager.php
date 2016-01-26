<?php
namespace App;

use GuzzleHttp\Client;
use Log;
use App\Models\AccessToken;
use App\Models\Outbound;


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
        $client = new Client(['baseUri' => 'https://api.weixin.qq.com/cgi-bin']);
        $token = new AccessToken;
    }

    protected function guardResponse($response)
    {
        if( array_key_exists('errcode', $response) ) {
            Log::error("Error in response: ${response['errmsg']}");
            throw new ErrorResponseException;
        }
    }


    protected function guardSizeAndFormat($type, $filepath, &$meta)
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

        $meta['filelength'] = $filesize;
        $meta['content-type'] = $fileformat;
        $meta['filename'] = $fileinfo['filename'];
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
            $resp = json_decode($client->request('GET', 'material/get_materialcount', [
                'query' => [
                    'access_token' => $token->get()
                ]
            ])->getBody(), true);
            guardResponse($resp);
            return $resp;
        } catch(ErrorResponseException $e) {
            return [];
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            return [];
        }
    }

    public function assetsList($type)
    {
        $lists = [];
        if( !in_array(strtolower($type), ['image', 'video', 'voice', 'news']) ) {
            Log::error("Unknown asset type: ${type}");
            return $lists;
        }

        try {
            $offset = 0; $total = 0;
            while (true) {
                $resp = client->request('POST', 'material/batchget_material', [
                    'query' => ['access_token' => $token->get()],
                    'body'  => [
                        'type'  => $type,
                        'offset'=> $offset,
                        'count' => 20
                    ]
                ]);
                $decoded = json_decode($resp->getBody(), true);
                guardResponse($decoded);

                $count = $decoded['item_count'];
                $total += $count;
                if( $total === $decoded['total_count'] ) break;
                else $offset = $total;

                $lists = array_merge($lists, $decoded['item']);
            }
        } catch(ErrorResponseException $e) {
            $lists =  [];
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            $lists =  [];
        } finally {
            return $lists;
        }
    }

    /**
     * Upload temporary materials. Supports both remote and local files.
     * Temporary materials will be saved for 3 days in the server
     *      
     * @param  string $type     supported types of material
     * @param  string $filepath file location for material
     * @return array            contains mediaId server generates for us
     */
    public function upload($type, $filepath)
    {
        $info = [];
        if( !is_string($type) || !in_array($type, ['image', 'voice', 'video', 'thumb']) ) {
            Log::error("Unknown asset type: ${type}");
            return
        }

        try {
            $meta = [];
            $this->guardSizeAndFormat($type, $filepath, $meta);
            $resp = $client->request('POST', 'media/upload', [
                'query' => [
                    'type' => $type,
                    'access_token' => $token->get()
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'content' => fopen($filepath, 'r')
                    ]
                ]
            ]);
            $decoded = json_decode($resp->getBody());
            $this->guardResponse($decoded);
            $info = $decoded;
        } catch(ErrorResponseException $e) {
            $info = [];
        } catch(SizeAndFormatException $e) {
            // do nothing
        } catch(Exception $e) {
            Log::error('Http request error: ' . $e->getMessage());
            $info = [];
        } finally {
            return $info;
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
            $resp = $client->request('GET', 'media/get', [
                'query' => [
                    'media_id' => $mediaId,
                    'access_token' => $token->get()
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

    /**
     * Add permanent material to server
     * 
     * @param Outbound $news The model for news outbound message
     */
    public function addNews(Outbound $news)
    {
        
    }
}