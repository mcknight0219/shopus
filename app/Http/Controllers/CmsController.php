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
            return view('cms')->with('profile', $user->profile);
        } else {
            return view('cms');
        }  
    }


    public function getEditProfile()
    {
        return view('cms.edit')->with('profile', Auth::user()->profile);
    }

    public function postEditProfile(Request $request)
    {
        $profile = new Profile($request->all());
        $profile->user_id = Auth::user()->id;
        if( $request->hasFile('photo') && $request->isValid('photo') ) {
            $file = $request->file('photo');
            $name = md5_file($file->getRealPath()) . '.' . $file->getOriginalExtension();
            $destPathPrefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $file->move($destPathPrefix, $name);
            $profile->photo = $name;
        }

        try {
            $profile->save();
            return view('cms')->with('profile', $profile);
        } catch(Exception $e) {
            Session::flash('error', 'Failed updating profile. Please try again later.');
            return Redirect::to('cms/profile/edit');
        }
    }

    public function getAddProduct()
    {

    }

    public function postAddProduct()
    {

    }
}
