<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use Response;
use Session;
use Redirect;
use Storage;
use Auth;
use App\Product;

class ProductController extends Controller
{
    public function postAddProduct(Request $request)
    {
        if (! $request->ajax()) {
            return redirect('/');
        }

        $product = new Product($request->all());
        $prodcut->brandId = $request->input('brand');
        $product->save();

        return Response::json(['status' => 'ok'], 201);
    }

    public function postEditProduct(Request $request)
    {

    }

    public function showProduct($productId)
    {
        return view('product.show')->with('product', Product::find($productId));
    }
}
