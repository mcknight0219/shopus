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

class ProfileController extends Controller
{
    public function getPhoto(Request $request, $userId)
    {
        $user = User::find($userId);
        if( $user === null || strlen($user->profile->photo) === 0 ) {
            return Response::make(null, 404);
        }
        return Image::make(Storage::disk('s3')->get($user->profile->photo))->response();    
    }    

    /**
     * Show the profile card of user
     *     
     * @param  Request $request
     * @param  String  $userId  
     * @return Response
     */
    public function getProfile(Request $request, $userId)
    {
        $user = User::find($userId);
        if( $user === null ) {
            return Response::make(null, 404);
        }

        return view('profile')->with('profile', $user->profile);
    }

    public function postEditProfile(Request $request)
    {
        if (! $request->ajax()) {
            return;
        }

        if ($request->hasFile('photo')) {

        }

        $profile = Auth::user()->profile;
        if (! $profile) {
            $profile = Profile::create($request->all());
        } else {
            foreach($request->all() as $key => $val) {
                $profile[$key] = $val;
            }
        }

        try {
            $profile->user_id = Auth::user()->id;
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
