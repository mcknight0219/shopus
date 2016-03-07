@extends('layouts.master')

@section('content')
    <div class="pure-u-1-4"></div>
    {!! Form::open(['@submit.prevent' => 'login', 'id' => 'loginForm', 'autocomplete' => 'off', 'class' => implode(' ', ['pure-form', 'pure-u-1', 'pure-u-sm-1-2', 'pure-form-stacked'])])!!}
    
    <div class="margintop2"></div>

    @if( count($loginError) > 0 )
        @for ($i = 0; $i < count($loginError); $i++)
            <div class="alert">{{ $loginError[$i] }}</div>
        @endfor
    @endif

    <div class="right"><a href="/register">Create Account</a></div>
    {!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}

    {!! Form::email('email', null, ['v-model' => 'loginData.email', 'class' => 'pure-input-1', 'placeholder' => 'your@email.com', 'autofocus', 'tabindex' => '1', 'autocomplete' => 'off']) !!}

    {!! Form::label('password', 'Password:', ['class' => 'control-label margintop1 inlineblock']) !!}

    {!! Form::password('password', ['v-model' => 'loginData.password', 'class' => "pure-input-1 ", 'tabindex' => '2', 'id' => 'password', 'autocomplete' => 'off']) !!}

    <div class="margintop2 centertext">
        {!! Form::submit('LOG IN', ['class' => 'borderedbutton', 'tabindex' => '3']) !!}
    </div>
    {!! Form::close() !!}
    <div class="pure-u-1-4"></div>     
@endsection