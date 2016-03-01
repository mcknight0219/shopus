<?php
namespace App\Wechat;

class MenuService
{
    protected $httpService;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }


}