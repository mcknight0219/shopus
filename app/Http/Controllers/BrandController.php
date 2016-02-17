<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Brand;

class BrandController extends Controller
{
    public function getBrand()
    {
        return view('cms.brand')->with('brands', Brand::all());
    }    
}
