<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

use Log;
use Storage;

use App\Brand;

class BrandController extends Controller
{
    public function getBrand()
    {
        return view('cms.brand')->with('brands', Brand::all());
    }

    public function postBrandAsync(Request $request, $brandId)
    {
        if( !$request->ajax() ) {
            return Response::make('' , 400);
        }

        $brandId = filter_var($brandId, FILTER_VALIDATE_INT, ["min_range" => 0]);
        if( !$brandId ) {
            return Response::make('', 404);
        }

        $brand = Brand::find($brandId);
        if( $brand === null ) {
            return Response::make('', 404);
        }

        if( $request->has('name') ) {
            $brand->name = $request->name;
        }
        else if( $request->has('website') ) {
            $brand->website = $request->website;
        }
        else if( $request->hasFile('logo') && $request->file('logo')->isValid() ) {
            $content = file_get_contents($request->file('logo'));
            $brand->logo = md5($content);
            Storage::disk('s3')->put($request->logo, $content);
        }
        try {
            $brand->save();
            return Response::make('', 200);
        } catch( Exception $e) {
            Log::error("Failed updaing brand " . $brandId);
        }
    }
}
