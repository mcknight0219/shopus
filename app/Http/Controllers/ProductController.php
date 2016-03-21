<?php
namespace App\Http\Controllers;

use Log;
use Auth;
use Response;
use Redirect;

use App\Product;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        $product->save();
        
        $product->savePhotos($request);

        if ($request->get('publish', false)) { 
            // deal with publising 
        }

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
