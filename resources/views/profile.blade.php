@extends('layouts.master')

@section('content')
<div class="pure-g">
    <div class="pure-u-1-6"></div>
    <div class="pure-u-1 pure-u-sm-2-3">
        <div class="pure-g">
            <div class="pure-u-1">
                <div class="left headtitle">Profile</div>
            </div>
        </div> 

        @include('shared.profile', ['profile' => $profile])
    </div>
    <div class="pure-u-1-6"></div>
</div>    
@stop