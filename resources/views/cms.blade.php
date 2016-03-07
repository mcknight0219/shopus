@extends('layouts.master')

@section('content')

    @if( Auth::user() )
        <div class="pure-g">
            <div class="pure-u-1-6"></div>
            <div class="pure-u-1 pure-u-sm-2-3">
                <div class="pure-u-1">
                    <div class="left headtitle">My Profile</div>
                </div>

                @include('shared.profile', ['profile'=> $profile])

                <div class="margintop2">
                    <div class="pure-u-1">
                        <div class="left headtitle">Products</div>
                        <div class="right">
                            <a href="/product/add">
                                <i class="fa fa-plus controlwidget"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="margintop2"></div>
                <div class="productcell">
                    <img class="productphoto"
                         src="http://s7d9.scene7.com/is/image/TheBay/888067642803_main?$COACHMAIN$&wid=325&hei=325&fit=fit,1">
                    <div class="productcontent">
                        <div class="titlestack">
                            <div class="title singleline">COACH</div>
                            <div class="caption singleline">Prairie Satchel In Pebble Leather</div>
                        </div>
                    </div>
                </div>

                <div class="productcell">
                    <img class="productphoto"
                         src="http://img1.cohimg.net/is/image/Coach/35983_lisad_a0?fmt=jpeg&wid=1034&qlt=75,1&op_sharpen=1&resMode=bicub&op_usm=1,1,6,0&iccEmbed=0">
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