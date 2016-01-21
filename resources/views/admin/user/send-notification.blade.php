@extends('admin.layout')

@section('title')
Send Notification
@stop

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">
@stop

@section('page-header')
Send Notification
@stop

@section('content')
@if (\Session::has('message'))
    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
@elseif(\Session::has('error'))
    <div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('error') }}</strong></div>
@endif
@include('partial.notifications')
<form class="form-horizontal" action="{{ route('admin.user.post.send-notification') }}" method="post">
    <div class="form-group">
        <label for="inputEmail3" class="col-sm-2 control-label">Select Users</label>
        <div class="col-sm-8">
            {!! Form::select('user_id[]', $list_users, null, ['class' => 'form-control', 'id' => 'list-users', 'multiple' => 'multiple']) !!}
        </div>
        <div class=" col-sm-2">
            <div class="checkbox">
                <label>
                  <input type="checkbox" name="send_all"> Send to all
                </label>
            </div>
        </div>
    
    </div>
    <div class="form-group">
        <label for="inputPassword3" class="col-sm-2 control-label">Message</label>
        <div class="col-sm-8">
            <textarea name="message" class="form-control">{{ old('message') }}</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </div>
</form>
@stop

@section('script')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script>
    var select2 = $("#list-users").select2({  placeholder: "Select a user"});
</script>
@stop