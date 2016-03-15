<?php
namespace App\Wechat;

class QrCodeService
{
    private $httpService;

    function __construct(HttpSerivceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * Create permanent ticket
     */
    public function createTicket($sceneStr)
    {
        try {
            return $this->httpService->request('POST', 'qrcode/create', [
                'form_params' => [
                    'action_name'   => 'QR_LIMIT_SCENE',
                    'action_info'   => [
                        'scene' => ['scene_str' => $sceneStr]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('QrCodeSerivce error: ' . $e->getMessage());
        }
    }
}
