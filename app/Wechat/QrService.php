<?php

namespace App\Wechat;

use Log;

class QrService
{
    private $httpService;

    function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * Create permanent ticket
     *
     * @param  integer $sceneId
     * @return \Illuminate\Support\Collection
     */
    public function createTicket($sceneId)
    {
        try {
            return $this->httpService->request('POST', 'qrcode/create', [
                'json' => [
                    'action_name'   => 'QR_LIMIT_SCENE',
                    'action_info'   => [
                        'scene' => ['scene_id' => $sceneId]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('QrCodeSerivce error: ' . $e->getMessage());
        }
    }
}
