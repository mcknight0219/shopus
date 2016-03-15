<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Storage;
use Image;
use Response;
use Auth;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Profile;
use App\Subscriber;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        if (! $request->ajax()) { 
            return; // ignore
        }
        $user = Auth::user(); 
        if (! $user || ! $user->profile) {
            return Response::json([
                'status' => 'bad'
            ]);
        }

        $data = $user->profile->toArray();
        $data['subscribed'] = Subscriber::isSubscribed($data['weixin']);
        
        return Response::json($user->profile->toArray());
    }

    public function postEditProfile(Request $request)
    {
        if (! $request->ajax()) {
            return;
        }

        $profile = Auth::user()->profile;
        if (! $profile) {
            $profile = new Profile($request->all());
            $profile->user_id = Auth::user()->id;
        }

        // Upload profile photo
        if ($request->hasFile('photo')) {
            if ($request->file('photo')->isValid()) {
                $content = file_get_contents($request->file('photo'));
                $name = md5($content);
                $profile->photo = $name;
                $profile->save();

                Storage::disk('s3')->put($name, $content);

                return Response::json(['status' => 'ok']);
            } else { 
                return Response::json(['status' => 'bad', 'errormsg' => 'file is not valid']);
            }
        }

        // update data 
        foreach($request->all() as $key => $val) {
                $profile[$key] = $val;
        }

        try {
            $profile->save();
            return Response::json([
                'status' => 'ok'
            ]);
        } catch (\Exception $e) {
            return Response::json([
                'status' => 'bad',
                'error_msg' => $e->getMessage()
            ]);
        }
    }
}
