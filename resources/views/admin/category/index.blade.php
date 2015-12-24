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
	        <th width="20%">Name</th>
	        <th width="30%">Description</th>
	        <th width="25%">Keyword</th>
	        <th>Created At</th>
	        <th>Action</th>
	    </thead>
	    <tbody>
	    	@foreach ($categories as $category)
				<tr>
					<td>{{ $category->id }}</td>
					<td>{{  wordwrap(str_limit($category->name, 150), 150, "\n") }}</td>
					<td>{{ $category->meta['description'] != null ? wordwrap(str_limit($category->meta['description'], 150), 150, "\n") : '' }}</td>
					<td>{{ $category->meta['keyword'] != null ? wordwrap(str_limit($category->meta['keyword'], 150), 150, "\n") : '' }}</td>
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
