<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Storage;
use Image;
use Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;

// A controller handles profile page's photo resource
// 
class PhotoController extends Controller
{
    public function getPhoto(Request $request, $userId)
    {
        $user = User::find($userId);
        if( $user === null || strlen($user->profile->photo) === 0 ) {
            return Response::make(null, 404);
        }
        return Image::make(Storage::disk('s3')->get($user->profile->photo))->response();    
    }    
}
