<?php

namespace App\Http\Controllers;

use App\QrTicket;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Get the profile data
     *
     * @return Illuminate\Http\Response
     */
    public function getProfile()
    {
        return response()->json(array_merge(auth()->user()->profile->toArray()));
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
        $profile = auth()->user()->profile;
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

    /**
     * Get qr photo url or create one if none existing
     *
     * @return string 
     */
    protected function qrPhoto() {
        $id = auth()->user()->profile->id;
        if (($url = QrTicket::where('scene', $id)->select('url')->first())) {
            return $url;
        }

        // Create a  Qr ticket through QrTicketService
        $qr = QrTicket::createAndReturn($id);
        return $qr ? $qr->url : '';
    }
}
