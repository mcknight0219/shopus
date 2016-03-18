<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Log;
use Auth;
use Storage;
use Session;
use Response;
use Redirect;

use App\Product;

class ProductController extends Controller
{
    /**
     * Asynchronously add a new product
     *
     * @return Response
     */    
    public function postAddProduct(Request $request)
    {
        if (! $request->ajax()) {
            return redirect('/');
        }

        $product = new Product($request->all());
        $product->currency = $request->get('currency');
        $product->user_id  = Auth::user()->id; 
        $product->savePhoto($request);
        $product->save();

        return Response::json(['status' => 'ok'], 201);
    }

    /**
     * Asynchronously edit the details of a product
     *
     * @param  Integer  $productId
     * @return Response
     */
    public function postEditProduct(Request $request, $productId)
    {
    }

    /**
     * Get the product detail page. Editing is available for product's owner
     *
     * @param  Integer $productId
     * @reutrn Response
     */
    public function showProduct($productId)
    {
        return view('product.show')->with('product', Product::find($productId));
    }

    
}
