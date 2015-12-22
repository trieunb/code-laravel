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

	<table class="table table-striped table-bordered table-hover" id="categories-table">
	    <thead>
	        <th><input type="checkbox" id="check_all"></th>
	        <th>Id</th>
	        <th>Name</th>
	        <th>Description</th>
	        <th>Keyword</th>
	        <th>Created At</th>
	    </thead>
	</table>
@stop

@section('script')
	<script>
	var categoryDataTable = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("admin.category.get.datatable") }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'description', name: 'description'},
                    {data: 'keyword', name: 'keyword'},
                    {data: 'created_at', name: 'created_at',orderable: false, searchable: true},
                    {data: 'action', name: 'action',orderable: false, searchable: true}
                ],
                order: [[4, 'DESC']]
            });

	</script>
@endsection