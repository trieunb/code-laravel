@extends('admin.layout')

@section('title')
	Manage User
@stop

@section('content')
	<table class="table table-striped table-bordered table-hover" id="questions-table">
		<thead>
			<th>Id</th>
			<th>Fullname</th>
			<th>Address</th>
			<th>Email</th>
			<th>Created At</th>
			<th>Updated At</th>
			<th>Action</th>
		</thead>
	</table>
@stop

@section('script')
	
@stop