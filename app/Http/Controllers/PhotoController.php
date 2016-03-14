<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Image;
use Response;
use Auth;
use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Product;


class PhotoController extends Controller
{
    public function getProfilePhoto(Request $request)
    {
        $user = Auth::user();
        if( $user === null ) {
            return Response::make(null, 404);
        }
        $photo = $user->profile->photo;
        if (strlen($photo) === 0) {
            return Image::make(public_path() . '/img/ghost_person_200x200_v1.png')->response();
        }

        return Image::make(Storage::disk('s3')->get($user->profile->photo))->response();    
    }

    public function getProductPhoto(Request $request, $productId)
    {
        $type = $request->has('type') ? strtolower($request->get('type')) : 'front';
        if( !in_array($type, ['front', 'back', 'top', 'bottom', 'custom1', 'custom2']) ) {
            $type = 'front';
        }

        $product = Product::find($productId);
        if( $product === null ) {
            return Response::make('', 404);
        }

        foreach( $product->photos as $photo ) {
            if( $photo->type === $type ) {
                return Image::make(Storage::disk('s3')->get($photo->location))->response();
            }
        }
    }
}
