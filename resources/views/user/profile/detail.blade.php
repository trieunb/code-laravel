@extends('user.layout')

@section('title')
User profile
@stop

@section('page-header')
{{ $user->present()->name }}'s profile
@stop

@section('content')
    <div id="content">
        <div class="row">
            <div class="col-md-2">
                <img class="img-responsive" src="@if($user->avatar != null && isset($user->avatar['thumb'])) {{ asset($user->avatar['thumb']) }} @else {{ asset('images/avatar.jpg') }}  @endif" >
            </div>
            <div class="col-md-9">
                <ul>
                    <li><strong>First Name</strong>: <i>{{ $user->firstname }}</i></li>
                    <li><strong>Last Name</strong>: <i>{{ $user->lastname }}</i></li>
                    <li><strong>Address</strong>: <i>{{ $user->address }}</i></li>
                    <li><strong>Country</strong>: <i>{{ $user->country }}</i></li>
                    <li><strong>Mobile phone</strong>: <i>{{ $user->mobile_phone }}</i></li>
                    <li><strong>Birthday</strong>: <i>{{ $user->dob }}</i></li>
                    <li><strong>Email</strong>: <i>{{ $user->email }}</i></li>
                    <li><strong>Link Profile</strong>: <i><a href="{{ $user->link_profile }}">{{ $user->link_profile }}</a></i></li>
                    <li><strong>Information</strong>: <i>{{ $user->infomation }}</i></li>
                </ul>
            </div>
        </div>
    </div>
@stop
