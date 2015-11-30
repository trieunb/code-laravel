@extends('admin.layout')

@section('title')
	Manage User
@stop

@section('page-header')
	Manage User
@stop

@section('content')
	@if (\Session::has('message'))
		<div class="alert alert-success">
		    <button type="button" class="close" data-dismiss="alert">×</button>
		    <strong>{{ \Session::get('message') }}</strong>
	    </div>
	@endif
	<table class="table table-striped table-bordered table-hover" id="users-table">
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
	<script>
		$('#users-table').DataTable({
		        processing: true,
		        serverSide: true,
		        responsive: true,
		        ajax: '{{ route("api.admin.user.get.dataTable") }}',
		        columns: [
		            {data: 'id', name: 'id'},
		            {data: 'firstname', name: 'firstname'},
		            {data: 'address', name: 'address'},
		            {data: 'email', name: 'email'},
		            {data: 'created_at', name: 'created_at'},
		            {data: 'updated_at', name: 'updated_at'},
		            {data: 'action', name: 'action', orderable: false, searchable: false}
		        ]
		    });
	</script>
@stop