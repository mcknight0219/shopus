<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use App\User;
use App\Http\Requests;
use Wechat\QrTicketService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = Auth::user(); 
        return response()->json(array_merge($user->profile->toArray(), ['subscribed' => $user->subscribed, 'qrphoto' => $this->qrPhoto()]));
    }

    /**
     * Only accessed first time user entered weixin id
     * 
     * @param  Request $request 
     * @return Illuminate\Http\Response           
     */
    public function getQrPhoto(Request $request)
    {
        return response()->json(['status' => 'ok', 'qrphoto' => $this->qrPhoto()]);
    }

    /**
     * Edit the profile attributes asynchronously
     * 
     * @param  Request $request 
     * @return Illuminate\Http\Response 
     */
    public function postEditProfile(Request $request)
    {
        $profile = Auth::user()->profile;
        // Upload profile photo
        if ($request->hasFile('photo')) {
            $respArr = $profile->savePhoto($request->file('photo')) ?
                ['status' => 'ok'] : ['status' => 'bad', 'errormsg' => 'file is not valid'];
            return response()->json($respArr);
        }

        try {
            foreach($request->all() as $key => $val) {
                $profile[$key] = $val;
            }
            $profile->save();
            return response()->json([
                'status' => 'ok'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'bad',
                'error_msg' => $e->getMessage()
            ]);
        }
    }

    protected function qrPhoto() {
        $profile = Auth::user();
        /**
         * If user hasn't entered his weixin or has already subscribed
         * to the offical account, return empty string.
         */
        if (! $profile->needRemindSubscribe()) {
            return '';
        }

        if (! is_null($url = QrTicket::where('scene', Auth::user()->profile->id)->select('url')->first())) {
            return $url;
        } 

        // Create a  Qr ticket through QrTicketService
        return with(new QrTicketService)->createTicket($profile->id);
    }
}
