<?php

namespace App\Http\Controllers;

use Log;
use App\Http\Requests;
use App\MessageFactory;
use App\GrandDispatcher;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getIndex(Request $request) 
    {
        if ($this->isCheckSignature($request)) {
            if ($this->isCheckSignature($request)) {
                return $request->input('echostr');
            }
        }
        return "";
    }

    /**
     * Receive normal user message and save them in database
     *
     * @param  Request $request 
     * @return Response
     */
    public function postIndex(Request $request) 
    {
        if ($request->isMethod('post')) {
            try {
                $attributes = json_decode(
                    json_encode((array)simplexml_load_string($request->getContent(), 'SimpleXMLElement', LIBXML_NOCDATA)),
                    true
                );

                return with(new GrandDispatcher)
                    ->handle(with(new MessageFactory)->create($attributes, 'inbound'))
                    ->execute()
                    ->response();
			} catch(Exception $e) {
			    Log::error('Failure at processing message: '.$e->getMessage());	
			}
        }
    }

    protected function isCheckSignature(Request $request)
    {
        foreach(['signature', 'timestamp', 'nonce', 'echostr'] as $field) {
            if (!$request->has($field)) {
                return false;
            }
        }
        return true;
    }

    protected function checkSignature(Request $request)
    {
        $signature = $request->input('signature');
        $timestamp = $request->input('timestamp');
        $nonce = $request->input('nonce');
        $token = env('WECHAT_TOKEN');

        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        return $tmpStr === $signature;
    }
}
