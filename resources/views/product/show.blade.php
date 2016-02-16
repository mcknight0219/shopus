@extends('layouts.master')

@section('content')
<div class="pure-g">
    <div class="pure-u-1-4"></div>
    <div class="pure-u-1 pure-u-sm-1-2">
        <div class="pure-g">
            <div class="pure-u-1">
                <div class="left headtitle">Product</div>
            </div>
        </div>

        <div class="pure-g margintop2">
            <div class="pure-u-1">
                <img src="{{ action('PhotoController@getProductPhoto', [$product->id]) . '?type=front' }}" alt="">
            </div>
        </div>
    </div>
    <div class="pure-u-1-4"></div>
</div>
@stop