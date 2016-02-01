@extends('layouts.master')

@section('content')

@if( Auth::user() )
    <div class="pure-g">
        <div class="pure-u-1-5"></div>
        <div class="pure-u-1 pure-u-sm-3-5">
            <h2>My Information</h2>
                @yield('infocard')
            <h2>Products</h2>
                @yield('products')
        </div>
        <div class="pure-u-1-5"></div>
    </div>
@else

@endif

@stop