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
            return Response::make('' , 400);
        }
        
        static $acceptedTypes =['front', 'back', 'top', 'bottom', 'custom1', 'custom2'];
        foreach( $acceptedTypes as $type) {
            if( $request->hasFile($type) && $request->file($type)->isValid() )
                break;
        }

        $id = Session::getId();
        $this->_holdFile($id, $request->file($type), $type);

        return Response::make('$contents', 204);
    }

    protected function _holdFile($sessionId, $file, $type)
    {
        //$tmpDir = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . '/tmp';
        $dir = 'tmp/' . $sessionId;
        if(! $this->_hasDirectory($dir) ) {
            Storage::disk('local')->makeDirectory($dir);
        }

        $destPath = $dir . '/' . $type . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->put($destPath, file_get_contents($file));
    }

    /**
     * Read upload from _holdFile and release the temporary file
     *     
     * @param  Int      $sessionId
     * @param  String   $type
     * @return mixed    FALSE | array of file content and extension name
     */
    protected function _releaseFile($sessionId, $type)
    {
        $dir = 'tmp/' . $sessionId;
        if(! exists($dir) ) {
            return false;
        }

        foreach( Storage::disk('local')->files($dir) as $file ) {
            if( pathinfo($file)['filename'] === $type ) {
                $content = file_get_contents(storage_path() . '/app/' . $file);
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                Storage::disk('local')->delete($file);
                return ['ext' => $ext, 'content' => $content];
            }
        }
        return false;
    }
}
