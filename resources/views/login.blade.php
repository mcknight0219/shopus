@extends('layouts.master')

@section('content')
    <div class="pure-u-1-4"></div>
    {!! Form::open(['id' => 'loginForm', 'class' => implode(' ', ['pure-form', 'pure-u-1', 'pure-u-sm-1-2', 'pure-form-stacked'])])!!}
    
    <div class="margintop2"></div>

    @if( count($loginError) > 0 )
        @for ($i = 0; $i < count($loginError); $i++)
            <div class="alert">{{ $loginError[$i] }}</div>
        @endfor
    @endif

    <div class="right"><a href="/cms/register">Create Account</a></div>
    {!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}

    {!! Form::email('email', null, ['class' => 'pure-input-1', 'placeholder' => 'your@email.com', 'autofocus', 'tabindex' => '1']) !!}

    {!! Form::label('password', 'Password:', ['class' => 'control-label margintop1 inlineblock']) !!}

    {!! Form::password('password', ['class' => "pure-input-1 ", 'tabindex' => '2', 'id' => 'password']) !!}

    <div class="margintop1 centertext">
        {!! Form::button('LOG IN', ['class' => 'borderedbutton', 'tabindex' => '3']) !!}
    </div>
    {!! Form::close() !!} 
    <div class="pure-u-1-4"></div>     
@endsection