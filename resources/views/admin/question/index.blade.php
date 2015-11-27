@extends('admin.layout')

@section('title')
	Question
@stop

@section('content')
	<div id="questions-table"></div>
@stop

@section('script')
	<script>
	  	$('#users-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: 'http://datatables.yajrabox.com/eloquent/add-edit-remove-column-data',
	        columns: [
	            {data: 'id', name: 'id'},
	            {data: 'name', name: 'name'},
	            {data: 'email', name: 'email'},
	            {data: 'created_at', name: 'created_at'},
	            {data: 'updated_at', name: 'updated_at'},
	            {data: 'action', name: 'action', orderable: false, searchable: false}
	        ]
	    });
	</script>
@stop