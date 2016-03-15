@extends('layouts.master')
@section('content')

<div class="pure-u-1-4"></div>

<div class="pure-u-1 pure-u-sm-1-2">
    <div class="centertext headtitle margintop1">
        Add Product
    </div>
</div>
<div class="pure-u-1 margintop2">
{!! Form::open(['id' => 'addProductForm', 'class' => 'pure-form pure-form-aligned', 'files' => true]) !!}
<fieldset>
    <div class="pure-control-group">
    {!! Form::label('brand', 'Brand:') !!}
    {!! Form::text('brand', '', ['placeholder' => 'Product Brand', 'class' => 'longinput']) !!}
    </div>

    <div class="pure-control-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', '', ['placeholder' => 'Product Name', 'class' => 'longinput']) !!}
    </div>

    <div class="pure-control-group">
        {!! Form::label('price', 'Price:') !!}
        {!! Form::text('price', '', ['placeholder' => "&yen;", 'class' => 'longinput']) !!}
    </div>

    <div class="pure-control-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['size' => '19x3', 'class' => 'longinput']) !!}
    </div>

    <div class=""></div>
    
    <div class="pure-controls">
    {!! Form::button('Save', ['class' => 'borderedbutton']) !!}
    </div>
</fieldset>
{!! Form::close() !!}

</div>
<div class="pure-u-1-4"></div>
@stop
