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
                    <form role="form" action="#" method="POST" class="form-horizontal">
                        {!! csrf_field() !!}
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-10  col-md-offset-1 ">
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Name</label>
                                        <div class="col-md-8"> 
                                            <input class="form-control" placeholder="Name" name="name" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-4 control-label">Email</label>
                                        <div class="col-md-8"> 
                                            <input class="form-control" placeholder="Email" name="email" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Password</label>
                                        <div class="col-md-8"> 
                                            <input class="form-control" placeholder="Password" name="password" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-4">Confirm Password</label>
                                        <div class="col-md-8"> 
                                            <input class="form-control" placeholder="Confirm Password" name="password_confirmation" type="password">
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-lg btn-primary" value="Sign Up">
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