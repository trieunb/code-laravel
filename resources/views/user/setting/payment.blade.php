@extends('user.layout')
@section('title')
    Setting Payment
@stop
@section('page-header')
Setting Payment Method
@stop

@section('content')
    <div class="content">
        <div class="row"> 
            <div class="col-md-6">
                <form role="form">
                <div class="form-group">
                    <label for="control-label">Name on Card</label>
                    <input type="text" class="form-control" name="InputName" placeholder="Name on Card" required>
                </div>
                <div class="form-group">
                    <label for="control-label">Card Number</label>
                    <input type="text" class="form-control" name="InputEmail" placeholder="Card Number" required>
                </div>
                <div class="form-group">
                    <label for="control-label">Email</label>
                    <input type="email" class="form-control" name="InputEmail" placeholder="Email" required>
                </div>
                <input type="submit" name="submit" id="submit" value="Setting" class="btn btn-primary pull-right">
                </form>
            </div>
        </div>
    </div>  
@stop