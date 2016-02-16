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

    public function getEditProfile()
    {
        $profile = Auth::user()->profile;
        if( $profile === null ) {
            $profile = new Profile();
            $profile->user_id = Auth::user()->id;
        }

        return view('cms.edit')->with('profile', $profile);
    }

    public function postEditProfile(Request $request)
    {
        $profile = Profile::where('weixin', $request->weixin)->first();
        if( $profile === null ) {
            $profile = new Profile($request->all());
            $profile->user_id = Auth::user()->id;
        } else {
            // updating
            array_map(function($prop) use ($request, $profile) {
                if( $request->has($prop) && strlen($request->$prop) > 0) {
                    $profile->$prop = $request->$prop;
                }
            }, ['address', 'city', 'state']);
        }
        
        if( $request->hasFile('photo') && $request->file('photo')->isValid('photo') ) {
            $file = $request->file('photo');
            $name = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $profile->photo = $name;

            Storage::disk('s3')->put($name, file_get_contents($file->getRealPath()));
        }

        try {
            $profile->save();
            return Redirect::to('cms');
        } catch(Exception $e) {
            Session::flash('error', 'Failed updating profile. Please try again later.');
            return Redirect::to('cms:profile:edit');
        }
    }
}
