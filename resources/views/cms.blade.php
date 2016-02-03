@extends('layouts.master')

@section('content')

@if( Auth::user() )
    <div class="pure-g">
        <div class="pure-u-1-6"></div>
        <div class="pure-u-1 pure-u-sm-2-3">
            <div class="pure-g">
                <div class="pure-u-1">
                    <div class="left headtitle">My Profile</div>
                    <div class="right" id="editProfile"><i class="controlwidget fa fa-pencil"></i></div>
                </div>
            </div>
            
            <div class="pure-g margintop2">
                <div class="pure-u-1 pure-u-sm-1-3">
                    <img class="pure-img" src="http://farm3.staticflickr.com/2875/9069037713_1752f5daeb.jpg" alt=""></img>
                </div> 
                <div class="pure-u-1 pure-u-sm-2-3">
                     <div class="pure-u-1 margintop1">
                        <span class="cardname marginleft1">Weixin:</span>
                        <span class="cardval">Shopus</span>
                        <span class="cardname marginleft1">Name:</span>
                        <span class="cardval">Qiang Guo</span>
                    </div>
                    <div class="pure-u-1 margintop1">
                        <span class="cardname marginleft1">Address:</span>
                        <span class="cardval">91 Tuscarora Cres. NW</span>
                    </div>
                    <div class="pure-u-1 margintop1">
                        <span class="cardname marginleft1">City:</span>
                        <span class="cardval">Calgary</span>
                        <span class="cardname marginleft1">State:</span>
                        <span class="cardval">Alberta</span>
                        <span class="cardanme" id="countryFlag"><img src={{ "/img/us.svg" }} width="20px" height="15px"></span>
                    </div> 
                    <div class="pure-u-1 margintop1">
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-shopping-bag"></i></span>
                        <span class="cardval">5</span>
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-usd"></i></span>
                        <span class="cardval">109.12</span>
                    </div>
                </div>
            </div>
            <div class="pure-g margintop2">
                <div class="pure-u-1">
                    <div class="left headtitle">Products</div>
                    <div class="right" id="unfoldProduct"><i class="controlwidget fa fa-chevron-down"></i></div>
                    <div class="right" id="addProduct"><i class= "fa fa-plus controlwidget"></i></div>
                </div>
            </div>
               
        </div>
        <div class="pure-u-1-6"></div>
    </div>
@else

@endif

@stop