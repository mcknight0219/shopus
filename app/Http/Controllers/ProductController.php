<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;
use Input;
use Response;
use Session;

class ProductController extends Controller
{

    public function getAddProduct()
    {
        return view('product.add');
    }

    public function postAddProduct()
    {
        $product = new Product($request->all());
        
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
