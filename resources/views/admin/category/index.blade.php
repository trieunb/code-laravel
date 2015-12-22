@extends('admin.layout')

@section('page-title')
	List Category
@stop

@section('page-header')
	List Category
@stop

@section('content')
	@if (\Session::has('message'))
	    <div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><strong>{{ \Session::get('message') }}</strong></div>
	@endif

	<table class="table table-striped table-bordered table-hover" id="templates-table">
	    <thead>
	        <th><input type="checkbox" id="check_all"></th>
	        <th>Id</th>
	        <th>Title</th>
	        <th>Price</th>
	        <th>Created At</th>
	        <th>Updated At</th>
	        <th>Status</th>
	        <th>Action</th>
	    </thead>
	</table>
@stop

@section('script')

@endsection