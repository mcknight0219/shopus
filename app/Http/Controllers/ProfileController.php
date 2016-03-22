<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Image;
use Storage;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

class ProfileController extends Controller
{
    public function getProfile(Request $request)
    {
        $user = Auth::user(); 
        return response()->json(array_merge($user->profile->toArray(), ['subscribed' => $user->subscribed]));
    }

    public function postEditProfile(Request $request)
    {
        $profile = Auth::user()->profile;
        // Upload profile photo
        if ($request->hasFile('photo')) {
            $respArr = $profile->savePhoto($request->file('photo')) ?
                ['status' => ok] : ['status' => 'bad', 'errormsg' => 'file is not valid'];
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
}
