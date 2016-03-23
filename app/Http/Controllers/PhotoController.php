<?php
namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use App\User;
use App\ProductPhoto;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function getProfilePhoto(Request $request)
    {
        $photo = Auth::user()->profile->photo;
        if ('' === $photo) {
            $path = public_path() . '/img/ghost_person_200x200_v1.png';
        } else {
            $path = Storage::disk('s3')->get($photo);
        }
        return Image::make($path)->response();    
    }

    public function getProductPhoto($productId, $type = 'front')
    {
        $photo = ProductPhoto::where(['product_id' => $productId, 'type' => $type])->first(); 
        if ($photo === null) {
            return Response::make('', 404);
        }

        return Image::make(Storage::disk('s3')->get($photo->location))->response();
    }
}
