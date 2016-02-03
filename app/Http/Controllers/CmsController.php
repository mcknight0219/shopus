<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CmsController extends Controller
{
    public function index()
    {
        return view('cms');  
    }


    public function getEditProfile()
    {
        return view('cms.edit');
    }

    public function postEditProfile()
    {

    }

    public function getAddProduct()
    {

    }

    public function postAddProduct()
    {

    }
}
