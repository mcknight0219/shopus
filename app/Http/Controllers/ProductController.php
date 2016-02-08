<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

    // The action is only supposed to get called during product
    public function postProductPhotoAsync(Request $request)
    {
        if( !$request->ajax() ) {
            return;
        }
        
        if( $request->hasFile('photo') && $request->file('photo')->isValid() ) {
            // store the file in session. Later when the form is actually submitted,
            // we could retrieve them
            Session::
        }
    }

}
