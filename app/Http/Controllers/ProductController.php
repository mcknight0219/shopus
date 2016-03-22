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

        return Response::json(['status' => 'ok'], 201);
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
            collect(Auth::user()->products())->map(function($product) {
                $photos = collect($product->photos())->reduce(function ($carry, $photo) {
                    $carry[$photo->type] = $photo->location;
                    return $carry;
                }, []);
                return array_merge($product->toArray(), $photos->toArray());
            })->jsonSerialize()
        );      
    }
}
