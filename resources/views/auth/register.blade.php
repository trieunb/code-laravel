@extends('auth.layout')
@section('title', 'Register')
@section('content')
<div class="container" style="margin-top:40px">
    <div class="row">
        <div class="col-sm-6 col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong> Sign Up </strong>
                </div>
                <div class="panel-body">
                    <form role="form" action="{{route('auth.register')}}" method="POST" class="form-horizontal register">
                        {!! csrf_field() !!}
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <label class="control-label">First Name</label>
                                        <input class="form-control" placeholder="First Name" name="firstname" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Last Name</label>
                                        <input class="form-control" placeholder="Last Name" name="lastname" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">E-mail</label>
                                        <input class="form-control" placeholder="E-mail" name="email" type="text">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <input class="form-control" placeholder="Password" name="password" type="password">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Confirm Password</label>
                                        <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password">
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-lg btn-primary btn-block" value="Sign Up">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="panel-footer ">
                    <a href="{{route('auth.login')}}" onClick=""> Sign In Here </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection