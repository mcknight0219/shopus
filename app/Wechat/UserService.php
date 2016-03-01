<?php
namespace App\Wechat;

use Log;

class UserService
{
    protected $httpService;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    public function createGroup($name)
    {
        try {
            $resp = $this->httpService->request('POST', 'groups/create', [
                'form_params' => [
                    'name' => $name
                ]
            ]);
            return $resp['id'];
        } catch (\Exception $e) {
            $this->logError($e);
            return 0;
        }
    }

    public function groups()
    {
        try {
            $resp = $this->httpService->request('GET', 'groups/get', []);
            return $resp['groups'];
        } catch (\Exception $e) {
            $this->logError($e);
            return array();
        }
    }

    public function getGroupId($openid)
    {
        try {
            $resp = $this->httpService->request('POST', 'groups/getid', [
                'form_params' => [
                    'openid' => $openid
                ]
            ]);
            return $resp['groupid'];
        } catch (\Exception $e) {
            $this->logError($e);
            return 0;
        }
    }

    public function move($openid, $toGroupId)
    {
        try {
            $this->httpService->request('POST', 'groups/members/update', [
                'form_params' => [
                    'openid'    => $openid,
                    'to_groupid'=> $toGroupId
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    public function moveBatch($openids, $toGroupId)
    {
        try {
            $this->httpService->request('POST', 'groups/members/bacthupdate', [
                'form_params' => [
                    'openid_list'   => $openids,
                    'to_groupid'    => $toGroupId
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    public function delete($groupid)
    {
        try {
            $this->httpService->request('POST', 'groups/delete', [
                'form_params' => [
                    'id' => $groupid
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    public function info($openid)
    {
       try {
           return $this->httpService->request('POST', 'user/info', [
               'query' => [
                   'lang' => 'zh_CN'
               ],
               'form_params' => [
                   'openid' => $openid
               ]
           ]);
        } catch (\Exception $e) {
            $this->logError($e);
            return array();
        }
    }

    /**
     * Return a list of openids who subscribe our account
     *
     * @return array
     */
    public function users()
    {
        try {
            $resp = $this->httpService->request('GET', 'user/get', []);
            return $resp['data']['openid'];
        } catch (\Exception $e) {
            $this->logError($e);
            return array();
        }
    }


    protected function logError(\Exception $e)
    {
        Log::error(__CLASS__ . " {$e->getMessage()}");
    }
}