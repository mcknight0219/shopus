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

    /**
     * Create the menu definition
     *
     * @param array $definition
     * @return \Illuminate\Support\Collection
     */
    public function create(Array $definition)
    {
        try {
            $resp = $this->httpService->request('POST', 'menu/create', [
                'form_params'   => $definition
            ]);
        } catch (\Exception $e) {
            Log::error("Menu service: {$e->getMessage()}");
            $resp = collect();
        } finally {
            return $resp;
        }
    }

    /**
     * Get all menu definition for official account
     *
     * @return mixed
     */
    public function info()
    {
        try {
            $resp = $this->httpService->request('GET', 'get_current_selfmenu_info');
        } catch (\Exception $e) {
            Log::error("Menu service: {$e->getMessage()}");
            $resp = collect();
        } finally {
            return $resp;
        }
    }
}
