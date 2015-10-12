@extends('auth.layout')
@section('title', 'Login')
@section('content')
<div class="container" style="margin-top:40px">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong> Sign in</strong>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{route('auth.login')}}" method="POST">
                        {!! csrf_field() !!}
                        <fieldset>
                            <div class="row">
                                <div class="center-block">
                                    <img class="profile-img"
                                        src="{{asset('assets/images/avatar_2x.png')}}" alt="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-user"></i>
                                            </span> 
                                            <input class="form-control" placeholder="Email" name="email" type="text" autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="glyphicon glyphicon-lock"></i>
                                            </span>
                                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign in">
                                    </div>
                                    <div class="form-group">
                                        <a href="{{route('auth.linkedin')}}" class="btn btn-lg btn-primary btn-block">Sign in with LinkedIn</a>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="panel-footer ">
                    Don't have an account! <a href="{{route('auth.register')}}"> Sign Up Here </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
