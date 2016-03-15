<?php
namespace App\Wechat;

use Log;

class MenuService
{
    protected $httpService;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    public function create($definition)
    {
        try {
            $this->httpService->request('POST', 'menu/create', [
                'form_params'   => $definition
            ]);
        } catch (\Exception $e) {
            Log::error("Menu service: {$e->getMessage()}");
        }
    }

    public function info()
    {
        try {
            $info = $this->httpService->request('GET', 'get_current_selfmenu_info', []);
        } catch (\Exception $e) {
            Log::error("Menu service: {$e->getMessage()}");
        }
    }
}
