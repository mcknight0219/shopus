<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MessageFactory;
use App\GrandDispatcher;

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
     * @param  Request $request 
     * @return Response
     */
    public function postIndex(Request $request) 
    {
        if ($request->post()) {
            $body = $request->getContent();
            $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);
            $attrs = json_decode(json_encode((array)$xml), TRUE);
			try {
				$msg = (new MessageFactory)->create($attrs['MsgType'], $attrs);
				$action = GrandDispatcher::handle($msg);
				
			} catch(Exception $e) {
				
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
