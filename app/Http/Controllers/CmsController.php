<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use Storage;
use Redirect;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Profile;

class CmsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if( $user ) {
            $profile = $user->profile;
            $profile = ($profile === null) ? new Profile : $profile;
            
            return view('cms')->with('profile', $profile);
        } else {
            return view('cms');
        }  
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
