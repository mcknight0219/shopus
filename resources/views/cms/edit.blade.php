@extends('layouts.master')

@section('content')
    <div class="pure-u-1-4"></div>
    {!! Form::open(['id' => 'editPrpfileForm','class' => 'pure-form pure-form-stacked pure-u-1 pure-u-sm-1-2', 'files' => true]) !!}
    <div class="margintop2"></div> 
    <fieldset>
    <div class="pure-g">
    <div class="pure-u-1 pure-u-md-2-3">
    {!! Form::label('weixin', 'Weixin ID', ['class' => 'control-label']) !!}
    {!! Form::text('weixin', $profile->weixin, ['v-model' => 'profileData.weixin', 'placeholder' => 'Your Weixin', 'class' => 'pure-u-23-24']) !!}
    </div>
    <div class="pure-u-1 pure-u-md-1-3">
        {!! Form::label('sex', 'Sex', ['class' => 'control-label']) !!}
        {!! Form::select('sex', ['M' => 'Boy', 'F' => 'Girl'], 'F') !!}
    </div>

    <div class="pure-u-1 pure-u-md-2-3">
        {!! Form::label('city', 'City', ['class' => 'control-label margintop1']) !!}
        {!! Form::text('city', $profile->city, ['' => '', 'placeholder' => 'Your City', 'class' => 'pure-u-23-24']) !!}
    </div>

    <div class="pure-u-1 pure-u-md-1-3">
        {!! Form::label('country', 'Country', ['class' => 'control-label margintop1']) !!}
        {!! Form::text('city', $profile->country, ['' => '', 'placeholder' => 'Country', 'class' => 'pure-u-23-24']) !!}
    </div>

    <div class="pure-u-1">
        {!! Form::label('photo', 'Profile Image', ['class' => 'control-label']) !!}
        <div class="uploadcell pure-button  pure-u-23-24">
            <span class="centertext">Add photo</span>
            {!! Form::file('photo', ['class' => 'uploadbutton', 'id' => 'fileSelector']) !!}
        </div>
    </div>

    <div class="margintop2 centertext">
        {!! Form::submit('Save Profile', ['class' => 'borderedbutton', 'tabindex' => '6']) !!}
    </div>
    </div>
    </fieldset>
    {!! Form::close() !!}
    
    <div class="pure-u-1-4"></div>
@stop
