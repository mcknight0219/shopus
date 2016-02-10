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

class ProductController extends Controller
{

    public function getAddProduct()
    {
        return view('product.add');
    }

    public function getEditProduct($productId)
    {

    }

    public function postEditProduct(Request $request)
    {

    }

    public function postAddProduct(Request $request)
    {
        $product = new Product($request->all());
		$product->save();
        
        // Let's save up product photos
        static $types = ['front', 'back', 'top', 'bottom', 'custom1', 'custom2'];
        foreach( $types as $type ) {
            $content = $this->_releaseFile(Session::getId(), $type);
            if( $content === false ) continue;
            // start a backend job
            $this->dispatch(new StoreProductPhotos(array_merge([
                'product_id'    => $product->id,
                'type'          => $type
            ], $content)));
        }
        
        return Redirect::to('cms');
    }

    public function getProductPhoto(Request $request, $productId) 
    {

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
        $dir = '/tmp/' . $sessionId;
        if(! $this->_hasDirectory($dir) ) {
            Storage::disk('local')->makeDirectory($dir);
        }
        $file->move($dir, $type . '.' . $file->getClientOriginalExtension());
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
        $dir = '/tmp/' . $sessionId;
        if(! $this->_hasDirectory($dir) ) {
            return false;
        }
        foreach( Storage::disk('local')->files($dir) as $file ) {
            if( basename($file) === $type ) {
                $content = file_get_contents($file);
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                Storage::disk('local')->delete($dir . '/' . $file);
                return ['ext' => $ext, 'content' => content];
            }
        }
    }

    // two level maximum
    protected function _hasDirectory($dir)
    {
        $parts = explode('/', $dir);
        $parts = array_filter($parts, function($part) { return strlen($part) > 0; });
        if( count($parts) > 2 ) {
            Log::warning('_hasDirectory() only supports two level recursion');
            return false;
        }

        if( in_array($parts[0], Storage::disk('local')->directories('/')) ) {
            if( count($parts) === 1) return true;
            else {
                return in_array($parts[1], Storage::disk('local')->directories('/' . $parts[0]));
            }
        }

        return false;
    }

}
