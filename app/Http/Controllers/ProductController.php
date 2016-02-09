<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;
use Session;

class ProductController extends Controller
{

    public function getAddProduct()
    {
        return view('product.add');
    }

    public function postAddProduct()
    {

    }

    public function getProductPhoto(Request $request, $productId) 
    {

    }

    // The action is only supposed to get called during product photo
    // uploading
    public function postProductPhotoAsync(Request $request)
    {
        if( !$request->ajax() ) {
            return;
        }
        
        if( $request->hasFile('photo') && $request->file('photo')->isValid() ) {
            $upload = &Session::get('productPhotoUpload', []);
            $type = strtolower(Input::get('type'));
            static $acceptedTypes =['front', 'back', 'top', 'bottom', 'custom1', 'custom2'];
            if(! in_array($type, $acceptedTypes) ) {
                return;
            }

            $upload[$type] = Input::get('file');
        }
    }

}
