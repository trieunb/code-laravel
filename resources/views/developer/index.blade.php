@extends('admin.layout')

@section('content')
@if (\Session::has('message'))
   <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
@endif
<div class="col-md-12">
    <div class="col-md-6">
        <legend>Send jobs match notification</legend>
        <form action="/developer/send_job_match_notification" method="POST">
            <input type="text" name="email"/ class="form-control" placeholder="Email address">
            <br>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</div>
@stop
