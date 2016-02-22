@extends('layouts.master')

@section('content')

<div class="pure-g">
    <div class="pure-u-1-4"></div>
    <div class="pure-u-1 pure-u-sm-1-2">
@foreach ($brands as $brand) 
   <div class="productcell margintop1" data-index-number="{{$brand->id}}">
       <div class="productphoto">
            <input type="file" class="selector">
            <img src={{$brand->logo === "" ? URL::to('/') . "/img/100x100.png" : action('BrandController@getBrandLogo', $brand->id) }}>
       </div>
       <div class="productcontent">
           <div class="titlestack">
               <div class="title singleline editable">{{strtoupper($brand->name)}}</div>
               <div class="caption singleline editable">{{$brand->website === "" ? "http://" : $brand->website}}</div>
           </div>
       </div>
   </div> 
@endforeach
    </div>
    <div class="pure-u-1-4"></div> 
</div>



@stop