<?php
namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use App\ProductPhoto;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PhotoController extends Controller
{
    /**
     * Render the profile photo
     *
     * @return mixed
     */
    public function getProfilePhoto()
    {
        $photo = Auth::user()->profile->photo;
        return Image::make(!$photo ? $this->defaultPhoto() : Storage::disk('s3')->get($photo))->response();
    }

    /**
     * Render the product photos
     *
     * @param integer $productId
     * @param string $type
     * @return mixed
     */
    public function getProductPhoto($productId, $type = 'front')
    {
        $photo = ProductPhoto::where(['product_id' => $productId, 'type' => $type])->first(); 
        if ($photo === null) {
            return Response::make('', 404);
        }

        return Image::make(Storage::disk('s3')->get($photo->location))->response();
    }

    /**
     * Get the name of default profile photo
     *
     * @return string
     */
    protected function defaultPhoto()
    {
        return public_path() . '/img/ghost_person_200x200_v1.png';
    }
}
