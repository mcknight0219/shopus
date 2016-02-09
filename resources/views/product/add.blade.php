@extends('layouts.master')
@section('content')
<div class="pure-u-1-4"></div>
<div class="pure-u-1">
{!! Form::open(['id' => 'addProductForm', 'class' => 'pure-form pure-form-aligned', 'files' => true]) !!}
<fieldset>
    <div class="pure-control-group">
    {!! Form::label('brand', 'Brand:') !!}
    {!! Form::text('brand', '', ['placeholder' => 'Product Brand']) !!}    
    </div>

    <div class="pure-control-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', '', ['placeholder' => 'Product Name']) !!}
    </div>

    <div class="pure-control-group">
        {!! Form::label('price', 'Price:') !!}
        {!! Form::text('price', '', ['placeholder' => "&yen;"]) !!}
    </div>

    <div class="pure-control-group">
        {!! Form::label('description', 'Description:') !!}
        {!! Form::textarea('description', null, ['size' => '19x3']) !!}
    </div>

    <div class="pure-control-group">
        {!! Form::label('photo', 'Photos:') !!}
        {!! Form::select('phototype', ['Front' => 'Front', 'Back' => 'Back', 'Top' => 'Top', 'Bottom' => 'Bottom', 'Custom1' => 'Custom1', 'Custom2' => 'Custom2'], 'Front') !!}
        <div class="uploadcell pure-button">
            <span class="centertext">Choose File</span>
            {!! Form::file('photo', ['class' => 'uploadbutton', 'id' => 'fileSelector']) !!}
        </div>
    </div>
    {{-- The progress bar --}}
    <div class="pure-control-group narrowgap">
        {!! Form::label('', '') !!}
        <progress class="progressbar" value="0" max="100" id="uploadProgressbar" hidden></progress>
    </div>

    <div class="pure-controls">
    {!! Form::button('Publish', ['class' => 'borderedbutton']) !!}
    </div>
</fieldset>
{!! Form::close() !!}

</div>
<div class="pure-u-1-4"></div>
@stop