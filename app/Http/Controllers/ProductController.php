<?php

namespace App\Http\Controllers;

use Log;
use App\Product;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Asynchronously add a new product
     *
     * @param Illuminate\Http\Request $request
     * @return Response
     */    
    public function postAddProduct(Request $request)
    {
        $product = new Product($request->all());
        $product->user_id = Auth::user()->id; 
        $product->save();
        $product->savePhotos($request);

        if ($request->get('publish', false)) { 
            // deal with publising 
        }

        return response()->json(['status' => 'ok'], 201);
    }

    /**
     * Asynchronously edit the details of a product
     *
     * @param Illuminate\Http\Request $request
     * @param  Integer  $productId
     * @return Response
     */
    public function postEditProduct(Request $request, $productId)
    {
    }

    /**
     * Asynchronously get all products that user own
     *
     * @param Illuminate\Http\Request $request
     */
    public function getAllProduct(Request $request)
    {
        return response()->json(
            collect(auth()->user()->products)->map(function($product) {
                return $product;
            })->jsonSerialize()
        );      
    }
}
