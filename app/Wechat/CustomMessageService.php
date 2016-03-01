<?php
namespace App\Wechat;

use App\Wechat\HttpServiceInterface;
use Log;
/**
 * Send customer message when certain events happen
 *
 * @package App\Wechat
 */
class CustomMessageService
{
    protected $httpService;

    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    public function sendText($customer, $text)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'body' => [
                    'touser'    => $customer,
                    'msgtype'   => 'text',
                    'text'      => [
                        'content'   => $text
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    public function sendImage($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
               'body' => [
                   'touser'     => $customer,
                   'msgtype'    => 'image',
                   'image'      => [
                       'medid_id'   => $mediaId
                   ]
               ]
            ]);
        } catch (\Exceptionion $e) {
            $this->logError($e);
        }
    }

    public function sendVoice($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'body' => [
                    'touser'    => $customer,
                    'msgtype'   => 'voice',
                    'voice'     => [
                        'media_id'  => $mediaId
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    public function sendArticle($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'body' => [
                    'touser'    => $customer,
                    'msgtype'   => 'mpnews',
                    'mpnews'    => [
                        'media_id' => $mediaId
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $this->logError($e);
        }
    }

    protected function logError(\Exception $e)
    {
        Log::error("Failed sending custom message: {$e->getMessage()}");
    }
}