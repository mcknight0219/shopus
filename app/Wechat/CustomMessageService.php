<?php

namespace App\Wechat;

use Log;
use App\Model\Message;
use App\Wechat\HttpServiceInterface;

/**
 * Send customer message when certain events happen
 *
 * @package App\Wechat
 */
class CustomMessageService
{
    /**
     * @var \App\Wechat\HttpServiceInterface
     */
    protected $httpService;

    /**
     * Make new instance of service.
     *
     * @param \App\Wechat\HttpServiceInterface $httpService
     */
    public function __construct(HttpServiceInterface $httpService)
    {
        $this->httpService = $httpService;
    }

    /**
     * Send a text message to a user
     *
     * @param integer $customer
     * @param string  $text 
     * @return void
     */
    public function sendText($customer, $text)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'form_params' => [
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

    /**
     * Send an image message to a user
     *
     * @param integer $customer
     * @param integer $mediaId
     * @return void
     */
    public function sendImage($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
               'form_params' => [
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
    
    /**
     * Send an voice message to a user
     *
     * @param integer $customer
     * @param integer $mediaId
     * @return void
     */
    public function sendVoice($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'form_params' => [
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

    /**
     * Send an voice message to a user
     *
     * @param integer $customer
     * @param integer $mediaId
     * @return void
     */
    public function sendArticle($customer, $mediaId)
    {
        try {
            $this->httpService->request('POST', 'message/custom/send', [
                'form_params' => [
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
