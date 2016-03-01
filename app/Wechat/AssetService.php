<?php
namespace App\Wechat;

use App\Wechat\HttpServiceInterface;
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
    protected $httpService;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    public function count($type)
    {
        try {
            $resp = $this->httpService->request('GET', 'material/get_materialcount', []);
            $key = $type . '_count';
            return $resp[$key];
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
                $resp = $this->httpService->request('POST', 'material/batchget_material', [
                    'form_params' => [
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

    public function upload($type, $filepath)
    {
        try {
            $resp = $this->httpService->request('POST', 'media/upload', [
                'query' => [
                    'type' => $type
                ],
                'multipart' => [
                    [
                        'name' => 'media',
                        'contents' => fopen($filepath, 'r')
                    ]
                ]
            ]);
            return $resp['media_id'];
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            $this->logError($e);
            return self::ERROR_RESPONSE_SCALAR;
        }
    }

    public function article($attr)
    {
        try {
            $resp = $this->httpService->request('POST', 'material/add_news', [
                'form_params' => $attr
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
            $resp = $this->httpService->request('POST', 'material/add_material', [
                'multipart' => [
                    'name'      => 'media',
                    'type'      => 'image',
                    'contents'   => fopen($filepath, 'r')
                ]
            ]);

            return $resp['media_id'];
        } catch (\Exception $e) {
            $this->logError($e);
            return self::ERROR_RESPONSE_SCALAR;
        }
    }


    public function delete($mediaId)
    {
        try {
            $this->httpService->request('POST', 'material/del_material', [
                'form_params' => [
                    'media_id' => $mediaId
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }


    protected function logError(\Exception $e)
    {
        Log::error("Failed processing material: {$e->getMessage()}");
    }
}