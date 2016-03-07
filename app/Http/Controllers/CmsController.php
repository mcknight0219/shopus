<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
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
            if ($profile === null) {
                $profile = new Profile;
            }
            return view('cms')->with('profile', $profile);
        } else {
            return view('cms');
        }  
    }
}
