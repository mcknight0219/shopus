<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Response;

use Log;
use Storage;
use Image;

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

        Log::info($request->all());

        if( $request->has('name') ) {
            $brand->name = $request->name;
        }
        else if( $request->has('website') ) {
            Log::info($request->website);
            $brand->website = $request->website;
        }
        else if( $request->hasFile('logo') && $request->file('logo')->isValid() ) {
            $content = file_get_contents($request->file('logo'));
            $brand->logo = md5($content) . '.' . $request->file('logo')->getClientOriginalExtension();
            
            Storage::disk('s3')->put($brand->logo, $content);
        }

        try {
            $brand->save();
            return Response::make('', 200);
        } catch( Exception $e) {
            Log::error("Failed updaing brand " . $brandId);
        }
    }

    public function getBrandLogo(Request $request, $brandId)
    {
        $brand = Brand::find($brandId);
        if( $brand === null || $brand->logo === '' ) {
            return Response::make('', 200);
        }
        return Image::make(Storage::disk('s3')->get($brand->logo))->response();
    }
}
