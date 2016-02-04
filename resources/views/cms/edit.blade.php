@extends('layouts.master')

@section('content')
    <div class="pure-u-1-4"></div>
    {!! Form::open(['id' => 'editPrpfileForm','class' => 'pure-form pure-form-stacked pure-u-1 pure-u-sm-1-2', 'files' => true]) !!}
    <div class="margintop2"></div> 
    {!! Form::label('weixin', 'Weixin ID:', ['class' => 'control-label']) !!}
    {!! Form::text('weixin', '', ['placeholder' => 'Your Weixin', 'class' => 'pure-input-1']) !!}
     
    {!! Form::label('address', 'Address:', ['class' => 'control-label margintop1']) !!}
    {!! Form::text('address', '', ['placeholder' => 'Your Address', 'class' => 'pure-input-1']) !!}

    {!! Form::label('city', 'City:', ['class' => 'control-label margintop1']) !!}
    {!! Form::text('city', '', ['placeholder' => 'Your City', 'class' => 'pure-input-1']) !!}

    {!! Form::label('state', 'State / Province:', ['class' => 'control-label margintop1']) !!}
    {!! Form::select('state', ['AL'=>'AL', 'CA'=>'CA', 'AB'=>'AB', "BC"], 'AB') !!}

    {!! Form::label('photo', 'Photo:', ['class' => 'control-label margintop1']) !!}
    {!! Form::file('photo') !!}

    {!! Form::close() !!}
    <div class="margintop2 centertext">
        {!! Form::button('Save Profile', ['class' => 'borderedbutton', 'tabindex' => '6']) !!}
    </div>
    <div class="pure-u-1-4"></div>
@stop
