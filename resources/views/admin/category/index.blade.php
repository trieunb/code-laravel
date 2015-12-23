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
	        <th>Id</th>
	        <th>Name</th>
	        <th>Description</th>
	        <th>Keyword</th>
	        <th>Created At</th>
	        <th>Action</th>
	    </thead>
	    <tbody>
	    	@foreach ($categories as $category)
				<tr>
					<td>{{ $category->id }}</td>
					<td>{{ $category->name }}</td>
					<td>{{ $category->meta['description'] }}</td>
					<td>{{ $category->meta['keyword'] }}</td>
					<td>{{ $category->created_at }}</td>
					<td>
						<div class="btn-group" role="group" aria-label="...">
		                    <a class="btn btn-primary edit" href="{{ route('admin.category.get.edit', $category->id)}}"><i class="glyphicon glyphicon-edit"></i></a>
		                </div>
                	</td>
				</tr>
	    	@endforeach
	    </tbody>
	</table>
	{!! $categories->render() !!}
@stop

@section('script')
	<script>
	/*var categoryDataTable = $('#categories-table').DataTable({
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
            });*/

	</script>
@endsection