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
                <img class="pure-img" src={{ 'cms/profile/photo/' . Auth::user()->id }} alt=""></img>
            </div> 
            <div class="pure-u-1 pure-u-sm-2-3">
               <div class="pure-u-1 margintop1">
                <div class="pure-g">
                    <div class="pure-u-1">
                        <span class="cardname marginleft1">Weixin:</span>
                        <span id="profileWeixinId" class="cardval">{{ $profile->weixin }}</span>
                    </div>
                </div>
            </div>
            <div class="pure-u-1 margintop1">
                <span class="cardname marginleft1">Address:</span>
                <span class="cardval">{{ $profile->address }}</span>
            </div>
            <div class="pure-u-1 margintop1">
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1">City:</span>
                        <span class="cardval">{{ $profile->city }}</span>
                    </div>
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1">State:</span>
                        <span class="cardval">{{ $profile->state }}</span>
                    </div>
                </div>
            </div> 
            <div class="pure-u-1 margintop1">
                <div class="pure-g">
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-shopping-bag"></i></span>
                        <span class="cardval">5</span>
                    </div>
                    <div class="pure-u-1-2">
                        <span class="cardname marginleft1 marginright1"><i class="fa fa-usd"></i></span>
                        <span class="cardval">109.12</span>
                    </div>
                </div>
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

    <div class="margintop2"></div>
    <div class="productcell">
        <img class="productphoto" src="http://s7d9.scene7.com/is/image/TheBay/888067642803_main?$COACHMAIN$&wid=325&hei=325&fit=fit,1">
        <div class="productcontent">
            <div class="titlestack">
            <div class="title singleline">COACH</div>
            <div class="caption singleline">Prairie Satchel In Pebble Leather</div>
            </div>
        </div>
    </div>

    <div class="productcell">
        <img class="productphoto" src="http://img1.cohimg.net/is/image/Coach/35983_lisad_a0?fmt=jpeg&wid=1034&qlt=75,1&op_sharpen=1&resMode=bicub&op_usm=1,1,6,0&iccEmbed=0">
        <div class="productcontent">
        <div class="titlestack">
            <div class="title singleline">COACH</div>
            <div class="caption singleline">EDIE shoulder bag 28</div>
        </div>
        </div>
    </div>

</div>
<div class="pure-u-1-6"></div>
</div>
@else

@endif

@stop