<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Jobs\StoreProductPhotos;

use Log;
use Response;
use Session;
use Redirect;
use Storage;
use Auth;
use App\Product;

class ProductController extends Controller
{


    public function getEditProduct($productId)
    {
        return view('product.edit')->with('productId', $productId);
    }

    public function postEditProduct(Request $request)
    {

    }

    public function showProduct($productId)
    {
        return view('product.show')->with('product', Product::find($productId));
    }


    public function getAddProduct()
    {
        return view('product.add');
    }

    public function postAddProduct(Request $request)
    {
        $product = new Product($request->only(['name', 'price', 'description']));
        $product->brand_id = $request->brand;
        $product->user_id = Auth::user()->id; 
        $product->save();

        $this->dispatch(new StoreProductPhotos([
            'product_id' => $product->id,
            'session_id' => Session::getId()
        ]));
               
        return Redirect::to('cms');
    }

    // The action is only supposed to get called during product photo
    // uploading
    public function postProductPhotoAsync(Request $request)
    {
        if( !$request->ajax() ) {
            return Response::json('' , 400);
        }
        
        static $acceptedTypes =['front', 'back', 'top', 'bottom', 'custom1', 'custom2'];
        foreach( $acceptedTypes as $type) {
            if( $request->hasFile($type) && $request->file($type)->isValid() )
                break;
        }

        $id = Session::getId();
        $this->_holdFile($id, $request->file($type), $type);

        return Response::json(['status' => 'ok']);
    }

    protected function _holdFile($sessionId, $file, $type)
    {
        //$tmpDir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . '/tmp';
        $dir = 'tmp/' . $sessionId;

        if(! $this->exists($dir) ) {
            Storage::disk('local')->makeDirectory($dir);
        }

        $destPath = $dir . '/' . $type . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->put($destPath, file_get_contents($file));
    }

    protected function exists($dir)
    {
        $parts = explode('/', $dir);
        $parts = array_values(
            array_filter($parts, function($part) { return strlen($part) > 0; })
        );

        if( count($parts) > 2 ) {
            Log::warning('_hasDirectory() only supports two level recursion');
            return false;
        }

        if( in_array($parts[0], Storage::disk('local')->directories('/')) ) {
            if( count($parts) === 1) return true;
            else {
                return in_array($parts[0] . '/' . $parts[1], Storage::disk('local')->directories('/' . $parts[0]));
            }
        }

        return false;
    }
}
